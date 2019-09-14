<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Newsletter_subscribers extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'components';
    protected $list_link = 'components/newsletter_subscribers';
    protected $tbl = 'newsletter_subscribers';
    
    protected $model_name = 'components/Newsletter_subscriber_model';
    protected $model = 'newsletter_subscriber_model';
    protected $is_model = 0;

    protected $grid_settings = [
        'order_cols'=> ['a.subs_email', 'a.published', 'a.modified_on'],
        'search_cols'=> ['a.subs_email','a.modified_on'],
        'fixed_filters'=> []
    ];

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/newsletter_subscribers');
        $this->_loadModelEnv();
    }
    
    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->lang->load($this->parent_dir.'/newsletter_subscribers');
        $this->BuildContentEnv(['table']);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/newsletter_subscribers.js', 'page');
        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/newsletter_subscribers';
        $data['view_class'] = 'newsletter_subscribers';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if ($this->item_ID) :
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_newsletter_subscriber';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_newsletter_subscriber';
        endif;
        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        //$data = $this->BuildAdminEnv($data);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/newsletter_subscribers_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/newsletter_subscriber_form';
        $data['view_class'] = 'newslettersubscriber_form_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $is_unique = ($this->item_ID > 0 && strtolower($this->input->post('subs_email')) == strtolower($this->{$this->model}->data_item->subs_email)) ? '' : '|is_unique[' . $this->tbl . '.subs_email]';
        $this->form_validation->set_rules('subs_email', 'lang:err_subs_email', 'trim|required|valid_email|min_length[5]|max_length[255]'.$is_unique);
        $this->form_validation->set_rules('subs_name', 'lang:err_subs_name', 'trim|min_length[2]|max_length[100]');
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
            $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), ['subs_email' => '', 'subs_name' => '', 'published' =>1]));
            if ($item_id) :
                $data['success'] = $this->lang->line('success_newsletter_subscriber_save');
                $this->deleteCache($this->cache_list);
                if (!$this->item_ID) :
                    $this->SubscriberNotifications($this->input->post('published'));
                    $this->session->set_flashdata('flashSuccess', $data['success']);
                    redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                endif;
            else : $data['error'] = $this->lang->line('err_newsletter_subscriber_save');
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
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_newsletter_subscriber_delete'));
        $this->deleteCache($this->cache_list);
        redirect($this->setAppURL($this->list_link));
    }

    protected function SubscriberNotifications($subscribe=1) {
        $this->BuildContentEnv(['email']);
        $user_fullname = $this->input->post('subs_name');
        $user_email = $this->input->post('subs_email');
        if($subscribe) {
            $email_data = [];
            $subject = email_subject_frmt([$this->lang->line('subscription_user_email_subject'), $this->lang->line('site_name')]);
            $email_data['email_header'] = '';
            $email_data['email_view_name'] = 'utilities/subscription_notification_user';
            $email_data['user_fullname'] = $user_fullname;
            $this->SendEmail($user_email, $subject, $email_data);

            $admin_emails = Modules::run($this->master_app.'/users/users/getAllAdministratorEmails');
            if(count($admin_emails) > 0) {
                $email_data = [];
                $subject = email_subject_frmt([sprintf($this->lang->line('subscription_admin_email_subject'), $this->input->post('subs_email')), $this->lang->line('site_name')]);
                $email_data['email_header'] = '';
                $email_data['email_view_name'] = 'utilities/subscription_notification_admin';
                $email_data['user_fullname'] = $user_fullname;
                $email_data['user_email'] = $user_email;
                $this->SendEmail($admin_emails, $subject, $email_data);
            }
        }

    }

    public function FrontNewsLetterSubscriptionProcess() {
        if (!$this->input->is_ajax_request() && $this->input->method() !='post') { $this->unauthorized_access(); }
        $result = $item_id = 0; $message = $this->lang->line('request_failed');
        $this->_initEnv();
        $this->BuildFormValidationEnv();
        $this->form_validation->set_rules('subs_email', 'lang:err_subs_email', 'trim|required|valid_email|min_length[5]|max_length[255]');
        if ($this->form_validation->run()) {
            build_postvar(['published' => 1, 'subs_name'=>'']);
            $item = $this->get_Items(['filterArr'=>['db_Select'=>['a.id', 'a.published'], 'db_Filters'=>['where'=>['a.subs_email'=>$this->input->post('subs_email')]]], 'hooks'=>['FormattedResultList(published)']]);
            if(count($item) > 0) {
                if(current($item)) {
                    return ['result'=>$result, 'message'=>$this->lang->line('err_newsletter_subscriber_existed')];
                }
                else {
                    $this->{$this->model}->setItem(key($item));
                }
            }
            $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), ['subs_email' => '', 'subs_name' => '', 'published' =>1]));
            if($item_id) {
                $this->deleteCache($this->cache_list);
                $this->current_app = $this->master_app;
                $this->SubscriberNotifications();
                $result = 1; $message = $this->lang->line('success_newsletter_subscriber_enduser');
            }
        }
        else {
            $message = validation_errors();
        }

    return ['result'=>$result, 'message'=>$message];
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
                $v->subs_email,
                display_status_btn($v->published),
                formatDateTime($v->modified_on, $this->display_date_full_frmt),
                $action_links
            ];
        endforeach;
        return $rowdata;
    }
}
