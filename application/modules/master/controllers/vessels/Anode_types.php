<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Anode_types extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'vessels';
    protected $list_link = 'vessels/anode_types';
    protected $tbl = 'vessels_anode_types';
    
    protected $model_name = 'vessels/Anode_type_model';
    protected $model = 'anode_type_model';
    protected $is_model = 0;

    protected $utility_cnfg = [
        'select'=> ['a.id', 'a.anode_type_name'],
        'filters'=> ['where'=>['a.published'=>1]],
        'hooks'=> ['FormattedResultList(anode_type_name)']
    ];

    protected $currency_codes_list = [];

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/anode_types');
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
            $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/anode_types.js', 'page');
        endif;

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/anode_types';
        $data['view_class'] = 'anode_types';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if ($this->item_ID) :
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_anode_type';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_anode_type';
        endif;
        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/anode_type_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/anode_type_form';
        $data['view_class'] = 'anode_type_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $is_unique = ($this->item_ID > 0 && strtolower($this->input->post('anode_type_name')) == strtolower($this->{$this->model}->data_item->anode_type_name)) ? '' : '|is_unique[' . $this->tbl . '.anode_type_name]';
        $this->form_validation->set_rules('anode_type_name', 'lang:err_anode_type_name', 'trim|required|min_length[2]|max_length[50]'.$is_unique);
        $this->form_validation->set_rules('currency_code', 'lang:err_pricing_currency_code', 'trim|required|in_list[' . implode(',', array_keys($this->currency_codes_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
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
        $this->currency_codes_list = $data['currency_codes_list'] = array_keys($this->config->item('currency_codes_list'));
        $this->currency_codes_list = $data['currency_codes_list'] = array_combine($this->currency_codes_list, $this->currency_codes_list);
        if ($this->doFormValidation()) :
            $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), [ 'anode_type_name'=> '','currency_code'=> '','published' =>1]));
            if ($item_id) :
                $data['success'] = $this->lang->line('success_anode_type_save');
                $this->deleteCache($this->tbl . '*');
                if (!$this->item_ID) :             
                    $this->session->set_flashdata('flashSuccess', $data['success']);
                    redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                endif;
            else : $data['error'] = $this->lang->line('err_anode_type_save');
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
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_anode_type_delete'));
        $this->deleteCache($this->tbl . '*');
        redirect($this->setAppURL($this->list_link));
    }

}
