<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Manufacturers extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'shop';
    protected $list_link = 'shop/manufacturers';
    protected $tbl = 'shop_manufacturers';
    
    protected $model_name = 'shop/Manufacturer_model';
    protected $model = 'shop_manufacturer_model';
    protected $is_model = 0;

    protected $stores_list = [];

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/manufacturers');
        $this->_loadModelEnv();
    }
    
    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->_initEnv();
		
        $data['items'] = $this->get_Items();
        if (count($data['items']) > 0) {
            $this->BuildContentEnv(['table']);
            //$data['stores_list'] = Modules::run($this->master_app.'/shop/stores/utilityStoreList');
            $this->minify->add_js($this->th_custom_js_path . $this->parent_dir . '/manufacturers.js', 'page');
        }

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/Manufacturers';
        $data['view_class'] = 'manufacturers';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if ($this->item_ID) :
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_manufacturer';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_manufacturer';
        endif;
        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        //$data = $this->BuildAdminEnv($data);
 $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/manufacturer_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/Manufacturer_form';
        $data['view_class'] = 'manufacturer_form_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $is_unique = ($this->item_ID > 0 && $this->input->post('manufacturer') == $this->{$this->model}->data_item->manufacturer) ? '' : '|is_unique[' . $this->tbl . '.manufacturer]';
        $this->form_validation->set_rules('manufacturer', 'lang:err_shop_manufacturer', 'trim|required|min_length[2]|max_length[100]'.$is_unique);
        $this->form_validation->set_rules('stores[]', 'lang:err_shop_manufacturer_stores','trim|required|in_list['.implode(',',array_keys($this->stores_list)).']', ['in_list'=>$this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('published', 'lang:err_published', 'trim|required|in_list[' . implode(',', array_keys($this->boolean_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        return $this->form_validation->run();
    }

    protected function FormProcess($data) {
        $this->_initEnv();
        $this->{$this->model}->setItem($this->item_ID);
        if ($this->item_ID > 0 && ($this->item_ID != $this->{$this->model}->data_item->id)) :
            $this->unauthorized_access();
        endif;
        $this->stores_list = $data['stores_list'] = Modules::run($this->master_app.'/shop/stores/utilityStoreList');
        $this->BuildContentEnv();
        if ($this->doFormValidation()) :
            $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), ['manufacturer' => '', 'published' => 1]));
            if ($item_id) :
                $data['success'] = $this->lang->line('success_shop_manufacturer_save');
                $this->deleteCache($this->tbl . '*');
                if (!$this->item_ID) :
                    $this->session->set_flashdata('flashSuccess', $data['success']);
                    redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                endif;
            else : $data['error'] = $this->lang->line('err_shop_manufacturer_save');
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
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_shop_manufacturer_delete'));
        $this->deleteCache($this->tbl . '*');
        redirect($this->setAppURL($this->list_link));
    }
    
    public function utilityManufacturersList() {
        return $this->get_Items(['filterArr'=>['db_Select'=>['a.id', 'a.manufacturer'], 'db_Filters'=>['where'=>['a.published'=>1]]], 'hooks'=>['FormattedResultList(manufacturer)']]);
    }

}
