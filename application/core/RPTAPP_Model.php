<?php

(defined('BASEPATH')) OR exit('No direct script access allowed');

class RPTAPP_Model extends CI_Model {

    protected $_table;
    protected $_table_mapping = [];
    //private $_exclude_table_mapping = [];
    protected $_database;
    protected $primary_key = 'id';
    protected $itemID = 0;
    protected $Items = [];
    public $db_return_type = 'object';
    protected $_temporary_return_type = NULL;
    protected $_db_group = '';
    protected $db_Select = [];
    protected $db_Joins = [];
    protected $db_Groups = [];
    protected $db_Having = [];
    protected $db_Order = [];
    protected $db_Limit = [];
    protected $db_Filters = [];
    protected $_fields = [];
    protected $_DataItem;
    protected $callback_parameters = [];
    protected $before_setData = [];
    protected $before_setDataItems = [];
    protected $before_delete = [];
    protected $after_delete = [];
    protected $before_insert = [];
    protected $after_insert = [];
    protected $before_update = [];
    protected $after_update = [];
    protected $before_mapping = [];
    protected $_table_mapping_delete_filter = [];
    protected $_date;
    protected $managed_all_items = [];
    protected $_parent_order_field = 'parent_id';
    protected $_item_ordering_field = 'item_ordering';
    protected $_item_ordering_temp_list = [];
    protected $_item_ordering_grp_clauses = [];
    protected $_extra_grp_clauses = [];
    protected $_extra_grp_clauses_alias = [];
    protected $_filters_set = ['db_Select', 'db_Filters', 'db_Joins', 'db_Order', 'db_Groups', 'db_Having', 'db_Limit'];

    //protected $_cache_on = 1;

    public function __construct() {
        parent::__construct();
        $this->_database = $this->db;
        $this->_setDB_connection();
        $this->_fetch_table();
        $this->_temporary_return_type = $this->db_return_type;
        $this->_date = new DateTime();
        $this->_DataItem = new StdClass();
    }

    public function getVal($property) {
        return (property_exists($this, $property)) ? $this->$property : '';
    }

    public function setVal($property, $value = '') {
        if (property_exists($this, $property)) :
            $this->$property = $value;
        endif;
    }

    private function _fetch_primary_key() {
        if ($this->primary_key == NULL) :
            $this->primary_key = $this->_database->query("SHOW KEYS FROM `" . $this->_table . "` WHERE Key_name = 'PRIMARY'")->row()->Column_name;
        endif;
    }

    private function _fetch_table() {
        if ($this->_table == NULL) :
            $this->_table = plural(preg_replace('/(_m|_model)?$/', '', strtolower(get_class($this))));
        endif;
    }

    private function _setDB_connection() {
        if ($this->_db_group != NULL) :
            $this->_database = $this->load->database($this->_db_group, TRUE);
        endif;
    }

    private function _setFields() {
        if ($this->db_return_type == 'object') : foreach ($this->_fields as $k => $v) : $this->_DataItem->$k = $v;
            endforeach;
        else : $this->_DataItem = $this->_fields;
        endif;
    }

    public function _trigger($event, $data = FALSE, $last = TRUE) {
        if (isset($this->$event) && is_array($this->$event) && count($this->$event) > 0) :
            foreach ($this->$event as $k => $method) :
                if (strpos($method, '(')) :
                    preg_match('/([a-zA-Z0-9\_\-]+)(\(([a-zA-Z0-9\_\-\., ]+)\))?/', $method, $matches);
                    $method = $matches[1];
                    $this->callback_parameters = explode(',', $matches[3]);
                endif;
                $last = (filter_var($k, FILTER_VALIDATE_INT) === false) ? $k : $last;
                $data = call_user_func_array(array($this, $method), array($data, $last));
            endforeach;
        endif;
        return $data;
    }

    protected function setDataItem($item_id) {
        //$data_id = ($this->db_return_type != 'object')? $this->_DataItem[$this->primary_key]:$this->_DataItem->{$this->primary_key};
        //if($item_id > 0 && $data_id < 1) : 
        if ($item_id > 0) :
            $result = $this->_getDataItems(['db_Filters' => ['where' => ['a.' . $this->primary_key => $item_id]]]);
            if ($result->num_rows() > 0) :
                $row = ($this->db_return_type == 'object') ? $result->row() : $result->row_array();
                $this->_DataItem = $this->_trigger('before_setData', $row);
                $this->itemID = ($this->db_return_type != 'object') ? $row[$this->primary_key] : $row->{$this->primary_key};
            endif;
            $result->free_result();
        else :
            $this->_setFields();
        endif;
    }
    
