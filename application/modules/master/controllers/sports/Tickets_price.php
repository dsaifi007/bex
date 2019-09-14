<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tickets_price extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'sports';
    protected $list_link = 'sports/tickets_price';
    protected $tbl = 'sports_tickets_price';
    
    protected $model_name = 'sports/Ticket_price_model';
    protected $model = 'ticket_price_model';
    protected $is_model = 0;
    protected $stadium_stand_id =[];
    protected $league_id =[];
    protected $match_id =[];
    protected $stadium_id =[];
    protected $type_name =[];
    protected $league_type_id=[];
    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/tickets_price');
        $this->_loadModelEnv();
    }
    
    public function index() {
		
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->_initEnv();
		
        $data['items'] = $this->get_Items();
        if (count($data['items']) > 0) :
		
            $this->BuildContentEnv(['table']);
            $this->currency_code=$data['currency_code']=$this->config->item('currency_code');
			$this->stadium_stand_id=$data['stadium_stand_id'] = Modules::run($this->master_app.'/sports/stadium_stands/utilityStadiumStandsList');
		    $this->stadium_id=$data['stadium_id'] = Modules::run($this->master_app.'/sports/stadiums/utilityStadiumsList');

			$this->league_id=$data['league_id'] = Modules::run($this->master_app.'/sports/leagues/utilityLeaguesList');
            $this->league_type_id=$data['league_type_id'] = Modules::run($this->master_app.'/sports/league_types/utilityLeagueTypeList');
			$this->match_id=$data['match_id'] = Modules::run($this->master_app.'/sports/matches/utilityMatchesList');
            $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/tickets_price.js', 'page');
        endif;

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/tickets_price';
        $data['view_class'] = 'tickets_price';
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
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/ticket_price_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/ticket_price_form';
        $data['view_class'] = 'ticket_price_form_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();

        $this->form_validation->set_rules('currency_code', 'lang:err_currency_code', 'trim|required|in_list[' . implode(',', array_keys($this->currency_code)) . ']', ['in_list' => $this->lang->line('err_in_list')]);

        $this->form_validation->set_rules('match_id', 'lang:err_match', 'trim|required|in_list[' . implode(',', array_keys($this->match_id)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('stadium_stand_id', 'lang:err_stadium_stand', 'trim|required|in_list[' . implode(',', array_keys($this->stadium_stand_id)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('stadium_id', 'lang:err_stadium_name', 'trim|required|in_list[' . implode(',', array_keys($this->stadium_id)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('league_id', 'lang:err_league_name', 'trim|required|in_list[' . implode(',', array_keys($this->league_id)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('league_type_id', 'lang:err_league_type', 'trim|required|in_list[' . implode(',', array_keys($this->league_type_id)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
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
        $this->currency_code=$data['currency_code']=$this->config->item('currency_code');
        $this->stadium_stand_id=$data['stadium_stand_id'] = Modules::run($this->master_app.'/sports/stadium_stands/utilityStadiumStandsList');
        $this->stadium_id=$data['stadium_id'] = Modules::run($this->master_app.'/sports/stadiums/utilityStadiumsList');
        $this->league_id=$data['league_id'] = Modules::run($this->master_app.'/sports/leagues/utilityLeaguesList');
        $this->league_type_id=$data['league_type_id'] = Modules::run($this->master_app.'/sports/league_types/utilityLeagueTypeList');
        $this->match_id=$data['match_id'] = Modules::run($this->master_app.'/sports/matches/utilityMatchesList');
		if ($this->doFormValidation()) :
            $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), ['currency_code'=>'','ticket_pricing'=>'','stadium_stand_id'=>'','stadium_id'=>'','league_id'=>'','league_type_id'=>'','match_id'=>'','published' =>1]));
            if ($item_id) :
                $data['success'] = $this->lang->line('success_ticket_price_save');
                $this->deleteCache($this->tbl . '*');
                if (!$this->item_ID) :             
                    $this->session->set_flashdata('flashSuccess', $data['success']);
                    redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                endif;
            else : $data['error'] = $this->lang->line('err_ticket_price_save');
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
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_ticket_price_delete'));
        $this->deleteCache($this->tbl . '*');
        redirect($this->setAppURL($this->list_link));
    }
	public function utilityTicketsPriceList() {
        return $this->get_Items(['filterArr'=>['db_Select'=>['a.id', 'a.ticket_pricing'], 'db_Filters'=>['where'=>['a.published'=>1]]], 'hooks'=>['FormattedResultList(ticket_pricing)']]);
    } 
}
