<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Category extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'products';
    protected $list_link = 'products/category';
    protected $tbl = 'bex_skin_category';
    
    protected $model_name = 'products/Category_model';
    protected $model = 'category_model';
    protected $is_model = 0;
    protected $category_type_list =[];

    protected $utility_cnfg = [
        'select'=> ['a.id', 'a.backend_name'],
        'filters'=> ['where'=>['a.published'=>1]],
        'hooks'=> ['FormattedResultList(backend_name)']
    ];

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/category');
        $this->_loadModelEnv();
    }
    
    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->_initEnv();

        $data['items'] = $this->get_Items();
        if (count($data['items']) > 0) :
            $this->BuildContentEnv(['table']);
            $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/category.js', 'page');
        endif;

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/category';
        $data['view_class'] = 'category';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {
        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if ($this->item_ID) :
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_category';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_category';
        endif;
        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/category_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/category_form';
        $data['view_class'] = 'category_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $this->form_validation->set_rules('frontend_name', 'lang:err_frontend_name', 'trim|required|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('backend_name', 'lang:err_backend_name', 'trim|required|min_length[2]|max_length[50]');
        $multi_unique_param = [
            'id'=>$this->item_ID,
            'fields'=> ['frontend_name', 'category_type_id'],
            'table'=>$this->tbl
        ];
        $this->form_validation->set_rules('frontend_name', 'lang:err_frontend_name', 'multi_is_unique['.json_encode($multi_unique_param).']');
         $multi_unique_param_1 = [
            'id'=>$this->item_ID,
            'fields'=> ['backend_name', 'category_type_id'],
            'table'=>$this->tbl
        ];
        $this->form_validation->set_rules('backend_name', 'lang:err_backend_name', 'multi_is_unique['.json_encode($multi_unique_param_1).']');
        $this->form_validation->set_rules('category_type_id', 'lang:err_category_type', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->category_type_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
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
        $this->category_type_list = $data['category_type_list'] = Modules::run($this->master_app.'/products/category_type/utilityList');
        if ($this->doFormValidation()) :
            $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), [ 'frontend_name'=> '', 'backend_name'=> '', 'tips'=>'', 'category_type_id'=> '','published' =>1]));
            if ($item_id) :
                $data['success'] = $this->lang->line('success_category_save');
                $this->deleteCache($this->tbl . '*');
                if (!$this->item_ID) :             
                    $this->session->set_flashdata('flashSuccess', $data['success']);
                    redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                endif;
            else : $data['error'] = $this->lang->line('err_category_save');
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
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_category_delete'));
        $this->deleteCache($this->tbl . '*');
        redirect($this->setAppURL($this->list_link));
    }
}
