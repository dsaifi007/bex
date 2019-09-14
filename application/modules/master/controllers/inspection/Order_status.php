<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Order_status extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'inspection';
    protected $list_link = 'inspection/order_status';
    protected $tbl = 'vessels_inspection_order_status';
    
    protected $model_name = 'inspection/Order_status_model';
    protected $model = 'order_status_model';
    protected $is_model = 0;

    protected $utility_cnfg = [
        'select'=> ['a.id', 'a.order_status'],
        'filters'=> ['where'=>['a.published'=>1]],
        'hooks'=> ['FormattedResultList(order_status)']
    ];
    
    protected $item_ordering_list = [];
    protected $parent_list = [];

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/order_status');
        $this->_loadModelEnv();
    }
    
    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->_initEnv();

        $data['items'] = $this->get_Items();
        if (count($data['items']) > 0) :
            $this->BuildContentEnv(['table']);
            $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/order_status.js', 'page');
        endif;

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/order_status';
        $data['view_class'] = 'order_status';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if ($this->item_ID) :
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_order_status';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_order_status';
        endif;
        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/order_status_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/order_status_form';
        $data['view_class'] = 'order_status_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $is_unique = ($this->item_ID > 0 && strtolower($this->input->post('order_status')) == strtolower($this->{$this->model}->data_item->order_status)) ? '' : '|is_unique[' . $this->tbl . '.order_status]';
        $this->form_validation->set_rules('order_status', 'lang:err_order_status', 'trim|required|min_length[2]|max_length[50]'.$is_unique);
        if ($this->item_ID > 0  && $this->input->post('parent_id') == $this->{$this->model}->data_item->parent_id) :
            $this->form_validation->set_rules('item_ordering', 'lang:err_order_status_item_ordering', 'trim|required|in_list[' . implode(',', array_keys($this->item_ordering_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
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
        $this->parent_list = $data['parent_list'] = getParentsList($items, 'order_status', 1);
        $this->item_ordering_list = $data['item_ordering_list'] = ($this->item_ID > 0) ? $this->get_Items(['filterArr' => ['db_Select' => ['a.id', 'a.item_ordering', 'a.order_status'], 'db_Joins' => [], 'db_Order' => ['a.item_ordering' => 'asc'], 'db_Filters' => ['where' => ['a.published'=>1, 'a.parent_id' => $this->{$this->model}->data_item->parent_id]]], 'hooks' => ['FormattedResultListOnIndexHierarchical(order_status,. ,item_ordering)']]) : [];

        $this->BuildContentEnv();
        if ($this->doFormValidation()) :
            $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), [ 'order_status'=> '','parent_id'=>0, 'item_ordering'=>0, 'published' =>1]));
            if ($item_id) :
                $data['success'] = $this->lang->line('success_order_status_save');
                $this->deleteCache($this->tbl . '*');
                if (!$this->item_ID) :             
                    $this->session->set_flashdata('flashSuccess', $data['success']);
                    redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                else:
                    $this->item_ordering_list = $data['item_ordering_list'] = $this->get_Items(['filterArr' => ['db_Select' => ['a.id', 'a.item_ordering', 'a.order_status'], 'db_Joins' => [], 'db_Order' => ['a.item_ordering' => 'asc'], 'db_Filters' => ['where' => ['a.published'=>1, 'a.parent_id' => $this->{$this->model}->data_item->parent_id]]], 'hooks' => ['FormattedResultListOnIndexHierarchical(order_status,. ,item_ordering)']]);        
                endif;
            else : $data['error'] = $this->lang->line('err_order_status_save');
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
            $this->session->set_flashdata('flashError', sprintf($this->lang->line('success_order_status_delete'), count($children)));
        else :
            if (!$this->{$this->model}->delete($this->item_ID)) {
                $this->unauthorized_access();
            }
            $this->session->set_flashdata('flashSuccess', $this->lang->line('success_order_status_delete'));
            $this->deleteCache($this->cache_list);
        endif;
        redirect($this->setAppURL($this->list_link));
    }
}
