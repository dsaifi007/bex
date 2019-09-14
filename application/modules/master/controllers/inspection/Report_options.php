<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Report_options extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'inspection';
    protected $list_link = 'inspection/report_options';
    protected $tbl = 'vessels_inspection_report_options';
    
    protected $model_name = 'inspection/Report_options_model';
    protected $model = 'report_options_model';
    protected $is_model = 0;

    protected $option_type_arr = [];
    protected $parent_list = [];
    protected $anode_list = [];
    protected $item_ordering_list = [];
    protected $option_values_list = [];
    protected $report_type_list = [];
    protected $field_type_list = [];

    protected $utility_cnfg = [
        'select'=> ['a.id', 'a.option_name'],
        'filters'=> ['where'=>['a.published'=>1]],
        'hooks'=> []
    ];

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/report_options');
        $this->_loadModelEnv();
        $this->load->helper('string');
    }
    
    public function index() {
        $data['error'] = ($this->session->flashdata('flashError'))? $this->session->flashdata('flashError'):'';
        $data['success'] = ($this->session->flashdata('flashSuccess'))? $this->session->flashdata('flashSuccess'):'';
        $this->_initEnv();

        $data['items'] = $this->get_Items(['hooks'=>['treeOrder']]);
        if(count($data['items']) > 0) :
            $this->BuildContentEnv(['table']);
            $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/report_options.js', 'page');
        endif;

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/report_options';
        $data['view_class'] = 'report_options';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if ($this->item_ID) :
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_report_option';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_report_option';
        endif;
        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/report_option_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/report_option_form';
        $data['view_class'] = 'report_option_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $this->form_validation->set_rules('option_type', 'lang:err_report_option_type','trim|required|in_list['.implode(',',array_keys($this->option_type_arr)).']', ['in_list'=>$this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('option_name', 'lang:err_option_name', 'trim|required|min_length[2]|max_length[50]');
        $multi_unique_param  = [
            'id'=>$this->item_ID,
            'fields'=> ['option_name', 'parent_id', 'report_type_id'],
            'table'=>$this->tbl
        ];
        $this->form_validation->set_rules('option_name', 'lang:err_report_option_name', 'multi_is_unique['.json_encode($multi_unique_param).']');
        $this->form_validation->set_rules('parent_id', 'lang:err_report_option_parent','trim|required|in_list['.implode(',',array_keys($this->parent_list)).']', ['in_list'=>$this->lang->line('err_in_list')]);
        if($this->input->post('option_type') == 1){
            $this->form_validation->set_rules('anode_id', 'lang:err_report_option_anode','trim|required|in_list['.implode(',',array_keys($this->anode_list)).']', ['in_list'=>$this->lang->line('err_in_list')]);
            build_postvar(['field_type'=>0]);
        }else{
            build_postvar(['anode_id'=>0]);
            $this->form_validation->set_rules('field_type', 'lang:err_report_option_field_type', 'trim|required|in_list[' . implode(',', array_keys($this->field_type_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        }
        $this->form_validation->set_rules('report_type_id', 'lang:err_report_type','trim|required|in_list['.implode(',',array_keys($this->report_type_list)).']', ['in_list'=>$this->lang->line('err_in_list')]);
        if($this->item_ID > 0 && $this->input->post('parent_id') == $this->{$this->model}->data_item->parent_id) :
            $this->form_validation->set_rules('item_ordering', 'lang:err_report_option_item_order','trim|required|in_list['.implode(',',array_keys($this->item_ordering_list)).']', ['in_list'=>$this->lang->line('err_in_list')]);
        endif;
        $this->form_validation->set_rules('notes_required', 'lang:err_report_option_notes_required', 'trim|required|in_list[' . implode(',', array_keys($this->boolean_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('published', 'lang:err_published', 'trim|required|in_list[' . implode(',', array_keys($this->boolean_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        return $this->form_validation->run();
    }

    private function arrangeReportOptionValues($data) {
        $data['option_values_list'] =  Modules::run($this->master_app.'/inspection/report_options/utilityList', ['select'=>['v.id', 'v.value'], 'filters'=> ['where'=>['v.option_id'=>$this->item_ID]], 'hooks'=>[]]);

        if(count($data['option_values_list']) < 1) : 
            $cnt = (is_array($this->input->post('option_values')))? count($this->input->post('option_values')):1;
            for($i=0;$i<$cnt;$i++) : 
                $data['option_values_list'][$i] = new stdClass();
                $data['option_values_list'][$i]->id = 0;
                $data['option_values_list'][$i]->value = '';
            endfor;
        endif;  
        sort($data['option_values_list']);
        return $data;    
    }

    protected function FormProcess($data) {
        $this->_initEnv();
        $this->{$this->model}->setItem($this->item_ID);
        if ($this->item_ID > 0 && ($this->item_ID != $this->{$this->model}->data_item->id)) :
            $this->unauthorized_access();
        endif;
        $data = $this->arrangeReportOptionValues($data);
        $this->BuildContentEnv();
        $this->option_type_arr = $data['option_type_arr'] = [0=>$this->lang->line('report_option_type_normal'), 1=>$this->lang->line('report_option_type_anode')];
        $this->anode_list = $data['anode_list'] = Modules::run($this->master_app . '/vessels/anode_types/utilityList');
        $this->report_type_list = $data['report_type_list'] = Modules::run($this->master_app . '/inspection/report_type/utilityList');
        $this->field_type_list = $data['field_type_list'] = $this->config->item('field_types');
        $items = $this->get_Items(['hooks'=>['treeOrder']]);
        $this->parent_list = $data['parent_list'] = getParentsList($items, 'option_name', 1);
        ($this->item_ID)? $this->{$this->model}->setVal('managed_all_items', $items):'';

        $this->item_ordering_list = $data['item_ordering_list'] = ($this->item_ID > 0)? $this->get_Items(['filterArr'=>['db_Select'=>['a.id', 'a.item_ordering', 'a.option_name'], 'db_Joins'=>[], 'db_Order'=>['a.item_ordering'=> 'asc'], 'db_Filters'=>['where'=>['a.parent_id'=>$this->{$this->model}->data_item->parent_id]]], 'hooks'=>['FormattedResultListOnIndexHierarchical(option_name,. ,item_ordering)']]):[];

        if ($this->doFormValidation()) :
            if($this->item_ID > 0 && ($this->item_ID == $this->input->post('parent_id'))) :
                $data['error'] = $this->lang->line('err_option_name_self_parent');
            else :
                $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), [
                    'option_name' => '', 'option_type' => 0, 'field_type' => 0, 'parent_id' => 0, 'anode_id' => 0, 'report_type_id'=> 0, 'item_ordering' => 0, 'notes_required' => 1, 'published' =>1]));
                if ($item_id) :
                    $data['success'] = $this->lang->line('success_report_option_save');
                    $this->deleteCache($this->tbl . '*');
                    if(!$this->item_ID) :
                        $this->session->set_flashdata('flashSuccess', $data['success']);
                        redirect($this->setAppURL($this->list_link.'/edit/'.$this->encodeData($item_id)));
                    else :
                        $this->{$this->model}->setItem($this->item_ID);
                        $data = $this->arrangeReportOptionValues($data);
                        $this->item_ordering_list = $data['item_ordering_list'] = $this->get_Items(['filterArr'=>['db_Select'=>['a.id', 'a.item_ordering', 'a.option_name'], 'db_Joins'=>[], 'db_Order'=>['a.item_ordering'=> 'asc'], 'db_Filters'=>['where'=>['a.parent_id'=>$this->{$this->model}->data_item->parent_id]]], 'hooks'=>['FormattedResultListOnIndexHierarchical(option_name,. ,item_ordering)']]);
                    endif;
                else : $data['error'] = $this->lang->line('err_report_option_save');endif;
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
        $children = $this->get_Items(['filterArr' => ['db_Select' => ['a.id'], 'db_Joins'=>[], 'db_Filters' => ['where' => ['a.parent_id' => $this->item_ID]]]]);
        if (count($children) > 0) :
            $this->session->set_flashdata('flashError', sprintf($this->lang->line('success_report_option_delete'), count($children)));
        else :
            if (!$this->{$this->model}->delete($this->item_ID)) {
                $this->unauthorized_access();
            }
            $this->session->set_flashdata('flashSuccess', $this->lang->line('success_report_option_delete'));
            $this->deleteCache($this->cache_list);
        endif;
        redirect($this->setAppURL($this->list_link));
    }

}
