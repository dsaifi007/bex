<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Price_types extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'vessels';
    protected $list_link = 'vessels/price_types';
    protected $tbl = 'vessels_price_types';
    
    protected $model_name = 'vessels/Price_type_model';
    protected $model = 'price_type_model';
    protected $is_model = 0;

    protected $utility_cnfg = [
        'select'=> ['a.id', 'a.type_name'],
        'filters'=> ['where'=>['a.published'=>1]],
        'hooks'=> ['FormattedResultList(type_name)']
    ];
    protected $item_ordering_list = [];
    protected $display_type_list = [];
    protected $currency_codes_list = [];
    protected $parent_list = [];

    protected $grid_settings = [
        'order_cols'=> ['a.type_name', 'a.currency_code', 'a.modified_on' , 'a.published'],
        'search_cols'=> ['a.type_name', 'a.currency_code',],
        'fixed_filters' => []
    ];

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/price_types');
        $this->_loadModelEnv();
    }
    
    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->_initEnv();
        $this->BuildContentEnv(['table']);
        $data['currency_codes_list']=$this->config->item('currency_codes_list');
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/price_types.js', 'page');
        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/price_types';
        $data['view_class'] = 'price_types';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if ($this->item_ID) :
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_price_type';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_price_type';
        endif;
        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/price_type_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/price_type_form';
        $data['view_class'] = 'price_type_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $is_unique = ($this->item_ID > 0 && strtolower($this->input->post('type_name')) == strtolower($this->{$this->model}->data_item->type_name)) ? '' : '|is_unique[' . $this->tbl . '.type_name]';
        $this->form_validation->set_rules('type_name', 'lang:err_price_type_name', 'trim|required|min_length[2]|max_length[50]'.$is_unique);
        $this->form_validation->set_rules('currency_code', 'lang:err_price_currency_code', 'trim|required|in_list[' . implode(',', array_keys($this->currency_codes_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('display_type', 'lang:err_price_display_type', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->display_type_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        if ($this->item_ID > 0  && $this->input->post('parent_id') == $this->{$this->model}->data_item->parent_id) :
            $this->form_validation->set_rules('item_ordering', 'lang:err_price_item_ordering', 'trim|required|in_list[' . implode(',', array_keys($this->item_ordering_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        endif;
        $this->form_validation->set_rules('published', 'lang:err_published', 'trim|required|in_list[' . implode(',', array_keys($this->boolean_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        return $this->form_validation->run();
    }

    protected function FormProcess($data) {
        $this->_initEnv();
        $this->{$this->model}->setItem($this->item_ID);
        if ($this->item_ID > 0 && ($this->item_ID != $this->{$this->model}->data_item->id)) :
            $this->unauthorized_access();
        endif;
        build_postvar(['item_ordering' => 0]);
        $items = $this->get_Items(['hooks' => ['treeOrder']]);
        $this->parent_list = $data['parent_list'] = getParentsList($items, 'type_name', 1);
        $this->item_ordering_list = $data['item_ordering_list'] = ($this->item_ID > 0) ? $this->get_Items(['filterArr' => ['db_Select' => ['a.id', 'a.item_ordering', 'a.type_name'], 'db_Joins' => [], 'db_Order' => ['a.item_ordering' => 'asc'], 'db_Filters' => ['where' => ['a.published'=>1, 'a.parent_id' => $this->{$this->model}->data_item->parent_id]]], 'hooks' => ['FormattedResultListOnIndexHierarchical(type_name,. ,item_ordering)']]) : [];

        $this->BuildContentEnv();
        $this->display_type_list = $data['display_type_list'] = [0=>$this->lang->line('display_type_single_select_lbl'), 1=>$this->lang->line('display_type_multiselect_lbl')];
        $this->currency_codes_list = $data['currency_codes_list'] = array_keys($this->config->item('currency_codes_list'));
        $this->currency_codes_list = $data['currency_codes_list'] = array_combine($this->currency_codes_list, $this->currency_codes_list);
        if ($this->doFormValidation()) :
            $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), [ 'type_name'=> '','currency_code'=> '', 'display_type'=> 0, 'item_ordering'=>0,'published' =>1]));
            if ($item_id) :
                $data['success'] = $this->lang->line('success_price_type_save');
                $this->deleteCache($this->tbl . '*');
                if (!$this->item_ID) :             
                    $this->session->set_flashdata('flashSuccess', $data['success']);
                    redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                else:
                    $this->item_ordering_list = $data['item_ordering_list'] = $this->get_Items(['filterArr' => ['db_Select' => ['a.id', 'a.item_ordering', 'a.type_name'], 'db_Joins' => [], 'db_Order' => ['a.item_ordering' => 'asc'], 'db_Filters' => ['where' => ['a.published'=>1, 'a.parent_id' => $this->{$this->model}->data_item->parent_id]]], 'hooks' => ['FormattedResultListOnIndexHierarchical(type_name,. ,item_ordering)']]);    
                endif;
            else : $data['error'] = $this->lang->line('err_price_type_save');
            endif;
        endif;
        $data = $this->_initBasicFormEnv($data);
        $this->displayView($data);
    }

    public function add() {
        $data['error'] = '';
        $data['success'] = '';
        $this->FormProcess($data);
    }

    public function edit($id) {
        $this->item_ID = $this->validate_decrypt_ID($id);
        $data['error'] = '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->FormProcess($data);
    }

    public function delete($id) {
        $this->item_ID = $this->validate_decrypt_ID($id);
        $this->_initEnv();
        $children = $this->get_Items(['filterArr' => ['db_Select' => ['a.id'], 'db_Joins'=>[], 'db_Filters' => ['where' => ['a.parent_id' => $this->item_ID]]]]);
        if (count($children) > 0) :
            $this->session->set_flashdata('flashError', sprintf($this->lang->line('success_price_type_delete'), count($children)));
        else :
            if (!$this->{$this->model}->delete($this->item_ID)) {
                $this->unauthorized_access();
            }
            $this->session->set_flashdata('flashSuccess', $this->lang->line('success_price_type_delete'));
            $this->deleteCache($this->cache_list);
        endif;
        redirect($this->setAppURL($this->list_link));
    }

    protected function AjaxGridRecordsList($items1) {
        $list_link = $this->setAppURL($this->list_link);
        $edit_link_attr = ['class' => 'btn btn-xs tooltips', 'data-placement' => 'top', 'data-original-title' => $this->lang->line('edit_item_lbl')];
        $delete_link_attr = ['class' => 'btn btn-xs text-danger', 'data-toggle' => 'confirmation', 'data-placement' => 'left', 'data-original-title' => $this->lang->line('remove_item_lbl'), 'data-popout' => 'true'];
        $rowdata = [];
        foreach ($items1 as $k => $v) :
            $encypt_id = $this->encodeData($k);
            $action_links = '<div class="actions">';
            $action_links .= anchor(site_url($list_link . '/edit/' . $encypt_id), '<i class="fa fa-edit"></i>', $edit_link_attr);
            $action_links .= nbs();
            $action_links .= anchor(site_url($list_link . '/delete/' . $encypt_id), '<i class="fa fa-trash"></i>', $delete_link_attr);
            $action_links .= '</div>';

            $rowdata[] = [
                $v->type_name,
                $v->currency_code,
                display_status_btn($v->published),
                formatDateTime($v->modified_on, $this->display_date_full_frmt),
                $action_links
            ];
        endforeach;
        return $rowdata;
    }
}
