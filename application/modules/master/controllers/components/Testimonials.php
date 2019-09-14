<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Testimonials extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'components';
    protected $list_link = 'components/testimonials';
    protected $tbl = 'testimonials';
    
    protected $model_name = 'components/Testimonial_model';
    protected $model = 'Testimonial_model';
    protected $is_model = 0;

    protected $utility_cnfg = [
        'select'=> ['a.id', 'a.client_name', 'a.content'],
        'filters'=> ['where'=>['a.published'=>1, 'b.published'=>1, 'c.published'=>1]],
        'hooks'=> []
    ];

    protected $parent_list = [];
    protected $item_ordering_list = [];
	protected $testimonial_categories = [];

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/testimonials');
        $this->_loadModelEnv();
    }
    
    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->_initEnv();

        $data['items'] = $this->get_Items();
        if (count($data['items']) > 0) :
            $this->BuildContentEnv(['table']);
			$this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/testimonials.js', 'page');
        endif;

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/testimonials';
        $data['view_class'] = 'testimonials';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if ($this->item_ID) :
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_testimonial';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_testimonial';
        endif;
        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        //$data = $this->BuildAdminEnv($data);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/testimonial_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/testimonial_form';
        $data['view_class'] = 'testimonial_form_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $is_unique = ($this->item_ID > 0 && strtolower($this->input->post('client_name')) == strtolower($this->{$this->model}->data_item->client_name)) ? '' : '|is_unique[' . $this->tbl . '.client_name]';
        $this->form_validation->set_rules('client_name', 'lang:err_testimonial_client_name', 'trim|required|min_length[2]|max_length[100]|alpha_numeric_spaces'.$is_unique);
        $this->form_validation->set_rules('content', 'lang:err_testimonial_content', 'trim|required|min_length[5]|max_length[2000]');
        $this->form_validation->set_rules('category_id', 'lang:err_testimonial_category', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->testimonial_categories)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        if ($this->item_ID > 0 && $this->input->post('parent_id') == $this->{$this->model}->data_item->parent_id) :
            $this->form_validation->set_rules('item_ordering', 'lang:err_testimonial_item_order', 'trim|required|in_list[' . implode(',', array_keys($this->item_ordering_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        endif;
        $this->form_validation->set_rules('published', 'lang:err_published', 'trim|required|in_list[' . implode(',', array_keys($this->boolean_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        return $this->form_validation->run();
    }

    protected function FormProcess($data) {
        $this->_initEnv();
        $this->{$this->model}->setItem($this->item_ID);
        if ($this->item_ID > 0 && ($this->item_ID != $this->{$this->model}->data_item->id)) :
            $this->unauthorized_access();
        endif;
        build_postvar(['item_ordering' => 0]);

        $items = $this->get_Items(['hooks' => ['treeOrder']]);
        $this->parent_list = $data['parent_list'] = getParentsList($items, 'client_name', 1);
        ($this->item_ID) ? $this->{$this->model}->setVal('managed_all_items', $items) : '';
        $this->item_ordering_list = $data['item_ordering_list'] = ($this->item_ID > 0) ? $this->get_Items(['filterArr' => ['db_Select' => ['a.id', 'a.item_ordering', 'a.client_name'], 'db_Joins' => [], 'db_Order' => ['a.item_ordering' => 'asc'], 'db_Filters' => ['where' => ['a.category_id'=>$this->{$this->model}->data_item->category_id, 'a.parent_id' => $this->{$this->model}->data_item->parent_id]]], 'hooks' => ['FormattedResultListOnIndexHierarchical(client_name,. ,item_ordering)']]) : [];
        $this->testimonial_categories = $data['testimonial_categories'] = Modules::run($this->master_app.'/components/testimonial_categories/utilityList');

        $this->BuildContentEnv(['form', 'form_elements', 'editor']);
        if ($this->doFormValidation()) :
            if($this->item_ID > 0 && ($this->item_ID == $this->input->post('parent_id'))) :
                $data['error'] = $this->lang->line('err_testimonial_self_parent');
            else :
            $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), ['client_name' => '', 'content' => '', 'parent_id'=>0, 'item_ordering'=>0, 'category_id'=>0, 'published' =>1]));
            if ($item_id) :
                $data['success'] = $this->lang->line('success_testimonial_save');
                $this->deleteCache($this->cache_list);
                if (!$this->item_ID) :
                    $this->session->set_flashdata('flashSuccess', $data['success']);
                    redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                else :
                    $this->{$this->model}->setItem($this->item_ID);
                    $this->item_ordering_list = $data['item_ordering_list'] = $this->get_Items(['filterArr' => ['db_Select' => ['a.id', 'a.item_ordering', 'a.client_name'], 'db_Joins' => [], 'db_Order' => ['a.item_ordering' => 'asc'], 'db_Filters' => ['where' => ['a.category_id'=>$this->{$this->model}->data_item->category_id, 'a.parent_id' => $this->{$this->model}->data_item->parent_id]]], 'hooks' => ['FormattedResultListOnIndexHierarchical(client_name,. ,item_ordering)']]);
                endif;
            else : $data['error'] = $this->lang->line('err_testimonial_save');
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
        $data['error'] = '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->FormProcess($data);
    }

    public function delete($id) {
        $this->item_ID = $this->validate_decrypt_ID($id);
        $this->_initEnv();
        $children = $this->get_Items(['filterArr' => ['db_Select' => ['a.id'], 'db_Joins'=>[], 'db_Filters' => ['where' => ['a.parent_id' => $this->item_ID]]]]);
        if (count($children) > 0) :
            $this->session->set_flashdata('flashError', sprintf($this->lang->line('error_testimonial_delete'), count($children)));
        else :
        if (!$this->{$this->model}->delete($this->item_ID)) : $this->unauthorized_access();
        endif;
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_testimonial_delete'));
        $this->deleteCache($this->cache_list);
        endif;
        redirect($this->setAppURL($this->list_link));
    }

}