    protected function runOperation($hook, $value, $column) {
        switch($hook) : 
            case 'explode' : $value = explode(',', $value); break;
            case 'implode' : $value = implode(',', $value); break;
            case 'json_decode_array' : $value = json_decode($value, 1); break;
            case 'password_hash' : 
                //$value = password_hash($value, PASSWORD_BCRYPT, ['cost' => 11, 'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM)]); 
                $value = password_hash($value, PASSWORD_BCRYPT);
            break;
            case 'decimal' : 
                $value = number_format($value, 2);
            break;
            default: $value = $hook($value);
            break; 
        endswitch;
        return $value;
    }
    
    public function setOperation($row, $hook = 'json_encode') {
        foreach ($this->callback_parameters as $column) :
            if (is_array($row)) : if (!isset($row[$column])) : continue; endif;
                $row[$column] = $this->runOperation($hook, $row[$column], $column);
            else : if (!isset($row->$column)) : continue; endif;
                $row->$column = $this->runOperation($hook, $row->$column, $column);
            endif;
        endforeach;
    return $row;
    }

    protected function timeStamps($row) {
        $tdate = $this->_date->format($this->config->item('timestamp_date_format'));
        foreach ($this->callback_parameters as $column) :
            if (is_array($row)) : $row[$column] = $tdate;
            else : $row->$column = $tdate;
            endif;
        endforeach;
        return $row;
    }
    
    protected function changeDateTime($row, $format='Y-m-d') {
        foreach ($this->callback_parameters as $column) :
            if (is_array($row)) {
                if (!isset($row[$column])) {
                    continue;
                }
                $row[$column] = formatDateTime($row[$column], $format);
            }
            else {
                if (!isset($row->$column)) {
                    continue;
                }
                $row->$column = formatDateTime($row->$column, $format);
            }
        endforeach;
        return $row;
    }

    protected function alterItemBy($row) {
        $user_id = isset($this->session->userdata('logged_in')->id)? $this->session->userdata('logged_in')->id:0;
        foreach ($this->callback_parameters as $column) :
            if (is_array($row)) : $row[$column] = $user_id;
            else : $row->$column = $user_id;
            endif;
        endforeach;
        return $row;
    }

    private function set_db_query_data($filter_arr) {
        $this->Items = []; 
        //if(count($filter_arr) > 0) : 
        foreach ($this->_filters_set as $v) : $vm = $v . '_m';
            if (isset($filter_arr[$v])) : $this->$v = $filter_arr[$v];
            elseif (isset($this->$vm)) : $this->$v = $this->$vm;
            else : $this->$v = [];
            endif;
        endforeach;
        //endif;
    }

    protected function setQueryFilters($filters) {
        if (count($filters) > 0) :
            foreach ($filters as $k => $v) :
                switch ($k) :
                    case 'where' :
                    case 'or_where' :
                        $this->_database->$k($v);
                        break;
                    case 'where_in' :
                    case 'or_where_in' :
                    case 'where_not_in' :
                    case 'or_where_not_in' :
                        foreach ($v as $kk => $vv) : $this->_database->$k($kk, $vv);
                        endforeach;
                        break;
                    case 'like' :
                    case 'or_like' :
                    case 'not_like' :
                    case 'or_not_like' :
                        foreach ($v as $kk => $vv) : (!filter_var($kk, FILTER_VALIDATE_INT) === false) ? $this->_database->$k(implode(', ', $vv)) : $this->_database->$k($kk, $vv);
                        endforeach;
                        break;
                    case 'group_start' :
                    case 'or_group_start' :
                    case 'not_group_start' :
                    case 'or_not_group_start' :
                        $this->_database->$k();
                        $this->setQueryFilters($v);
                        $this->_database->group_end();
                        break;
                endswitch;
            endforeach;
        endif;
    }

