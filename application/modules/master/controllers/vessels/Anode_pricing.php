<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Anode_pricing extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'vessels';
    protected $list_link = 'vessels/anode_pricing';
    protected $tbl = 'vessels_anode_pricing';
    
    protected $model_name = 'vessels/Anode_pricing_model';
    protected $model = 'anode_pricing_model';
    protected $is_model = 0;

    protected $anode_type_list =[];
    protected $currency_codes_list = [];

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/anode_pricing');
        $this->_loadModelEnv();
    }
    
    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->_initEnv();

        $data['items'] = $this->get_Items();
        if (count($data['items']) > 0) :
            $this->BuildContentEnv(['table']);
            $data['currency_codes_list']=$this->config->item('currency_codes_list');
            $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/anode_pricing.js', 'page');
        endif;

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/anode_pricing';
        $data['view_class'] = 'anode_pricing';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if ($this->item_ID) :
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_anode_pricing';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_anode_pricing';
        endif;
        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/anode_pricing_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/anode_pricing_form';
        $data['view_class'] = 'anode_pricing_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $this->form_validation->set_rules('anode_name', 'lang:err_anode_name', 'trim|required|min_length[2]|max_length[50]');
        $multi_unique_param = [
            'id'=>$this->item_ID,
            'fields'=> ['anode_name', 'anode_type_id'],
            'table'=>$this->tbl
        ];
        $this->form_validation->set_rules('anode_name', 'lang:err_anode_name', 'multi_is_unique['.json_encode($multi_unique_param).']');
        $this->form_validation->set_rules('anode_type_id', 'lang:err_anode_type', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->anode_type_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
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
        $this->anode_type_list = $data['anode_type_list'] = Modules::run($this->master_app.'/vessels/anode_types/utilityList');
        if ($this->doFormValidation()) :
            $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), [ 'anode_type_id'=> '', 'anode_name' => '', 'anode_price'=> '', 'published' =>1]));
            if ($item_id) :
                $data['success'] = $this->lang->line('success_anode_pricing_save');
                $this->deleteCache($this->tbl . '*');
                if (!$this->item_ID) :             
                    $this->session->set_flashdata('flashSuccess', $data['success']);
                    redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                endif;
            else : $data['error'] = $this->lang->line('err_anode_pricing_save');
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
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_anode_pricing_delete'));
        $this->deleteCache($this->tbl . '*');
        redirect($this->setAppURL($this->list_link));
    }
}
