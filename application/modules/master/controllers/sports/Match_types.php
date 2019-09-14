<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Match_types extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'sports';
    protected $list_link = 'sports/match_types';
    protected $tbl = 'sports_match_types';
    
    protected $model_name = 'sports/Match_type_model';
    protected $model = 'match_type_model';
    protected $is_model = 0;
    protected $league_types_list = [];

    protected $utility_cnfg = [
        'select'=> ['a.id', 'a.type_name', 'b.type_name as league_type_name'],
        'filters'=> ['where'=>['a.published'=>1, 'b.published'=>1]],
        'hooks'=> ['FormattedResultList(type_name.league_type_name)']
    ];

    protected $acl_btn_list = [
        'remove_btn'=>['mthd'=>'', 'func'=>'match_types_delete_btn', 'actn'=>'view', 'rdrct'=>0]
    ];

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/match_types');
        $this->_loadModelEnv();
    }
    
    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->_initEnv();
		
        $data['items'] = $this->get_Items();
        if (count($data['items']) > 0) :
            $this->BuildContentEnv(['table']);
            $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/match_types.js', 'page');
        endif;

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/match_types';
        $data['view_class'] = 'match_types';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if ($this->item_ID) :
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_match_type';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_match_type';
        endif;
        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        //$data = $this->BuildAdminEnv($data);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/match_type_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/match_type_form';
        $data['view_class'] = 'match_type_form_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $multi_unique_param = [
            'id' => $this->item_ID,
            'fields' => ['type_name', 'league_type_id'],
            'table' => $this->tbl
        ];
		$this->form_validation->set_rules('type_name', 'lang:err_match_type_name', 'trim|required|min_length[2]|max_length[100]|multi_is_unique[' . json_encode($multi_unique_param) . ']');
		$this->form_validation->set_rules('league_type_id', 'lang:err_match_league_name', 'trim|required|in_list[' . implode(',', array_keys($this->league_types_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
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
        $this->league_types_list = $data['league_types_list'] = Modules::run($this->master_app.'/sports/league_types/utilityList');
		if ($this->doFormValidation()) :
            $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), ['type_name'=>'','league_type_id'=>'','published' =>1]));
            if ($item_id) :
                $data['success'] = $this->lang->line('success_match_type_save');
                $this->deleteCache($this->cache_list);
                if (!$this->item_ID) :             
                    $this->session->set_flashdata('flashSuccess', $data['success']);
                    redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                endif;
            else : $data['error'] = $this->lang->line('err_match_type_save');
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
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_match_type_delete'));
        $this->deleteCache($this->cache_list);
        redirect($this->setAppURL($this->list_link));
    }

}
