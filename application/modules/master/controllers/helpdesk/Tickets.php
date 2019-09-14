<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tickets extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'helpdesk';
    protected $list_link = 'helpdesk/tickets';
    protected $tbl = 'helpdesk_tickets';
    
    protected $model_name = 'helpdesk/Ticket_model';
    protected $model = 'ticket_model';
    protected $is_model = 0;
    protected $categories_list = [];
    protected $status = [];
    protected $ticket_priorities = [];
    protected $product_list = [];

    protected $domain_list = [];

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
            $data['domain_list'] = Modules::run($this->master_app.'/settings/domains/utilityDomainsList');
			$this->categories_list = $data['categories_list'] = Modules::run($this->master_app.'/shop/Categories/utilityCategoriesList');
			$this->status = $data['status'] = Modules::run($this->master_app.'/helpdesk/Ticket_status/utilityTicketstatusList');
			$this->ticket_priorities = $data['ticket_priorities'] = Modules::run($this->master_app.'/helpdesk/Ticket_priorities/utilityPrioritiesList');
			$this->product_list = $data['product_list'] = Modules::run($this->master_app.'/shop/Products/utilityProductsList');
			//print_r($this->priorities_list);
			//die();
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
        $this->form_validation->set_rules('ticket_subject', 'lang:err_ticket_subject', 'trim|required|min_length[2]|max_length[255]');
        $this->form_validation->set_rules('status_id', 'lang:err_ticket_status', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->status)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('category_id', 'lang:err_ticket_category', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->categories_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('priority_id', 'lang:err_ticket_priority', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->ticket_priorities)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('product_id', 'lang:err_ticket_product', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->product_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
		$this->form_validation->set_rules('published', 'lang:err_published', 'trim|required|in_list[' . implode(',', array_keys($this->boolean_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        return $this->form_validation->run();
    }

    protected function FormProcess($data) {
        $this->_initEnv();
        $this->{$this->model}->setItem($this->item_ID);
        if ($this->item_ID > 0 && ($this->item_ID != $this->{$this->model}->data_item->id)) :
            $this->unauthorized_access();
        endif;
        $this->domain_list = $data['domain_list'] = Modules::run($this->master_app.'/settings/domains/utilityDomainsList');
        $this->categories_list = $data['categories_list'] = Modules::run($this->master_app.'/shop/Categories/utilityCategoriesList');
		$this->status = $data['status'] = Modules::run($this->master_app.'/helpdesk/Ticket_status/utilityTicketstatusList');            	
		$this->ticket_priorities = $data['ticket_priorities'] = Modules::run($this->master_app.'/helpdesk/Ticket_priorities/utilityPrioritiesList');            	
		$this->product_list = $data['product_list'] = Modules::run($this->master_app.'/shop/Products/utilityProductsList');

		$this->BuildContentEnv();

        if ($this->doFormValidation()) :
            $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), ['ticket_subject'=> '','status_id'=>'','category_id'=>'','priority_id'=>'', 'product_id'=>'', 'published' => 1]));
		   if ($item_id) :
                $data['success'] = $this->lang->line('success_ticket_save');
                $this->deleteCache($this->tbl . '*');
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
        $this->deleteCache($this->tbl . '*');
        redirect($this->setAppURL($this->list_link));
    }
    
    public function utilityTicketsList() {
        return $this->get_Items(['filterArr'=>['db_Select'=>['a.id', 'a.ticket'], 'db_Filters'=>['where'=>['a.published'=>1]]], 'hooks'=>['FormattedResultList(ticket)']]);
    }

}
