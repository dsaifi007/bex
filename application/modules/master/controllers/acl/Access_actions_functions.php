<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Access_actions_functions extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'acl';
    protected $list_link = 'acl/access_actions_functions';
    protected $tbl = 'acl_access_actions_functions_map';

    protected $model_name = 'acl/Access_action_function_model';
    protected $model = 'acl_access_action_function_model';
    protected $is_model = 0;

    protected $grid_settings = [
        'order_cols'=> ['a.function_name', 'a.controller', 'c.acl_action', 'd.acl_level', 'e.title', 'a.published', 'a.modified_on'],
        'search_cols'=> ['a.function_name', 'a.controller', 'a.method', 'c.acl_action', 'd.acl_level', 'e.title', 'a.modified_on'],
        'fixed_filters' => []
    ];

    protected $usergroups_list = [];
    protected $acl_actions_list = [];
    protected $acl_levels_list = [];
    protected $domains_list = [];

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/access_actions_functions');
        $this->_loadModelEnv();
    }

    public function index()
    {
        $data['error'] = ($this->session->flashdata('flashError'))? $this->session->flashdata('flashError'):'';
        $data['success'] = ($this->session->flashdata('flashSuccess'))? $this->session->flashdata('flashSuccess'):'';
        $this->lang->load($this->parent_dir.'/access_actions_functions');
        $this->BuildContentEnv(['table']);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/access_actions_functions.js', 'page');
        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/access_actions_functions';
        $data['view_class'] = 'access_actions_functions';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if($this->item_ID) :
            $data['form_action'] = $this->list_link.'/edit/'.$this->encodeData($this->item_ID);
            $lang = 'edit_access_action_function';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link.'/add';
            $lang = 'add_access_action_function';
        endif;
        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        //$data = $this->BuildAdminEnv($data);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/access_action_function_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/access_action_function_form';
        $data['view_class'] = 'acl_access_action_function_form_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        build_postvar(['user_groups'=>[]]);
        $this->form_validation->set_rules('function_name', 'lang:err_acl_function_name_level', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('controller', 'lang:err_controller', 'trim|required|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('method', 'lang:err_method', 'trim|min_length[2]|max_length[50]');

        $this->form_validation->set_rules('action_id', 'lang:err_acl_action','trim|required|in_list['.implode(',',array_keys($this->acl_actions_list)).']', ['in_list'=>$this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('acl_level_id', 'lang:err_acl_level','trim|required|in_list['.implode(',',array_keys($this->acl_levels_list)).']', ['in_list'=>$this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('domain_id', 'lang:err_domain','trim|required|in_list['.implode(',',array_keys($this->domains_list)).']', ['in_list'=>$this->lang->line('err_in_list')]);

        $multi_unique_param = [
            'id'=>$this->item_ID,
            'fields'=> ['controller', 'method', 'function_name', 'action_id', 'domain_id'],
            'table'=>$this->tbl
        ];
        $this->form_validation->set_rules('function_name', 'lang:err_acl_function_name_level', 'multi_is_unique['.json_encode($multi_unique_param).']');

        $this->form_validation->set_rules('user_groups[]', 'lang:err_usergroups','trim|in_list['.implode(',',array_keys($this->usergroups_list)).']', ['in_list'=>$this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('published', 'lang:err_published','trim|required|in_list['.implode(',',array_keys($this->boolean_arr)).']', ['in_list'=>$this->lang->line('err_in_list')]);
        return $this->form_validation->run();
    }

    protected function FormProcess($data) {
        $this->_initEnv();
        $this->load->helper('string');
        $this->{$this->model}->setItem($this->item_ID);
        if($this->item_ID > 0 && ($this->item_ID != $this->{$this->model}->data_item->id)) :
            $this->unauthorized_access();
        endif;

        $this->usergroups_list = $data['usergroups_list'] = Modules::run($this->master_app.'/users/usergroups/utilityList');
        $this->acl_actions_list = $data['acl_actions_list'] = Modules::run($this->master_app.'/acl/access_actions/utilityList');
        $this->acl_levels_list = $data['acl_levels_list'] = Modules::run($this->master_app.'/acl/access_levels/utilityList');
        $this->domains_list = $data['domains_list'] = Modules::run($this->master_app.'/settings/domains/utilityList');

        $this->BuildContentEnv();
        if($this->doFormValidation()) :
            $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), ['function_name'=>'', 'controller'=>'', 'method'=>'', 'action_id'=>0, 'acl_level_id'=>0, 'domain_id'=>0, 'user_groups'=>[], 'published'=>1]));
            if($item_id) :
                $data['success'] = $this->lang->line('success_acl_access_action_function_save');
                $this->deleteCache($this->cache_list);
                if(!$this->item_ID) :
                    $this->session->set_flashdata('flashSuccess', $data['success']);
                    redirect($this->setAppURL($this->list_link.'/edit/'.$this->encodeData($item_id)));
                else :
                    $this->{$this->model}->setItem($this->item_ID);
                endif;
            else : $data['error'] = $this->lang->line('err_acl_access_action_function_save'); endif;
        endif;
        $data = $this->_initBasicFormEnv($data);
        $this->displayView($data);
    }

    public function add() {
        $data['error'] = ''; $data['success'] = '';
        $this->FormProcess($data);
    }

    public function edit($id) {
        $this->item_ID = $this->validate_decrypt_ID($id);
        $data['error'] = ''; $data['success'] = ($this->session->flashdata('flashSuccess'))? $this->session->flashdata('flashSuccess'):'';
        $this->FormProcess($data);
    }

    public function delete($id) {
        $this->item_ID = $this->validate_decrypt_ID($id);
        $this->_initEnv();
        if(!$this->{$this->model}->delete($this->item_ID)) : $this->unauthorized_access(); endif;
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_acl_access_action_function_delete'));
        $this->deleteCache($this->cache_list);
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
                $v->function_name,
                $v->controller.' / '.$v->method,
                $v->acl_action,
                $v->acl_level,
                $v->domain_title,
                display_status_btn($v->published),
                formatDateTime($v->modified_on, $this->display_date_full_frmt),
                $action_links
            ];
        endforeach;
        return $rowdata;
    }

}
