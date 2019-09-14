<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Stadium_stands extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'sports';
    protected $list_link = 'sports/stadium_stands';
    protected $tbl = 'sports_stadium_stands';

    protected $model_name = 'sports/Stadium_stand_model';
    protected $model = 'stadium_stand';
    protected $is_model = 0;
    protected $stadiums_list =[];

    protected $utility_cnfg = [
        'select'=> ['a.id', 'a.stand_name, b.stadium_name, b.country_code'],
        'filters'=> ['where'=>['a.published'=>1, 'b.published'=>1]],
        'hooks'=> ['FormattedResultList(stand_name.stadium_name.country_code)']
    ];

    protected $acl_btn_list = [
        'remove_btn'=>['mthd'=>'', 'func'=>'stadium_stands_delete_btn', 'actn'=>'view', 'rdrct'=>0]
    ];

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/stadium_stands');
        $this->_loadModelEnv();
    }

    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->_initEnv();

        $data['items'] = $this->get_Items();
        if (count($data['items']) > 0) :
            $this->BuildContentEnv(['table']);
            $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/stadium_stands.js', 'page');
        endif;

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/stadium_stands';
        $data['view_class'] = 'stadium_stands';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if ($this->item_ID) :
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_stadium_stand';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_stadium_stand';
        endif;
        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        //$data = $this->BuildAdminEnv($data);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/stadium_stand_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/stadium_stand_form';
        $data['view_class'] = 'stadium_stand_form_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $multi_unique_param = [
            'id' => $this->item_ID,
            'fields' => ['stand_name', 'stadium_id'],
            'table' => $this->tbl
        ];
        $this->form_validation->set_rules('stand_name', 'lang:err_stadium_stand_name', 'trim|required|min_length[2]|max_length[100]|multi_is_unique['.json_encode($multi_unique_param) . ']');
        $this->form_validation->set_rules('stadium_id', 'lang:err_stadium_stand_stadium', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->stadiums_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
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
        $this->stadiums_list = $data['stadiums_list'] = Modules::run($this->master_app.'/sports/stadiums/utilityList');

        if ($this->doFormValidation()) :
            $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), ['stadium_id'=> '', 'stand_name'=>'', 'published' =>1]));
            if ($item_id) :
                $data['success'] = $this->lang->line('success_stadium_stand_save');
                $this->deleteCache($this->cache_list);
                if (!$this->item_ID) :
                    $this->session->set_flashdata('flashSuccess', $data['success']);
                    redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                endif;
            else : $data['error'] = $this->lang->line('err_stadium_stand_save');
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
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_stadium_stand_delete'));
        $this->deleteCache($this->cache_list);
        redirect($this->setAppURL($this->list_link));
    }
}
