<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_types extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'settings';
    protected $list_link = 'settings/menu_types';
    protected $tbl = 'menu_types';

    protected $domains_list = [];

    protected $model_name = 'settings/Menu_type_model';
    protected $model = 'menu_type_model';
    protected $is_model = 0;

    protected $utility_cnfg = [
        'select'=> ['a.id', 'a.title', 'b.title as domain_title'],
        'filters'=> ['where'=>['a.published'=>1, 'b.published'=>1]],
        'hooks'=> ['FormattedResultList(title.domain_title)']
    ];

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/menu_types');
        $this->_loadModelEnv();
    }

    public function index()
    {
        $data['error'] = ($this->session->flashdata('flashError'))? $this->session->flashdata('flashError'):'';
        $data['success'] = ($this->session->flashdata('flashSuccess'))? $this->session->flashdata('flashSuccess'):'';
        $this->_initEnv();

        $data['items'] = $this->get_Items();
        if(count($data['items']) > 0) :
            $this->BuildContentEnv(['table']);
            $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/menu_types.js', 'page');
        endif;

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/menu_types';
        $data['view_class'] = 'menu_types';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if($this->item_ID) :
            $data['form_action'] = $this->list_link.'/edit/'.$this->encodeData($this->item_ID);
            $lang = 'edit_menu_type';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link.'/add';
            $lang = 'add_menu_type';
        endif;

        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        //$data = $this->BuildAdminEnv($data);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/menu_type_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/menu_type_form';
        $data['view_class'] = 'menu_type_form_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $this->form_validation->set_rules('title', 'lang:err_menu_type_title', 'trim|required|min_length[2]|max_length[100]');
        $this->form_validation->set_rules('menu_type_slug', 'lang:err_err_menu_type_slug', 'trim|required|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('domain_id', 'lang:err_menu_type_domain','trim|required|in_list['.implode(',',array_keys($this->domains_list)).']', ['in_list'=>$this->lang->line('err_in_list')]);

        $multi_unique_param = [
            'id'=>$this->item_ID,
            'fields'=> ['menu_type_slug', 'domain_id'],
            'table'=>$this->tbl
        ];
        $this->form_validation->set_rules('menu_type_slug', 'lang:err_menu_type_slug', 'multi_is_unique['.json_encode($multi_unique_param).']');

        $this->form_validation->set_rules('description', 'lang:err_content_description', 'trim|required|min_length[2]|max_length[255]');
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

        $this->domains_list = $data['domains_list'] = Modules::run($this->master_app.'/settings/domains/utilityList');

        $this->BuildContentEnv();
        if($this->doFormValidation()) :
            $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), ['title'=>'', 'menu_type_slug'=>'', 'domain_id'=>0, 'description'=>'', 'published'=>1]));
            if($item_id) :
                $data['success'] = $this->lang->line('success_menu_type_save');
                $this->deleteCache($this->cache_list);
                if(!$this->item_ID) :
                    $this->session->set_flashdata('flashSuccess', $data['success']);
                    redirect($this->setAppURL($this->list_link.'/edit/'.$this->encodeData($item_id)));
                else :
                    $this->{$this->model}->setItem($this->item_ID);
                endif;
            else : $data['error'] = $this->lang->line('err_menu_type_save'); endif;
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
        $menu_items = Modules::run($this->master_app.'/settings/menu_items/get_Items', ['filterArr'=>['db_Select'=>['a.id'], 'db_Filters'=>['where'=>['a.menu_type_id'=>$this->item_ID]]]]);
        if(count($menu_items) > 0) :
            $this->session->set_flashdata('flashError', sprintf($this->lang->line('error_menu_type_delete'), count($menu_items)));
        else :
            if(!$this->{$this->model}->delete($this->item_ID)) : $this->unauthorized_access(); endif;
            $this->session->set_flashdata('flashSuccess', $this->lang->line('success_menu_type_delete'));
            $this->deleteCache($this->cache_list);
        endif;
        redirect($this->setAppURL($this->list_link));
    }

}
