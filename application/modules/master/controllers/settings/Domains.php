<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Domains extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'settings';
    protected $list_link = 'settings/domains';
    protected $tbl = 'domains';
    protected $usergroups_list = [];

    protected $model_name = 'settings/Domain_model';
    protected $model = 'domain_model';
    protected $is_model = 0;

    protected $utility_cnfg = [
        'select'=> ['a.id', 'a.title'],
        'filters'=> ['where'=>['a.published'=>1]],
        'hooks'=> ['FormattedResultList(title)']
    ];

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/domains');
        $this->_loadModelEnv();
    }

    public function index()
    {
        $data['error'] = ($this->session->flashdata('flashError'))? $this->session->flashdata('flashError'):'';
        $data['success'] = ($this->session->flashdata('flashSuccess'))? $this->session->flashdata('flashSuccess'):'';
        $this->_initEnv();

        $data['items'] = $this->get_Items();
        if(count($data['items']) > 0) :
            $data['usergroup_items'] = Modules::run($this->master_app.'/users/usergroups/utilityList', 1);
            $this->BuildContentEnv(['table']);
            $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/domains.js', 'page');
        endif;

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/domains';
        $data['view_class'] = 'domains';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if($this->item_ID) :
            $data['form_action'] = $this->list_link.'/edit/'.$this->encodeData($this->item_ID);
            $lang = 'edit_domain';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link.'/add';
            $lang = 'add_domain';
        endif;
        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        //$data = $this->BuildAdminEnv($data);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/domain_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/domain_form';
        $data['view_class'] = 'domain_form_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $this->form_validation->set_rules('title', 'lang:err_domain_title','trim|required|alpha_numeric_spaces|min_length[2]|max_length[50]');
        $is_unique = ($this->item_ID > 0 && strtolower($this->input->post('slug')) == strtolower($this->{$this->model}->data_item->slug))? '':'|is_unique['.$this->tbl.'.slug]';
        $this->form_validation->set_rules('slug', 'lang:err_domain_slug','trim|required|alpha_dash|min_length[2]|max_length[25]'.$is_unique);
        $this->form_validation->set_rules('url', 'lang:err_domain_url','trim|min_length[2]|max_length[255]|valid_url');
        $this->form_validation->set_rules('user_groups[]', 'lang:err_domain_usergroups','trim|required|in_list['.implode(',',array_keys($this->usergroups_list)).']', ['in_list'=>$this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('is_down', 'lang:err_domain_is_down','trim|required|in_list['.implode(',',array_keys($this->boolean_arr)).']', ['in_list'=>$this->lang->line('err_in_list')]);
        $is_message_req = ($this->input->post('is_down') == 1)? '|required':'';
        $this->form_validation->set_rules('down_message', 'lang:err_domain_down_message','trim'.$is_message_req.'|min_length[2]|max_length[500]', ['required'=>$this->lang->line('err_domain_down_message_required')]);
        $this->form_validation->set_rules('display_notice', 'lang:err_domain_display_notice','trim|required|in_list['.implode(',',array_keys($this->boolean_arr)).']', ['in_list'=>$this->lang->line('err_in_list')]);
        $is_notice_req = ($this->input->post('display_notice') == 1)? '|required':'';
        $this->form_validation->set_rules('notice_message', 'lang:err_domain_notice_message','trim'.$is_notice_req.'|min_length[2]|max_length[500]', ['required'=>$this->lang->line('err_domain_notice_message_required')]);
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

        $grparr = Modules::run($this->master_app.'/users/usergroups/utilityList');
        $this->usergroups_list = $data['usergroups_list'] = $grparr;
        $this->BuildContentEnv();
        if($this->doFormValidation()) :
            $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), ['title'=>'', 'slug'=>'', 'url'=>'', 'is_down'=>0, 'down_message'=>'', 'display_notice'=>0, 'notice_message'=>'', 'published'=>'']));
            if($item_id) :
                $data['success'] = $this->lang->line('success_domain_save');
                $this->deleteCache($this->cache_list);
                if(!$this->item_ID) :
                    $this->session->set_flashdata('flashSuccess', $data['success']);
                    redirect($this->setAppURL($this->list_link.'/edit/'.$this->encodeData($item_id)));
                else :
                    $this->{$this->model}->setItem($this->item_ID);
                endif;
            else : $data['error'] = $this->lang->line('err_domain_save'); endif;
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
        //if($this->item_ID != 5) :
        $this->_initEnv();
        if(!$this->{$this->model}->delete($this->item_ID)) : $this->unauthorized_access(); endif;
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_doamin_delete'));
        $this->deleteCache($this->cache_list);
        //endif;
        redirect($this->setAppURL($this->list_link));
    }

}
