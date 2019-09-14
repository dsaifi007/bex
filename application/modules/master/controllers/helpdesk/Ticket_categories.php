<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ticket_categories extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'helpdesk';
    protected $list_link = 'helpdesk/ticket_categories';
    protected $tbl = 'helpdesk_ticket_categories';
    protected $model_name = 'helpdesk/Ticket_category_model';
    protected $model = 'ticket_category_model';
    protected $is_model = 0;
    protected $parent_list = [];
    protected $item_ordering_list = [];

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->loadLanguage($this->parent_dir . '/ticket_categories');
        $this->_loadModelEnv();
        $this->load->helper('string');
    }

    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->_initEnv();

        $data['items'] = $this->get_Items(['hooks' => ['treeOrder']]);
        if (count($data['items']) > 0) :
            $this->BuildContentEnv(['table']);
            $this->minify->add_js($this->th_custom_js_path . $this->parent_dir . '/ticket_categories.js', 'page');
        endif;

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir . '/ticket_categories';
        $data['view_class'] = 'ticket_categories';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if ($this->item_ID) :
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_ticket_category';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_ticket_category';
        endif;
        $this->loadLanguage($this->parent_dir . '/' . $lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        //$data = $this->BuildAdminEnv($data);
        $this->minify->add_js($this->th_custom_js_path . $this->parent_dir . '/ticket_category_form.js', 'page');
        $data['view_name'] = $this->parent_dir . '/ticket_category_form';
        $data['view_class'] = 'ticket_category_form_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $this->form_validation->set_rules('category_name', 'lang:err_category_name', 'trim|required|min_length[2]|max_length[150]');
        $this->form_validation->set_rules('parent_id', 'lang:err_category_parent', 'trim|required|in_list[' . implode(',', array_keys($this->parent_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $multi_unique_param = [
            'id' => $this->item_ID,
            'fields' => ['category_name', 'parent_id'],
            'table' => $this->tbl
        ];
        $this->form_validation->set_rules('category_name', 'lang:err_category_name', 'multi_is_unique[' . json_encode($multi_unique_param) . ']');
        $this->form_validation->set_rules('category_description', 'lang:err_category_description', 'trim|min_length[2]');
        if ($this->item_ID > 0 && $this->input->post('parent_id') == $this->{$this->model}->data_item->parent_id) :
            $this->form_validation->set_rules('item_ordering', 'lang:err_category_item_order', 'trim|required|in_list[' . implode(',', array_keys($this->item_ordering_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
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
        $this->parent_list = $data['parent_list'] = getParentsList($items, 'category_name', 1);
        ($this->item_ID) ? $this->{$this->model}->setVal('managed_all_items', $items) : '';
        $this->item_ordering_list = $data['item_ordering_list'] = ($this->item_ID > 0) ? $this->get_Items(['filterArr' => ['db_Select' => ['a.id', 'a.item_ordering', 'a.category_name'], 'db_Order' => ['a.item_ordering' => 'asc'], 'db_Filters' => ['where' => ['a.parent_id' => $this->{$this->model}->data_item->parent_id]]], 'hooks' => ['FormattedResultListOnIndex(item_ordering.category_name,. ,item_ordering)']]) : [];

        $this->BuildContentEnv(['form', 'form_elements', 'editor']);
        if ($this->doFormValidation()) :
            if($this->item_ID > 0 && ($this->item_ID == $this->input->post('parent_id'))) :
                $data['error'] = $this->lang->line('err_category_self_parent');
            else :
                $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), [
                    'category_name' => '',
                    'category_description' => '',
                    'parent_id' => 0,
                    'item_ordering' => 0,
                    'published' => 1
                ]));
                if ($item_id) :
                    $data['success'] = $this->lang->line('success_category_save');
                    $this->deleteCache($this->tbl . '*');
                    if (!$this->item_ID) :
                        $this->session->set_flashdata('flashSuccess', $data['success']);
                        redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                    else :
                        $this->{$this->model}->setItem($this->item_ID);
                        $this->item_ordering_list = $data['item_ordering_list'] = $this->get_Items(['filterArr' => ['db_Select' => ['a.id', 'a.item_ordering', 'a.category_name'], 'db_Joins' => [], 'db_Order' => ['a.item_ordering' => 'asc'], 'db_Filters' => ['where' => ['a.parent_id' => $this->{$this->model}->data_item->parent_id]]], 'hooks' => ['FormattedResultListOnIndex(item_ordering.category_name,. ,item_ordering)']]);
                    endif;
                else : $data['error'] = $this->lang->line('err_category_save');
                endif;
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
        $children = $this->get_Items(['filterArr' => ['db_Select' => ['a.id'], 'db_Filters' => ['where' => ['a.parent_id' => $this->item_ID]]]]);
        if (count($children) > 0) :
            $this->session->set_flashdata('flashError', sprintf($this->lang->line('error_category_delete'), count($children)));
        else :
            if (!$this->{$this->model}->delete($this->item_ID)) : $this->unauthorized_access();
            endif;
            $this->session->set_flashdata('flashSuccess', $this->lang->line('success_category_delete'));
            $this->deleteCache($this->tbl . '*');
        endif;
        redirect($this->setAppURL($this->list_link));
    }

}