    protected function _getDataItems($filter_arr = []) {
        $this->set_db_query_data($filter_arr);
        $this->_database->reset_query();

        foreach ($this->db_Select as $k => $v) : $this->_database->select($v);
        endforeach;

        $this->_database->from($this->_table . ' as a');

        if (count($this->db_Joins) > 0) :
            foreach ($this->db_Joins as $k => $v) :
                if (count($v) > 2) : $this->_database->join($v[0], $v[1], $v[2]);
                elseif (count($v) > 1) : $this->_database->join($v[0], $v[1]);
                else : $this->_database->join($v[0]);
                endif;
            endforeach;
        endif;

        $this->setQueryFilters($this->db_Filters);

        /*if (count($this->db_Filters) > 0) :
            foreach ($this->db_Filters as $k => $v) :
                switch ($k) :
                    case 'where' :
                    case 'or_where' :
                        $this->_database->$k($v);
                        break;
                    case 'where_in' :
                    case 'or_where_in' :
                    case 'where_not_in' :
                    case 'or_where_not_in' :
                        foreach ($v as $kk => $vv) : $this->_database->$k($kk, $vv);
                        endforeach;
                        break;
                    case 'like' :
                    case 'or_like' :
                    case 'not_like' :
                    case 'or_not_like' :
                        foreach ($v as $kk => $vv) : (!filter_var($kk, FILTER_VALIDATE_INT) === false) ? $this->_database->$k(implode(', ', $vv)) : $this->_database->$k($kk, $vv);
                        endforeach;
                        break;
                endswitch;
            endforeach;
        endif;*/

        if (count($this->db_Groups) > 0) : foreach ($this->db_Groups as $k => $v) : $this->_database->group_by($v);
            endforeach;
        endif;
        if (count($this->db_Having) > 0) : foreach ($this->db_Having as $k => $v) : $this->_database->$k($v[0], $v[1]);
            endforeach;
        endif;
        if (count($this->db_Order) > 0) : foreach ($this->db_Order as $k => $v) : $this->_database->order_by($k, $v);
            endforeach;
        endif;
        if(count($this->db_Limit) > 0) : 
            $this->_database->limit($this->db_Limit[0], $this->db_Limit[1]);
        endif;
        return $this->_database->get();
    }

    protected function setDataItems($filter_arr = []) {
        $result = $this->_getDataItems($filter_arr);
        //echo $this->_database->last_query().'<br />';
        if ($result->num_rows() > 0) :
            if ($this->db_return_type == 'object') : 
                foreach ($result->result() as $row) : 
                    //if(!$row->{$this->primary_key}) : continue; endif; 
                    $this->Items[$row->{$this->primary_key}] = $this->_trigger('before_setDataItems', $row);
                endforeach;
            else : foreach ($result->result_array() as $row) : 
                    //if(!$row[$this->primary_key]) : continue; endif; 
                    $this->Items[$row[$this->primary_key]] = $this->_trigger('before_setDataItems', $row);
                endforeach;
            endif;
        endif;
        $result->free_result();
    }

    protected function deleteItem($item_id) {
        $success = 0;
        $this->setDataItem($item_id);
        if ($this->itemID) :
            $this->_database->trans_start();
            $this->_trigger('before_delete', $this->_DataItem);
            $this->_database->delete($this->_table, array($this->primary_key => $this->itemID));
            $success = $this->_database->affected_rows();
            if ($success) :
                $this->_trigger('after_delete', $this->_DataItem);
            endif;
            $this->_database->trans_complete();
        endif;
        return $success;
    }

    private function _insertItem($data) {
        $affected = 0;
        $data = $this->_trigger('before_insert', $data);
        $this->_database->set($data);
        $this->_database->insert($this->_table);
        if ($affected = $this->_database->affected_rows()) :
            $this->itemID = $this->_database->insert_id();
            $this->_trigger('after_insert', $this->input->post());
        endif;
        return $affected;
    }

    private function _updateItem($data) {
        $affected = 0;
        $data = $this->_trigger('before_update', $data);
        $this->_database->where($this->primary_key, $this->itemID);
        $this->_database->update($this->_table, $data);
        if ($affected = $this->_database->affected_rows()) :
            $this->_trigger('after_update', $this->input->post());
        endif;
        return $affected;
    }
    
    protected function _FreeUpdate($data) {
        $this->_database->where($this->primary_key, $this->itemID);
        $this->_database->update($this->_table, $data);
    }
    
    protected function saveItem($data) {
        $success = 0;
        $this->_database->reset_query();
        $this->_database->trans_start();
        $success = ($this->itemID) ? $this->_updateItem($data) : $this->_insertItem($data);
        $success = ($success) ? $this->itemID : $success;
        $this->_database->trans_complete();
        return $success;
    }

