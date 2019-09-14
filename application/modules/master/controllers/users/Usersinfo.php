<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Usersinfo extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'users';
    protected $list_link = 'users/usersinfo';
    protected $tbl = 'users';
    protected $usergroups_list = [];
    protected $model_name = 'users/userinfo_model';
    protected $model = 'userinfo_model';
    protected $is_model = 0;
    protected $acl_btn_list = [
        'remove_btn'=>['mthd'=>'', 'func'=>'userinfo_delete_btn', 'actn'=>'view', 'rdrct'=>0],
        'add_btn'=>['mthd'=>0, 'func'=>'userinfo_add_btn', 'actn'=>'common', 'rdrct'=>0]
    ];
    protected $grid_settings = [
        'order_cols'=> ['a.first_name', 'a.email', 'c.group_id', 'a.block', 'a.active', 'a.last_login', 'a.created_on'],
        'search_cols'=> ['a.first_name', 'a.middle_name', 'a.last_name', 'a.email', 'a.last_login', 'a.created_on'],
        'fixed_filters'=> []
    ];

    protected $utility_cnfg = [
        'select'=> ['a.id', 'd.address', 'd.address1','d.address2', 'd.city', 'd.state', 'd.state_code', 'd.zipcode', 'd.country_code'],
        'filters'=> ['where'=>['a.active'=>1, 'a.block'=>0]],
        'hooks' => []
    ];


    protected $user_system_email_flag = 0;

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir . '/usersinfo');
        $this->_loadModelEnv();
        $this->load->library('UserAuth');
        $this->userauth->setUserChildGroups($this->master_app);
    }

    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->lang->load($this->parent_dir . '/usersinfo');
        $this->BuildContentEnv(['table']);
        $this->minify->add_js($this->th_custom_js_path . $this->parent_dir . '/usersinfo.js', 'page');
        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir . '/usersinfo';
        $data['view_class'] = 'users';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {
        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if ($this->item_ID) :
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_userinfo';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_userinfo';
        endif;
        $this->lang->load($this->parent_dir . '/' . $lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        $this->minify->add_js($this->th_custom_js_path . $this->parent_dir . '/userinfo_form.js', 'page');
        $data['view_name'] = $this->parent_dir . '/userinfo_form';
        $data['view_class'] = 'userinfo_form_blk';
        return $data;
    }

    private function _initBasicFormEnvProfile($data) {
        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $lang = 'profile_userinfo';
        $data['home_url'] = $this->app_home_url;
        $this->lang->load($this->parent_dir . '/' . $lang);
        $data['form_action'] = $this->setAppURL($this->list_link . '/profile/' . $this->encodeData($this->item_ID));
        $data['list_link'] = $this->setAppURL($this->list_link);
        $this->minify->add_js($this->th_custom_js_path . $this->parent_dir . '/profile_userinfo_form.js', 'page');
        $data['view_name'] = $this->parent_dir . '/profile_userinfo_form';
        $data['view_class'] = 'profile_userinfo_form_blk';
        return $data;
    }

    protected function commonUserValidations() {
        $this->BuildFormValidationEnv();
        $this->form_validation->set_rules('first_name', 'lang:err_user_fname', 'trim|required|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('middle_name', 'lang:err_user_mname', 'trim|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('last_name', 'lang:err_user_lname', 'trim|required|min_length[2]|max_length[50]');
        $is_unique = ($this->item_ID > 0 && strtolower($this->input->post('email')) == strtolower($this->{$this->model}->data_item->email))? '':'|is_unique[users.email]';
        $this->form_validation->set_rules('email', 'lang:err_user_email', 'trim|required|valid_email|min_length[2]|max_length[150]'.$is_unique, ['is_unique'=> $this->lang->line('err_is_unique')]);
        $this->form_validation->set_rules('display_name', 'lang:err_user_display_name', 'trim|required|min_length[2]|max_length[25]');

        $this->form_validation->set_rules('address', 'lang:err_user_address', 'trim|required|min_length[5]|max_length[255]');
        $this->form_validation->set_rules('address1', 'lang:err_user_address1', 'trim|min_length[5]|max_length[255]');
        $this->form_validation->set_rules('address2', 'lang:err_user_address2', 'trim|min_length[5]|max_length[255]');
        $this->form_validation->set_rules('zipcode', 'lang:err_user_zipcode', 'trim|required|alpha_numeric_spaces|min_length[5]|max_length[10]');
        $this->form_validation->set_rules('city', 'lang:err_user_city', 'trim|required|alpha_numeric_spaces|min_length[3]|max_length[50]');
        $this->form_validation->set_rules('state', 'lang:err_user_state', 'trim|required|alpha_numeric_spaces|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('state_code', 'lang:err_user_state_code', 'trim|required|alpha|min_length[1]|max_length[10]');
        $this->form_validation->set_rules('country_code', 'lang:err_user_country_code', 'trim|required|alpha|min_length[1]|max_length[10]');
        $this->form_validation->set_rules('phone', 'lang:err_user_phone', 'trim|required|numeric|min_length[6]|max_length[20]');

        if(($this->item_ID == 0) || ($this->item_ID > 0 && $this->input->post('password_hash') !='')) :
            $this->form_validation->set_rules('password_hash', 'lang:err_user_password','trim|required|min_length[5]|max_length[20]');
            $this->form_validation->set_rules('verify_password', 'lang:err_user_vpassword','trim|required|matches[password_hash]');
        endif;
    }

    protected function doFormValidation() {
        $this->commonUserValidations();
        $this->form_validation->set_rules('user_groups[]', 'lang:err_user_usergroups', 'trim|required|in_list[' . implode(',', array_keys($this->usergroups_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        if($this->user_system_email_flag) : 
            $this->form_validation->set_rules('receive_system_emails', 'lang:err_user_receive_emails', 'trim|required|in_list[' . implode(',', array_keys($this->boolean_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        else : destroy_postvar(['receive_system_emails']); endif;
        $this->form_validation->set_rules('block', 'lang:err_user_block', 'trim|required|in_list[' . implode(',', array_keys($this->boolean_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('active', 'lang:err_user_active', 'trim|required|in_list[' . implode(',', array_keys($this->boolean_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        return $this->form_validation->run();
    }

    protected function doFormValidationProfile() {
        $this->commonUserValidations();
        return $this->form_validation->run();
    }

    protected function FormProcess($data) {
        $this->lang->load('vessels/vessels');
        $this->_initEnv();
        $this->load->helper('string');
        $this->{$this->model}->setItem($this->item_ID);
        if($this->item_ID > 0 ) : 
            if ($this->item_ID != $this->{$this->model}->data_item->id) :
                $this->unauthorized_access();
            elseif(count(array_intersect($this->userauth->parent_child_grp, $this->{$this->model}->data_item->user_groups)) < 1) : 
                $this->unauthorized_access();
            endif;
        endif;

        $this->usergroups_list = $data['usergroups_list'] = $this->userauth->userGroupsBasedOnParent(Modules::run($this->master_app.'/users/usergroups/utilityList'));
        $this->user_system_email_flag = $data['userinfo_system_email_flag'] = $this->authenticate_acl_action(0, 'userinfo_system_email_flag', 'common');
            
        $data['vessels_list_link'] = $this->setAppURL('vessels/vessels');
        $this->vessels_items = $data['vessels_items'] = Modules::run($this->master_app.'/vessels/vessels/utilityList', ['select'=>['a.id', 'a.vessel_name', 'b.style_name','c.manufacturer_name', 'd.drive_type_name', 'a.published'], 'filters'=> ['where'=>['a.user_id'=>$this->item_ID]], 'hooks'=> []]);     
             
        $this->BuildContentEnv(['form', 'form_elements','table']);
        if ($this->doFormValidation()) :
            $post_fields = ['first_name'=>'', 'middle_name'=>'', 'last_name' =>'', 'display_name' =>'', 'email' =>'', 'receive_system_emails' =>0, 'block' =>0, 'active' =>1];
            if(($this->item_ID == 0) || ($this->item_ID > 0 && $this->input->post('password_hash') !='')) : $post_fields['password_hash'] = ''; endif;
            $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), $post_fields), $this->userauth->parent_child_grp);
            if ($item_id) :
                $data['success'] = $this->lang->line('success_user_save');
                $this->deleteCache($this->cache_list);
                if (!$this->item_ID) :
                    $this->session->set_flashdata('flashSuccess', $data['success']);
                    redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                else :
                    $this->{$this->model}->setItem($this->item_ID);
                endif;
            else : $data['error'] = $this->lang->line('err_user_save');
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

    public function profile($id) {
        $this->item_ID = $this->validate_decrypt_ID($id);
        $data['error'] = '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';

        $this->_initEnv();
        $this->{$this->model}->setItem($this->item_ID);
        if($this->item_ID > 0 ) :
            if ($this->item_ID != $this->{$this->model}->data_item->id) :
                $this->unauthorized_access();
            elseif($this->item_ID != $this->userdata->id) :
                $this->unauthorized_access();
            endif;
        endif;
        $this->BuildContentEnv();
        if ($this->doFormValidationProfile()) :
            $post_fields = ['first_name'=>'', 'middle_name'=>'', 'last_name' =>'', 'display_name' =>'', 'email' =>''];
            if($this->item_ID > 0 && $this->input->post('password_hash') !='') : $post_fields['password_hash'] = ''; endif;
            $item_id = $this->{$this->model}->saveProfile(arrange_post_data($this->input->post(), $post_fields));
            if ($item_id) :
                $data['success'] = $this->lang->line('success_user_save');
                $this->deleteCache($this->cache_list);
                $this->{$this->model}->setItem($this->item_ID);
            else : $data['error'] = $this->lang->line('err_user_save');
            endif;
        endif;
        $data = $this->_initBasicFormEnvProfile($data);
        $this->displayView($data);
    }

    public function delete($id) {
        $this->item_ID = $this->validate_decrypt_ID($id);
        $this->_initEnv();
        if (!$this->{$this->model}->delete($this->item_ID)) : $this->unauthorized_access();
        endif;
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_user_delete'));
        $this->deleteCache($this->cache_list);
        redirect($this->setAppURL($this->list_link));
    }

    public function dataAjaxGrid() {
        $this->_initEnv();
        $this->grid_settings['fixed_filters'] = (count($this->userauth->parent_child_grp) > 0)? ['where_in'=>['c.group_id'=>$this->userauth->parent_child_grp]]:$this->grid_settings['fixed_filters'];
        parent::dataAjaxGrid();
    }

    protected function AjaxGridRecordsList($items1) {
        $list_link = $this->setAppURL($this->list_link);
        $edit_link_attr = ['class' => 'btn btn-xs tooltips', 'data-placement' => 'top', 'data-original-title' => $this->lang->line('edit_item_lbl')];
        $delete_link_attr = ['class' => 'btn btn-xs text-danger', 'data-toggle' => 'confirmation', 'data-placement' => 'left', 'data-original-title' => $this->lang->line('remove_item_lbl'), 'data-popout' => 'true'];
        $rowdata = [];
        $usergroup_items = Modules::run($this->master_app.'/users/usergroups/utilityList', 1);
        $boolean_arr = $this->boolean_arr;
        extract($this->setACL_Btns());
        foreach ($items1 as $k => $v) :
            $name = $v->first_name; $name .= ($v->middle_name)? nbs().$v->middle_name:''; $name .= nbs().$v->last_name;
            $encypt_id = encodeData($k);

            $groups = (is_array($v->user_groups))? $v->user_groups:[];
            array_walk($groups, function(&$n, $k, $mixed) {
                $n = (isset($mixed[$n]))? $mixed[$n]->title:'';
            }, $usergroup_items);

            $action_links = '<div class="actions">';
            $action_links .= anchor(site_url($list_link . '/edit/' . $encypt_id), '<i class="fa fa-edit"></i>', $edit_link_attr);
            $action_links .= nbs();
            $action_links .= ($remove_btn)? anchor(site_url($list_link . '/delete/' . $encypt_id), '<i class="fa fa-trash"></i>', $delete_link_attr):'';
            $action_links .= '</div>';

            $rowdata[] = [
                $name,
                $v->email,
                display_label_list(array_filter($groups)),
                display_status_btn($v->block, $boolean_arr),
                display_status_btn($v->active),
                formatDateTime($v->last_login, $this->display_date_full_frmt),
                formatDateTime($v->created_on, $this->display_date_full_frmt),
                $action_links
            ];
        endforeach;
        return $rowdata;
    }

}
