<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Extension_model extends RPTAPP_Model {
	
	protected $_table = 'extensions';
	protected $_fields = ['id'=> 0, 'ext_name'=> '', 'ext_heading'=>'', 'ext_show_heading'=>'', 'ext_type'=>1, 'module_id'=>0, 'domain_id'=>0, 'acl_level_id'=>1, 'user_groups'=>[], 'position_id'=>0, 'is_ext_global'=>0, 'selected_pages'=>'', 'modified_by' => 0, 'modified_on' => '', 'published' => 1];
	protected $db_Select_m = ['a.*', 'b.title as domain'];
    protected $db_Joins_m = [
        ['domains as b', 'b.id = a.domain_id', 'left'],
        ['extensions_positions as c', 'c.id = a.position_id', 'left']
    ];
	protected $db_Order_m = ['b.title'=> 'asc', 'a.ext_name'=> 'asc'];

    protected $before_setDataItems = ['json_decode_array' =>'setOperation(user_groups,selected_pages)'];
    protected $before_setData = ['json_decode' =>'setOperation(user_groups)', 'read' => 'selected_pages_processing'];

    protected $before_update = ['timeStamps(modified_on)', 'alterItemBy(modified_by)', 'json_encode' =>'setOperation(user_groups)', 'save' => 'selected_pages_processing'];
    protected $before_insert = ['timeStamps(modified_on)', 'alterItemBy(modified_by)', 'json_encode' =>'setOperation(user_groups)', 'save' => 'selected_pages_processing'];
        
	public $data_items = [];
	public $data_item;
	
	public function __construct() {
		parent::__construct();
    }
	
	public function setItem($item_id) {
		parent::setDataItem($item_id);
		$this->data_item = $this->_DataItem;
	}
	
	public function setItems($filter_arr=[]) {
		parent::setDataItems($filter_arr);
		$this->data_items = $this->Items;
	}
	
	public function save($data) {
		return parent::saveItem($data);
	}
	
	public function delete($item_id) {
		return parent::deleteItem($item_id);
	}

	protected function selected_pages_processing($row, $hook='save') {
        $new_line_char = "\n"; $cntrl_sep = ':'; $mthd_sep = '|';
	    if($hook == 'save') {
            $arr = [];
            if($row['selected_pages'] != '') {
                $arr_temp = array_filter(array_map('trim', explode($new_line_char, $row['selected_pages'])));
                if(count($arr_temp) > 0) {
                    foreach($arr_temp as $v) {
                        $arr_temp1 = array_filter(array_map('trim', explode($cntrl_sep, $v)));
                        if(count($arr_temp1) > 1) {
                            $arr[$arr_temp1[0]] = array_filter(array_map('trim', explode($mthd_sep, $arr_temp1[1])));
                        }
                        else {
                            $arr[$arr_temp1[0]] = [];
                        }
                    }
                }
            }
            $row['selected_pages'] = (count($arr) > 0)? json_encode($arr):json_encode([]);
        }
        else {
            $arr = [];
            $arr_temp = (is_object($row))? json_decode($row->selected_pages, 1):json_decode($row['selected_pages'], 1);
            if(count($arr_temp) > 0) {
                foreach($arr_temp as $k => $v) {
                    $arr[] = (count($v) > 0)? $k.$cntrl_sep.implode($mthd_sep, $v):$k;
                }
            }
            $arr = (count($arr) > 0)? implode($new_line_char, $arr):'';
            if(is_object($row)) {
                $row->selected_pages = $arr;
            }
            else {
                $row['selected_pages'] = $arr;
            }
        }

        return $row;
    }

}