    protected function manageParentGroupOrder_OwnTable($post_data) {
        $all_child = get_all_children($this->managed_all_items, $this->itemID, $this->_parent_order_field);
        if (in_array($post_data[$this->_parent_order_field], $all_child)) :
            $this->_database->where($this->_parent_order_field, $this->itemID);
            $this->_database->update($this->_table, [$this->_parent_order_field => $this->_DataItem->{$this->_parent_order_field}]);
        endif;
    return $post_data;
    }
    
    protected function preMappingCleanUp($post_data, $chkbx, $tb_map_key) {
        $unset = 0;
        if(!isset($post_data[$chkbx])) : $unset = 1;
        elseif($post_data[$chkbx] == 1) : $unset = 1;
        elseif($post_data[$chkbx] != 1) : $unset = 0;
        endif;
    
        if($unset) :
            $this->_database->delete($tb_map_key, [$this->_table_mapping[$tb_map_key]['fkey'] => $this->itemID]);
            unset($this->_table_mapping[$tb_map_key]);
        endif;
    }

    protected function buildDataBatchMapping($item, $post_data, $k1='', $k2='') {
        $fill_data = [];
        foreach($item['map_fields'] as $map_k => $map_v) {
            $val = 0;
            if (is_numeric($k1) && is_numeric($k2)) {
                $val = (isset($post_data[$map_k][$k1][$k2]))? $post_data[$map_k][$k1][$k2]:$post_data[$map_k][$k1];
            } elseif (is_numeric($k1) || is_numeric($k2)) {
                $val = $post_data[$map_k][$k1];
            } else {
                $val = $post_data[$map_k];
            }
            $fill_data[$map_v] = $val;
        }
        $fill_data[$item['fkey']] = $this->itemID;
    return $fill_data;
    }

    protected function manageDataMapping($post_data) {
        $post_data = $this->_trigger('before_mapping', $post_data);
        if (count($this->_table_mapping) > 0) :
            foreach ($this->_table_mapping as $k => $v) : $data = [];
                if(!isset($post_data[$v['post_field']])) : continue; endif;
                if(is_array($post_data[$v['post_field']])) : 
                    if(count($post_data[$v['post_field']]) < 1) : continue; endif;
                    // old solution
                    /*foreach ($post_data[$v['post_field']] as $k1 => $v1) :
                        $fill_data = [];
                        foreach($v['map_fields'] as $map_k => $map_v) : 
                            $fill_data[$map_v] = $post_data[$map_k][$k1];
                        endforeach;
                        $fill_data[$v['fkey']] = $this->itemID;
                        $data[] = $fill_data;
                    endforeach;*/
                    // new solution for 2nd level hierarchy but not nth level (no recursion)
                    foreach ($post_data[$v['post_field']] as $k1 => $v1) :
                        if(!is_array($v1)) {
                            $data[] = $this->buildDataBatchMapping($v, $post_data, $k1);
                        }
                        else {
                            foreach($v1 as $mk2 => $mv2) {
                                $data[] = $this->buildDataBatchMapping($v, $post_data, $k1, $mk2);
                            }
                        }
                    endforeach;
                else :
                    $data[] = $this->buildDataBatchMapping($v, $post_data);
                endif;
                
                if(isset($this->_table_mapping_delete_filter[$k])) : 
                    foreach($this->_table_mapping_delete_filter[$k]['filters'] as $map_dk => $map_dv) : 
                        foreach($map_dv as $condk => $condv) :
                            $this->_database->$map_dk($condk, $condv);
                        endforeach;
                    endforeach;
                endif;    
                
                $this->_database->delete($k, [$v['fkey'] => $this->itemID]);
                $this->_database->insert_batch($k, $data);
            endforeach;
        endif;
    return $post_data;
    }
    
    protected function getExtraGrpClauses_itemOrdering($row) {
        if(count($this->_item_ordering_grp_clauses) > 0) :
            foreach($this->_item_ordering_grp_clauses as $k => $v) : 
                $this->_extra_grp_clauses[$k] = $row[$v];
                $this->_extra_grp_clauses_alias['a.' .$k] = $row[$v];
            endforeach;
        endif;
    }
    
