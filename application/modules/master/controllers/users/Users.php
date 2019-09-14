<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Users extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'users';
    protected $list_link = 'users/users';
    protected $tbl = 'users';
    protected $usergroups_list = [];
    protected $model_name = 'users/user_model';
    protected $model = 'user_model';
    protected $is_model = 0;

    protected $utility_cnfg = [
        'select'=> ['a.id', 'a.first_name', 'a.middle_name', 'a.last_name'],
        'filters'=> ['where'=>['a.active'=>1, 'a.block'=>0]],
        'hooks'=> ['FormattedResultList(first_name.middle_name.last_name, )']
    ];

    protected $acl_btn_list = [
        'remove_btn'=>['mthd'=>'', 'func'=>'user_delete_btn', 'actn'=>'view', 'rdrct'=>0],
        'add_btn'=>['mthd'=>0, 'func'=>'user_add_btn', 'actn'=>'common', 'rdrct'=>0]
    ];
    protected $grid_settings = [
        'order_cols'=> ['a.first_name', 'a.email', 'c.group_id', 'a.block', 'a.active', 'a.last_login', 'a.created_on'],
        'search_cols'=> ['a.first_name', 'a.middle_name', 'a.last_name', 'a.email', 'a.last_login', 'a.created_on'],
        'fixed_filters'=> []
    ];
    protected $user_system_email_flag = 0;

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir . '/users');
        $this->_loadModelEnv();
        $this->load->library('UserAuth');
        $this->userauth->setUserChildGroups($this->master_app);
    }

    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->lang->load($this->parent_dir . '/users');
        $this->BuildContentEnv(['table']);
        $this->minify->add_js($this->th_custom_js_path . $this->parent_dir . '/users.js', 'page');
        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir . '/users';
        $data['view_class'] = 'users';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if ($this->item_ID) :
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_user';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_user';
        endif;
        $this->lang->load($this->parent_dir . '/' . $lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        $this->minify->add_js($this->th_custom_js_path . $this->parent_dir . '/user_form.js', 'page');
        $data['view_name'] = $this->parent_dir . '/user_form';
        $data['view_class'] = 'user_form_blk';
        return $data;
    }

    private function _initBasicFormEnvProfile($data) {
        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $lang = 'profile_user';
        $this->lang->load($this->parent_dir . '/' . $lang);
        $data['home_url'] = $this->app_home_url;
        $data['form_action'] = $this->setAppURL($this->list_link . '/profile/' . $this->encodeData($this->item_ID));
        $data['list_link'] = $this->setAppURL($this->list_link);
        $this->minify->add_js($this->th_custom_js_path . $this->parent_dir . '/profile_user_form.js', 'page');
        $data['view_name'] = $this->parent_dir . '/profile_user_form';
        $data['view_class'] = 'profile_user_form_blk';
        return $data;
    }

    protected function commonUserValidations() {
        $this->form_validation->set_rules('first_name', 'lang:err_user_fname', 'trim|required|alpha_numeric_spaces|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('middle_name', 'lang:err_user_mname', 'trim|alpha_numeric_spaces|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('last_name', 'lang:err_user_lname', 'trim|required|alpha_numeric_spaces|min_length[2]|max_length[50]');
        $is_unique = ($this->item_ID > 0 && strtolower($this->input->post('email')) == strtolower($this->{$this->model}->data_item->email))? '':'|is_unique[users.email]';
        $this->form_validation->set_rules('email', 'lang:err_user_email', 'trim|required|valid_email|min_length[2]|max_length[150]'.$is_unique, ['is_unique'=> $this->lang->line('err_is_unique')]);
        $this->form_validation->set_rules('display_name', 'lang:err_user_display_name', 'trim|required|alpha_numeric_spaces|min_length[5]|max_length[25]');
        if(($this->item_ID == 0) || ($this->item_ID > 0 && $this->input->post('password_hash') !='')) :
            $this->form_validation->set_rules('password_hash', 'lang:err_user_password','trim|required|min_length[5]|max_length[20]');
            $this->form_validation->set_rules('verify_password', 'lang:err_user_vpassword','trim|required|matches[password_hash]');
        endif;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
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
        $this->BuildFormValidationEnv();
        $this->commonUserValidations();
        return $this->form_validation->run();
    }

    protected function FormProcess($data) {
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
        $this->user_system_email_flag = $data['user_system_email_flag'] = $this->authenticate_acl_action(0, 'user_system_email_flag', 'common');
                 
        $this->BuildContentEnv();
        if ($this->doFormValidation()) :
            $post_fields = ['first_name'=>'', 'middle_name'=>'', 'last_name' =>'', 'display_name' =>'', 'email' =>'', 'receive_system_emails' =>0, 'block' =>0, 'active' =>1];
            if(($this->item_ID == 0) || ($this->item_ID > 0 && $this->input->post('password_hash') !='')) : $post_fields['password_hash'] = ''; endif;
            $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), $post_fields), $this->userauth->parent_child_grp);
            if ($item_id) :
                $data['success'] = $this->lang->line('success_user_save');
                $this->deleteCache($this->cache_list);
                if (!$this->item_ID) :
                    $this->sendNewUserNotifications();
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

    public function getAllAdministratorEmails() {
        return $this->get_Items(['filterArr'=>['db_Select'=>['a.id', 'a.email'], 'db_Filters'=>['where'=>['a.active'=>1, 'a.block'=>0, 'a.receive_system_emails'=>1]]], 'hooks'=>['FormattedResultList(email)']]);
    }

    public function sendNewUserNotifications() {
        $this->lang->load($this->parent_dir . '/users');
        $this->BuildContentEnv(['email']);
        $user_email = $this->input->post('email');
        $user_fullname = $this->input->post('first_name');
        $user_fullname .= ($this->input->post('middle_name'))? " ".$this->input->post('middle_name'):'';
        $user_fullname .= " ".$this->input->post('last_name');

        if($this->config->item('new_account_email_notification_to_user')) {
            $email_data = [];
            $subject = email_subject_frmt([$this->lang->line('user_email_subject'), $this->lang->line('site_name')]);
            $email_data['email_header'] = $this->lang->line('user_email_subject');
            $email_data['email_view_name'] = 'auth/registration_notification_user';
            $email_data['user_fullname'] = $user_fullname;
            $email_data['user_email'] = $user_email;
            $this->SendEmail($user_email, $subject, $email_data);
        }

        if($this->config->item('new_account_email_notification_to_admins')) {
            $admin_emails = Modules::run($this->master_app.'/users/users/getAllAdministratorEmails');
            if(count($admin_emails) > 0) {
                $email_data = [];
                $subject = email_subject_frmt([sprintf($this->lang->line('registration_admin_email_subject'), $this->input->post('email')), $this->lang->line('site_name')]);
                $email_data['email_header'] = sprintf($this->lang->line('registration_admin_email_subject'), $this->input->post('email'));
                $email_data['email_view_name'] = 'auth/registration_notification_admin';
                $email_data['user_fullname'] = $user_fullname;
                $email_data['user_email'] = $user_email;
                $this->SendEmail($admin_emails, $subject, $email_data);
            }
        }
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
