<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Leagues extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'sports';
    protected $list_link = 'sports/leagues';
    protected $tbl = 'sports_leagues';

    protected $model_name = 'sports/League_model';
    protected $model = 'league_model';
    protected $is_model = 0;

    protected $league_types_list = [];
    protected $countries_list = [];
    protected $teams_list = [];
    protected $domains_list = [];

    protected $utility_cnfg = [
        'select'=> ['a.id', 'a.league_name, c.type_name'],
        'hooks'=> ['FormattedResultList(league_name.type_name)']
    ];

    protected $acl_btn_list = [
        'remove_btn'=>['mthd'=>'', 'func'=>'leagues_delete_btn', 'actn'=>'view', 'rdrct'=>0]
    ];

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/leagues');
        $this->_loadModelEnv();
    }

    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->_initEnv();

        $data['items'] = $this->get_Items();
        if (count($data['items']) > 0) :
            $this->BuildContentEnv(['table']);
            $data['countries_list'] = $this->config->item('countries_list');
            $data['teams_list'] = Modules::run($this->master_app.'/sports/teams/utilityList');
            $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/leagues.js', 'page');
        endif;

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/leagues';
        $data['view_class'] = 'leagues';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if ($this->item_ID) :
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_league';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_league';
        endif;
        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        //$data = $this->BuildAdminEnv($data);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/league_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/league_form';
        $data['view_class'] = 'league_form_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $multi_unique_param = [
            'id' => $this->item_ID,
            'fields' => ['league_name', 'league_type_id'],
            'table' => $this->tbl
        ];
        $this->form_validation->set_rules('league_name', 'lang:err_league_name', 'trim|required|min_length[2]|max_length[100]|multi_is_unique[' . json_encode($multi_unique_param) . ']');
        $this->form_validation->set_rules('league_type_id', 'lang:err_league_league_type_id', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->league_types_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('country_codes[]', 'lang:err_team_country', 'trim|required|in_list[' . implode(',', array_keys($this->countries_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('teams[]', 'lang:err_league_teams', 'trim|required|in_list[' . implode(',', array_keys($this->teams_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('starts_on', 'lang:err_league_starts_on', 'trim|required|validate_date[m/d/Y H:i]');
        $this->form_validation->set_rules('ends_on', 'lang:err_league_ends_on', 'trim|required|validate_date[m/d/Y H:i]');
        $this->form_validation->set_rules('domain_id', 'lang:err_league_domain', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->domains_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('published', 'lang:err_published', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->boolean_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        return $this->form_validation->run();
    }

    protected function FormProcess($data) {
        $this->_initEnv();
        $this->{$this->model}->setItem($this->item_ID);
        if ($this->item_ID > 0 && ($this->item_ID != $this->{$this->model}->data_item->id)) :
            $this->unauthorized_access();
        endif;

        $this->countries_list = $data['countries_list'] = $this->config->item('countries_list');
        $this->league_types_list = $data['league_types_list'] = Modules::run($this->master_app.'/sports/league_types/utilityList');
        $this->teams_list = $data['teams_list'] = Modules::run($this->master_app.'/sports/teams/utilityList');
        $this->domains_list = $data['domains_list'] = Modules::run($this->master_app.'/settings/domains/utilityList');

        $this->BuildContentEnv(['form', 'form_elements', 'daterangepicker']);
        if ($this->doFormValidation()) :
            $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), ['league_type_id'=>0, 'league_name'=>'', 'country_codes'=>[], 'domain_id'=>0, 'starts_on'=>'','ends_on'=>'','published' =>1]));
            if ($item_id) :
                $data['success'] = $this->lang->line('success_league_save');
                $this->deleteCache($this->cache_list);
                if (!$this->item_ID) :
                    $this->session->set_flashdata('flashSuccess', $data['success']);
                    redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                endif;
            else : $data['error'] = $this->lang->line('err_league_save');
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
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_league_delete'));
        $this->deleteCache($this->cache_list);
        redirect($this->setAppURL($this->list_link));
    }

    public function utilityList($cnfg1=[]) {
        $this->utility_cnfg['filters'] = ['where'=>['a.published'=>1,
            'c.published'=>1,
            'd.published'=>1,
            'a.ends_on >='=> formatDateTime('', 'Y-m-d H:i:s||GMT')
            ]
        ];
        return parent::utilityList($cnfg1);
    }
}