    protected function itemOrderingProcessBegin($row) {
        $_extra_grp_clauses = $this->getExtraGrpClauses_itemOrdering($row);
        if(($row[$this->_parent_order_field] != $this->_DataItem->{$this->_parent_order_field}) || $this->_DataItem->{$this->primary_key} < 1) : 
            $filter = ['a.' . $this->_parent_order_field => $row[$this->_parent_order_field]];
            if(count($this->_extra_grp_clauses_alias) > 0) : $filter += $this->_extra_grp_clauses_alias; endif;
            $this->setItems(['db_Select' => ['a.' . $this->primary_key], 'db_Order'=>[], 'db_Joins' => [], 'db_Filters' => ['where' => $filter]]);
            $row[$this->_item_ordering_field] = (count($this->Items) > 0) ? (count($this->Items) + 1) : 1;
            if($this->_DataItem->{$this->primary_key} > 0) :
                $filter = ['a.' . $this->_parent_order_field => $this->_DataItem->{$this->_parent_order_field}];
                if(count($this->_extra_grp_clauses_alias) > 0) : $filter += $this->_extra_grp_clauses_alias; endif;
                $this->setItems(['db_Select' => ['a.' . $this->primary_key], 'db_Order'=>[], 'db_Joins' => [], 'db_Filters' => ['where' => $filter]]);
                $this->_item_ordering_temp_list = $this->Items;
                if(isset($this->_item_ordering_temp_list[$this->itemID])) : unset($this->_item_ordering_temp_list[$this->itemID]); endif;
            endif;
        endif;
    return $row;    
    }

    protected function itemOrderingProcessEnd($post_data) {
        $new_ordering = $post_data[$this->_item_ordering_field];
        $old_ordering = $this->_DataItem->{$this->_item_ordering_field};
        $item_ordering_fld = $this->_item_ordering_field;
        
        $new_parent = $post_data[$this->_parent_order_field]; 
        $old_parent = $this->_DataItem->{$this->_parent_order_field};
        
        if($new_parent == $old_parent) : 
            if ($new_ordering != $old_ordering) :
                if ($new_ordering > $old_ordering) :
                    $conditions = [$item_ordering_fld . ' > ' => $old_ordering, $item_ordering_fld . ' <= ' => $new_ordering];
                    $set_field = $item_ordering_fld . '-1';
                else :
                    $conditions = [$item_ordering_fld . ' >= ' => $new_ordering, $item_ordering_fld . ' < ' => $old_ordering];
                    $set_field = $item_ordering_fld . '+1';
                endif;                
                $parent_id = $new_parent;
                
                $this->_database->set($item_ordering_fld, $set_field, FALSE);
                $this->_database->where($conditions);
                if(count($this->_extra_grp_clauses) > 0) : $this->_database->where($this->_extra_grp_clauses); endif;
                $this->_database->where($this->_parent_order_field, $parent_id);
                $this->_database->where_not_in($this->primary_key, $this->_DataItem->{$this->primary_key});
                $this->_database->update($this->_table);
            endif;            
        elseif(count($this->_item_ordering_temp_list) > 0) : 
            $conditions = [$item_ordering_fld . ' > ' => $old_ordering];
            $set_field = $item_ordering_fld . '-1';
            $parent_id = $old_parent;
            
            $this->_database->set($item_ordering_fld, $set_field, FALSE);
            $this->_database->where($conditions);
            if(count($this->_extra_grp_clauses) > 0) : $this->_database->where($this->_extra_grp_clauses); endif;
            $this->_database->where($this->_parent_order_field, $parent_id);
            $this->_database->where_in($this->primary_key, array_keys($this->_item_ordering_temp_list));
            $this->_database->update($this->_table);
            
            $this->_database->reset_query();
            $filter = ['where_not_in' => ['a.' . $this->primary_key => array_keys($this->_item_ordering_temp_list)]];
            if(count($this->_extra_grp_clauses_alias) > 0) : $filter['where'] = $this->_extra_grp_clauses_alias; endif;
            $this->setItems(['db_Select' => ['a.' . $this->primary_key], 'db_Order'=>[], 'db_Joins' => [], 'db_Filters' => $filter]);
            if(count($this->Items) > 0) :
                $set_data = [];
                $primary_keys = array_keys($this->Items); $inc = count($this->_item_ordering_temp_list)+1;
                foreach($primary_keys as $kv) : 
                    $set_data[] = [$this->primary_key => $kv, $item_ordering_fld => $inc];
                    $inc++; 
                endforeach;
                $this->_database->where($this->_parent_order_field, $parent_id);
                if(count($this->_extra_grp_clauses) > 0) : $this->_database->where($this->_extra_grp_clauses); endif;
                $this->_database->where_not_in($this->primary_key, array_keys($this->_item_ordering_temp_list));
                $this->_database->update_batch($this->_table, $set_data, $this->primary_key);
            endif;
            
        endif;
        
        
    return $post_data;
    }

}
