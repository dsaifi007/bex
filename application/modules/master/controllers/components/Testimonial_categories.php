<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Testimonial_categories extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'components';
    protected $list_link = 'components/testimonial_categories';
    protected $tbl = 'testimonials_categories';
    
    protected $model_name = 'components/testimonial_category_model';
    protected $model = 'Testimonial_category_model';
    protected $is_model = 0;

    protected $utility_cnfg = [
        'select'=> ['a.id', 'a.category_name'],
        'filters'=> ['where'=>['a.published'=>1, 'b.published'=>1]],
        'hooks'=> ['FormattedResultList(category_name)']
    ];

    protected $domains_list = [];

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/testimonial_categories');
        $this->_loadModelEnv();
    }
    
    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->_initEnv();

        $data['items'] = $this->get_Items();
        if (count($data['items']) > 0) :
            $this->BuildContentEnv(['table']);
            $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/testimonial_categories.js', 'page');
        endif;

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/testimonial_categories';
        $data['view_class'] = 'testimonial_categories';
        $this->displayView($data);
		
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if ($this->item_ID) :
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_testimonial_category';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_testimonial_category';
        endif;
        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        //$data = $this->BuildAdminEnv($data);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/testimonial_category_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/testimonial_category_form';
        $data['view_class'] = 'testimonial_category_form_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $is_unique = ($this->item_ID > 0 && strtolower($this->input->post('category_name')) == strtolower($this->{$this->model}->data_item->category_name)) ? '' : '|is_unique[' . $this->tbl . '.category_name]';
        $this->form_validation->set_rules('category_name', 'lang:err_testimonial_category', 'trim|required|max_length[100]|min_length[3]'.$is_unique);
        $this->form_validation->set_rules('domain_id', 'lang:err_testimonial_category_domain','trim|required|in_list['.implode(',',array_keys($this->domains_list)).']', ['in_list'=>$this->lang->line('err_in_list')]);
        $multi_unique_param = [
            'id'=>$this->item_ID,
            'fields'=> ['category_name', 'domain_id'],
            'table'=>$this->tbl
        ];
        $this->form_validation->set_rules('category_name', 'lang:err_testimonial_category', 'multi_is_unique['.json_encode($multi_unique_param).']');
        $this->form_validation->set_rules('published', 'lang:err_published', 'trim|required|in_list[' . implode(',', array_keys($this->boolean_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        return $this->form_validation->run();
    }

    protected function FormProcess($data) {
        $this->_initEnv();
        $this->{$this->model}->setItem($this->item_ID);
        if ($this->item_ID > 0 && ($this->item_ID != $this->{$this->model}->data_item->id)) :
            $this->unauthorized_access();
        endif;
        $this->domains_list = $data['domains_list'] = Modules::run($this->master_app.'/settings/domains/utilityList');
        $this->BuildContentEnv();
        if ($this->doFormValidation()) :
            $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), ['category_name' => '', 'domain_id'=>0, 'published' =>1]));
            if ($item_id) :
                $data['success'] = $this->lang->line('success_testimonial_category_save');
                $this->deleteCache($this->cache_list);
                if (!$this->item_ID) :
                    
                    $this->session->set_flashdata('flashSuccess', $data['success']);
                    redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                endif;
            else : $data['error'] = $this->lang->line('err_testimonial_category_save');
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
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_testimonial_category_delete'));
        $this->deleteCache($this->cache_list);
        redirect($this->setAppURL($this->list_link));
    }
}
