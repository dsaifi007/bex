<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Access_actions extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'acl';
    protected $list_link = 'acl/access_actions';
    protected $tbl = 'acl_access_actions';
    
    protected $model_name = 'acl/Access_actions_model';
    protected $model = 'acl_action_model';
    protected $is_model = 0;

    protected $utility_cnfg = [
        'select'=> ['a.id', 'a.acl_action'],
        'filters'=> ['where'=>['a.published'=>1]],
        'hooks'=> ['FormattedResultList(acl_action)']
    ];

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/access_actions');
        $this->_loadModelEnv();
    }
    
    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->_initEnv();

        $data['items'] = $this->get_Items();
        if (count($data['items']) > 0) :
            $this->BuildContentEnv(['table']);
            $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/access_actions.js', 'page');
        endif;

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/access_actions';
        $data['view_class'] = 'access_actions';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if ($this->item_ID) :
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_access_actions';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_access_actions';
        endif;
        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        //$data = $this->BuildAdminEnv($data);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/access_actions_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/access_action_form';
        $data['view_class'] = 'aclaction_form_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $is_unique = ($this->item_ID > 0 && strtolower($this->input->post('acl_action')) == strtolower($this->{$this->model}->data_item->acl_action)) ? '' : '|is_unique[' . $this->tbl . '.acl_action]';
        $this->form_validation->set_rules('acl_action', 'lang:err_acl_action', 'trim|required|alpha|min_length[2]|max_length[50]' . $is_unique);
        $this->form_validation->set_rules('published', 'lang:err_published', 'trim|required|in_list[' . implode(',', array_keys($this->boolean_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        return $this->form_validation->run();
    }

    protected function FormProcess($data) {
        $this->_initEnv();
        $this->{$this->model}->setItem($this->item_ID);
        if ($this->item_ID > 0 && ($this->item_ID != $this->{$this->model}->data_item->id)) :
            $this->unauthorized_access();
        endif;
        $this->BuildContentEnv();
        if ($this->doFormValidation()) :
            $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), ['acl_action' => '', 'published' => '']));
            if ($item_id) :
                $data['success'] = $this->lang->line('success_acl_action_save');
                $this->deleteCache($this->cache_list);
                if (!$this->item_ID) :
                    $this->session->set_flashdata('flashSuccess', $data['success']);
                    redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                endif;
            else : $data['error'] = $this->lang->line('err_acl_action_save');
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
        if (!$this->{$this->model}->delete($this->item_ID)) : $this->unauthorized_access();
        endif;
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_acl_action_delete'));
        $this->deleteCache($this->cache_list);
        redirect($this->setAppURL($this->list_link));
    }

}
