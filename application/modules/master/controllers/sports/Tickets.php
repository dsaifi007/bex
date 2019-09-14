<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tickets extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'sports';
    protected $list_link = 'sports/tickets';
    protected $tbl = 'sports_tickets';
    
    protected $model_name = 'sports/Ticket_model';
    protected $model = 'ticket_model';
    protected $is_model = 0;

    protected $ticket_pricing_list =[];
    protected $currency_codes_list = [];
    protected $stadium_stands_list = [];

    protected $acl_btn_list = [
        'remove_btn'=>['mthd'=>'', 'func'=>'tickets_delete_btn', 'actn'=>'view', 'rdrct'=>0]
    ];

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/tickets');
        $this->_loadModelEnv();
    }
    
    public function index() {
		
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->_initEnv();
		
        $data['items'] = $this->get_Items();
        if (count($data['items']) > 0) :
            $this->BuildContentEnv(['table']);
            $data['currency_codes_list'] = $this->config->item('currency_codes_list');
            $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/tickets.js', 'page');
        endif;

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/tickets';
        $data['view_class'] = 'tickets';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if ($this->item_ID) :
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_ticket';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_ticket';
        endif;
        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        //$data = $this->BuildAdminEnv($data);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/ticket_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/ticket_form';
        $data['view_class'] = 'ticket_form_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $is_unique = ($this->item_ID > 0 && strtolower($this->input->post('ticket_number')) == strtolower($this->{$this->model}->data_item->ticket_number)) ? '' : '|is_unique[' . $this->tbl . '.ticket_number]';
        $this->form_validation->set_rules('ticket_number', 'lang:err_ticket_number', 'trim|required|min_length[5]|max_length[30]'.$is_unique);
        $this->form_validation->set_rules('stadium_stand_id', 'lang:err_ticket_stadium_stand', 'trim|required|in_list[' . implode(',', array_keys($this->stadium_stands_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('ticket_package_price_id','lang:err_ticket_package', 'trim|required|in_list[' . implode(',', array_keys($this->ticket_pricing_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('ticket_availability', 'lang:err_ticket_availability', 'trim|required|in_list[' . implode(',', array_keys($this->boolean_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
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
        $this->stadium_stands_list = $data['stadium_stands_list'] = Modules::run($this->master_app.'/sports/stadium_stands/utilityList');
        $this->ticket_pricing_list = $data['ticket_pricing_list'] = Modules::run($this->master_app.'/sports/tickets_packages_price/utilityList');
		if ($this->doFormValidation()) :
            $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), ['ticket_number'=>'', 'ticket_package_price_id'=>0, 'stadium_stand_id'=>0, 'ticket_availability'=>1,'published' =>1]));
            if ($item_id) :
                $data['success'] = $this->lang->line('success_ticket_save');
                $this->deleteCache($this->cache_list);
                if (!$this->item_ID) :             
                    $this->session->set_flashdata('flashSuccess', $data['success']);
                    redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                endif;
            else : $data['error'] = $this->lang->line('err_ticket_save');
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
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_ticket_delete'));
        $this->deleteCache($this->cache_list);
        redirect($this->setAppURL($this->list_link));
    }

}
