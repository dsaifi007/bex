<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usergroups extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'users';
    protected $list_link = 'users/usergroups';
    protected $tbl = 'users_groups';
    protected $usergroup_parents_list = [];

    protected $model_name = 'users/usergroup_model';
    protected $model = 'usergroup_model';
    protected $is_model = 0;

    protected $utility_cnfg = [
        'select'=> ['a.id', 'a.parent_id', 'a.title'],
        'filters'=> ['where'=>['a.published'=>1]]
    ];

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/usergroups');
        $this->_loadModelEnv();
        $this->load->helper('string');
    }

    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->_initEnv();

        $data['items'] = $this->get_Items(['hooks'=>['treeOrder']]);
        if (count($data['items']) > 0) :
            $this->BuildContentEnv(['table']);
            $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/usergroups.js', 'page');
        endif;

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/usergroups';
        $data['view_class'] = 'usergroups';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['parent_list'] = $this->usergroup_parents_list;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if ($this->item_ID) :
            $data['form_action'] = $this->list_link.'/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_usergroup';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link.'/add';
            $lang = 'add_usergroup';
        endif;
        $this->lang->load($this->parent_dir.'/' . $lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        //$data = $this->BuildAdminEnv($data);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/usergroup_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/usergroup_form';
        $data['view_class'] = 'usergroup_form_blk';

        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $is_unique = ($this->item_ID > 0 && strtolower($this->input->post('title')) == strtolower($this->{$this->model}->data_item->title)) ? '' : '|is_unique['.$this->tbl.'.title]';
        $this->form_validation->set_rules('title', 'lang:err_usergroup_title', 'trim|required|alpha_numeric_spaces|min_length[2]|max_length[100]' . $is_unique);
        $this->form_validation->set_rules('parent_id', 'lang:err_usergroup_parent_list', 'trim|required|in_list[' . implode(',', array_keys($this->usergroup_parents_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('published', 'lang:err_published', 'trim|required|in_list[' . implode(',', array_keys($this->boolean_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        return $this->form_validation->run();
    }

    protected function FormProcess($data) {
        $this->_initEnv();
        $this->{$this->model}->setItem($this->item_ID);
        if ($this->item_ID > 0 && ($this->item_ID != $this->{$this->model}->data_item->id)) :
            $this->unauthorized_access();
        endif;

        $items = $this->get_Items(['hooks'=>['treeOrder']]);
        $this->usergroup_parents_list = getParentsList($items, 'title', 1);
        ($this->item_ID)? $this->{$this->model}->setVal('managed_all_items', $items):'';

        $this->BuildContentEnv();
        if ($this->doFormValidation()) :
            if($this->item_ID > 0 && ($this->item_ID == $this->input->post('parent_id'))) :
                $data['error'] = $this->lang->line('err_usergroup_self_parent');
            else :
                $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), ['parent_id'=>'', 'title' => '', 'published' => '']));
                if ($item_id) :
                    $data['success'] = $this->lang->line('success_usergroup_save');
                    $this->deleteCache($this->cache_list);
                    if (!$this->item_ID) :
                        $this->session->set_flashdata('flashSuccess', $data['success']);
                        redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                    endif;
                else : $data['error'] = $this->lang->line('err_usergroup_save');
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
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->FormProcess($data);
    }

    public function delete($id) {
        $this->item_ID = $this->validate_decrypt_ID($id);
        $this->_initEnv();
        $items = $this->get_Items(['hooks'=>['treeOrder']]);
        $children = get_all_children($items, $this->item_ID);
        if(count($children) > 0) :
            $this->session->set_flashdata('flashError', sprintf($this->lang->line('error_usergroup_delete'), count($children)));
        else :
            if (!$this->{$this->model}->delete($this->item_ID)) : $this->unauthorized_access(); endif;
            $this->session->set_flashdata('flashSuccess', $this->lang->line('success_usergroup_delete'));
            $this->deleteCache($this->cache_list);
        endif;
        redirect($this->setAppURL($this->list_link));
    }

    public function utilityList($noParentList=0, $cnfg1=[]) {
        $hooks = ['treeOrder'];
        if(!$noParentList) :
            $this->load->helper('string');
            array_push($hooks, 'getParentsList(title)');
        endif;
        $this->utility_cnfg['hooks'] = $hooks;
        return parent::utilityList($cnfg1);
    }

}
