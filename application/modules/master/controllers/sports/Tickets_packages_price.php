<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tickets_packages_price extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'sports';
    protected $list_link = 'sports/tickets_packages_price';
    protected $tbl = 'sports_tickets_packages_pricing';
    
    protected $model_name = 'sports/Ticket_package_price_model';
    protected $model = 'ticket_package_price_model';
    protected $is_model = 0;

    protected $utility_cnfg = [
        'select'=> ['a.id', 'j.package_name', 'CONCAT(a.currency_code, " ", a.package_pricing) as package_pricing', 'CONCAT(f.team_name, " - Vs - ", g.team_name) as match_team', 'c.match_timing', 'd.stadium_name', 'd.country_code', 'h.league_name', 'i.type_name as league_type'],
        'hooks'=> ['FormattedResultList(package_pricing.package_name.match_team.match_timing.stadium_name.country_code.league_name.league_type)']
    ];
    protected $acl_btn_list = [
        'remove_btn'=>['mthd'=>'', 'func'=>'tickets_packages_price_delete_btn', 'actn'=>'view', 'rdrct'=>0]
    ];
    protected $matches_list = [];
    protected $ticket_packages_list =[];
    protected $currency_codes_list = [];

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/tickets_packages_price');
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
			$this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/ticket_packages_price.js', 'page');
        endif;

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/ticket_packages_price';
        $data['view_class'] = 'ticket_packages_price';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if ($this->item_ID) :
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_ticket_package_price';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_ticket_package_price';
        endif;
        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        //$data = $this->BuildAdminEnv($data);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/ticket_package_price_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/ticket_package_price_form';
        $data['view_class'] = 'ticket_package_price_form_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $this->form_validation->set_rules('match_id', 'lang:err_ticket_package_price_match', 'trim|required|in_list[' . implode(',', array_keys($this->matches_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $multi_unique_param = [
            'id' => $this->item_ID,
            'fields' => ['match_id', 'package_id'],
            'table' => $this->tbl
        ];
        $this->form_validation->set_rules('package_id', 'lang:err_ticket_package_price_package_name', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->ticket_packages_list)) . ']|multi_is_unique['.json_encode($multi_unique_param) . ']');
        $this->form_validation->set_rules('currency_code', 'lang:err_ticket_package_price_currency_code', 'trim|required|in_list[' . implode(',', array_keys($this->currency_codes_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('package_stock', 'lang:err_ticket_package_price_package_stock', 'trim|required|min_length[1]|max_length[5]|numeric|greater_than[0]|less_than[50000]');
        $this->form_validation->set_rules('package_qty', 'lang:err_ticket_package_price_package_qty', 'trim|required|min_length[1]|max_length[2]|numeric|greater_than[0]|less_than[100]');
        $this->form_validation->set_rules('package_pricing', 'lang:err_ticket_package_price', 'trim|required|min_length[1]|max_length[8]|decimal|greater_than_equal_to[1]');
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
        $this->matches_list = $data['matches_list'] = Modules::run($this->master_app.'/sports/matches/utilityList');
        $this->ticket_packages_list = $data['ticket_packages_list'] = Modules::run($this->master_app.'/sports/tickets_packages/utilityList');

        if ($this->doFormValidation()) :
            $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), ['package_id'=>'', 'package_qty'=>0, 'package_stock'=>0, 'currency_code'=>'', 'package_pricing'=>0, 'match_id'=>0, 'published' =>1]));
            if ($item_id) :
                $data['success'] = $this->lang->line('success_ticket_package_price_save');
                $this->deleteCache($this->cache_list);
                if (!$this->item_ID) :             
                    $this->session->set_flashdata('flashSuccess', $data['success']);
                    redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                endif;
            else : $data['error'] = $this->lang->line('err_ticket_package_price_save');
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
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_ticket_package_price_delete'));
        $this->deleteCache($this->cache_list);
        redirect($this->setAppURL($this->list_link));
    }

	public function utilityList($cnfg1=[]) {
        $curr_date = formatDateTime('', 'Y-m-d H:i:s||GMT');
        //DATE_FORMAT(CONVERT_TZ(c.match_timing, "+00:00", "'.$this->config->item('app_timezone_diff_from_GMT').'"), "%b %d %Y %h:%i %p")
        $this->utility_cnfg['filters'] = ['where'=>['a.published'=>1,
                'c.match_timing >=' => $curr_date,
                'h.ends_on >='=> $curr_date,
                'c.published'=>1,
                'h.published'=>1,
                'd.published'=>1,
                'f.published'=>1,
                'g.published'=>1,
                'i.published'=>1,
                'j.published'=>1
                ]
        ];
        return parent::utilityList($cnfg1);
    }
}
