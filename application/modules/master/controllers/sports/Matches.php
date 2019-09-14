<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Matches extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'sports';
    protected $list_link = 'sports/matches';
    protected $tbl = 'sports_matches';

    protected $model_name = 'sports/Match_model';
    protected $model = 'match_model';
    protected $is_model = 0;

    protected $utility_cnfg = [
        'select'=> ['a.id', 'CONCAT(f.team_name, " - Vs - ", g.team_name) as match_team', 'a.match_timing', 'b.stadium_name', 'c.type_name as match_type', 'b.country_code', 'd.league_name', 'e.type_name as league_type'],
        'hooks'=> ['FormattedResultList(match_type.match_team.match_timing.stadium_name.country_code.league_name.league_type)']
    ];

    protected $acl_btn_list = [
        'remove_btn'=>['mthd'=>'', 'func'=>'matches_delete_btn', 'actn'=>'view', 'rdrct'=>0]
    ];

    protected $match_types_list = [];
    protected $stadiums_list =[];
    protected $leagues_list =[];
    protected $teams_list =[];

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/matches');
        $this->_loadModelEnv();
    }

    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->_initEnv();

        $data['items'] = $this->get_Items();
        if (count($data['items']) > 0) :
            $this->BuildContentEnv(['table']);
            $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/matches.js', 'page');
        endif;

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/matches';
        $data['view_class'] = 'matches';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if ($this->item_ID) :
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_match';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_match';
        endif;
        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        //$data = $this->BuildAdminEnv($data);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/match_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/match_form';
        $data['view_class'] = 'match_form_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $this->form_validation->set_rules('league_id', 'lang:err_match_league', 'trim|required|in_list[' . implode(',', array_keys($this->leagues_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $multi_unique_param = [
            'id' => $this->item_ID,
            'fields' => ['home_team_id', 'match_timing'],
            'table' => $this->tbl
        ];
        $this->form_validation->set_rules('home_team_id', 'lang:err_match_home_team', 'trim|required|in_list[' . implode(',', array_keys($this->teams_list)) . ']|multi_is_unique[' . json_encode($multi_unique_param) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $multi_unique_param = [
            'id' => $this->item_ID,
            'fields' => ['away_team_id', 'match_timing'],
            'table' => $this->tbl
        ];
        $this->form_validation->set_rules('away_team_id', 'lang:err_match_away_team', 'trim|required|differs[home_team_id]|in_list[' . implode(',', array_keys($this->teams_list)) . ']|multi_is_unique[' . json_encode($multi_unique_param) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('match_timing', 'lang:err_match_timing', 'trim|required|validate_date[m/d/Y H:i]');
        $multi_unique_param = [
            'id' => $this->item_ID,
            'fields' => ['stadium_id', 'match_timing'],
            'table' => $this->tbl
        ];
        $this->form_validation->set_rules('stadium_id', 'lang:err_match_stadium', 'trim|required|in_list[' . implode(',', array_keys($this->stadiums_list)) . ']|multi_is_unique[' .json_encode($multi_unique_param) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('match_type_id', 'lang:err_match_type', 'trim|required|in_list[' . implode(',', array_keys($this->match_types_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('published', 'lang:err_published', 'trim|required|in_list[' . implode(',', array_keys($this->boolean_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        return $this->form_validation->run();
    }

    protected function FormProcess($data) {
        $this->_initEnv();
        $this->{$this->model}->setItem($this->item_ID);
        if ($this->item_ID > 0 && ($this->item_ID != $this->{$this->model}->data_item->id)) :
            $this->unauthorized_access();
        endif;
        $this->BuildContentEnv(['form', 'form_elements', 'daterangepicker']);

        $this->stadiums_list = $data['stadiums_list'] = Modules::run($this->master_app.'/sports/stadiums/utilityList');
        $this->leagues_list = $data['leagues_list'] = Modules::run($this->master_app.'/sports/leagues/utilityList');
        $this->teams_list = $data['teams_list'] = Modules::run($this->master_app.'/sports/teams/utilityList');
        $this->match_types_list = $data['match_types_list'] = Modules::run($this->master_app.'/sports/match_types/utilityList');

        if ($this->doFormValidation()) :
            $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), ['home_team_id'=>'','away_team_id'=>'','match_timing'=>'','stadium_id'=>'','league_id'=>'', 'match_type_id'=>'', 'published' =>1]));
            if ($item_id) :
                $data['success'] = $this->lang->line('success_match_save');
                $this->deleteCache($this->cache_list);
                if (!$this->item_ID) :
                    $this->session->set_flashdata('flashSuccess', $data['success']);
                    redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                endif;
            else : $data['error'] = $this->lang->line('err_match_save');
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
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_match_delete'));
        $this->deleteCache($this->cache_list);
        redirect($this->setAppURL($this->list_link));
    }

    public function utilityList($cnfg1=[]) {
        $curr_date = formatDateTime('', 'Y-m-d H:i:s||GMT');
        $this->utility_cnfg['filters'] =
            ['where'=>['a.published'=>1,
                'b.published'=>1,
                'd.published'=>1,
                'e.published'=>1,
                'f.published'=>1,
                'g.published'=>1,
                'd.ends_on >='=> $curr_date,
                'a.match_timing >='=> $curr_date
                ]
            ];
        return parent::utilityList($cnfg1);
    }

}
