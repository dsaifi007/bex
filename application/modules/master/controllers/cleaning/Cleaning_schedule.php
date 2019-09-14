<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Cleaning_schedule extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'cleaning';
    protected $list_link = 'cleaning/cleaning_schedule';
    protected $tbl = 'vessels_cleaning_schedule';
    
    protected $model_name = 'cleaning/cleaning_schedule_model';
    protected $model = 'Cleaning_schedule_model';
    protected $is_model = 0;

    protected $divers_list = [];
    protected $schedule_status_list = [];
    protected $cleaning_schedule_list = [];

    protected $utility_cnfg = [
        'select'=> ['a.id', 'a.vessel_id', 'a.cleaning_date'],
        'filters'=> ['where'=>['a.published'=>1]],
        'hooks'=> []
    ];

    protected $acl_btn_list = [
        'remove_btn'=>['mthd'=>'', 'func'=>'cleaning_schedule_delete_btn', 'actn'=>'view', 'rdrct'=>0],
        'add_btn'=>['mthd'=>0, 'func'=>'cleaning_schedule_add_btn', 'actn'=>'common', 'rdrct'=>0]
    ];

    protected $grid_settings = [
        'order_cols'=> ['b.vessel_name', 'a.cleaning_date', 'a.diver_name', 'c.status_label', 'a.modified_on'],
        'search_cols'=> ['b.vessel_name', 'a.cleaning_date', 'c.status_label'],
        'fixed_filters' => []
    ];

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/cleaning_schedule');
        $this->_loadModelEnv();
    }
    
    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->_initEnv();
        $this->BuildContentEnv(['table']);

        $this->cleaning_schedule_list =  Modules::run($this->master_app.'/cleaning/cleaning_schedule/utilityList');
        $vessel_id_arr = [];
        if(count($this->cleaning_schedule_list) > 0){
            foreach($this->cleaning_schedule_list as $v){
                $vessel_id_arr[] = $v->vessel_id;
            }
        }
        $this->vessels_list =  Modules::run($this->master_app.'/vessels/vessels/utilityList',  ['select'=>['a.id', 'a.next_cleaning_date'], 'filters'=> ['where'=>['a.published'=>1 ,'a.next_cleaning_date >= '=> date('Y-m-d'), 'a.next_cleaning_date <='=> date('Y-m-d', strtotime('+1 week'))]], 'hooks'=>[]]);
        $post_fields = [];
        if(count($this->vessels_list) > 0){
            foreach($this->vessels_list as $v){
                $post_fields['vessel_id'] = $v->id;
                $post_fields['cleaning_date'] = formatDateTime($v->next_cleaning_date, 'Y-m-d');
                $post_fields['created_on'] = date('Y-m-d H:i:s');
                $post_fields['published'] = 1;

                if(!in_array($v->id, $vessel_id_arr)){
                    $this->{$this->model}->save(arrange_post_data($post_fields, ['vessel_id'=>0, 'status_id'=> 0, 'notes'=> '', 'assigned_to'=>'', 'cleaning_date'=> '', 'assigned_on'=>'', 'created_on'=>'', 'published' =>1]));   
                    $this->deleteCache($this->cache_list);               
                }
            }
        }

        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/cleaning_schedule.js', 'page');
        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/cleaning_schedule';
        $data['view_class'] = 'cleaning_schedule';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {
        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if ($this->item_ID) :
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_cleaning_schedule';
        endif;

        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/cleaning_schedule_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/cleaning_schedule_form';
        $data['view_class'] = 'cleaning_schedule_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $this->form_validation->set_rules('diver_id[]', 'lang:err_vessel_divers', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->divers_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('notes', 'lang:err_vessel_notes', 'trim|required|min_length[2]|max_length[500]');
        $this->form_validation->set_rules('status_id', 'lang:err_vessel_schedule_status', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->schedule_status_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $divers_id= (is_array($this->input->post('diver_id')))? array_filter($this->input->post('diver_id')):[];
        $this->form_validation->set_rules('assigned_to', 'lang:err_vessel_diver_assigned_to', 'trim|required|numeric|in_list[' . implode(',', $divers_id) . ']', ['in_list' => $this->lang->line('err_in_list')]);
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
        $this->divers_list = $data['divers_list']  = Modules::run($this->master_app.'/users/users/utilityList', ['filters'=> ['where'=>['a.active'=>1, 'a.block'=>0, 'c.group_id'=>6]]]);
        
        $this->schedule_status_list = $data['schedule_status_list']  = Modules::run($this->master_app.'/cleaning/schedule_status/utilityList');

        if ($this->doFormValidation()) :
            $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), [ 'vessel_id'=>0, 'status_id'=> 0, 'notes'=> '', 'assigned_to'=>'', 'cleaning_date'=> '', 'assigned_on'=>'', 'created_on'=>'', 'published' =>1]));
            if ($item_id) :
                $data['success'] = $this->lang->line('success_cleaning_schedule_save');
                $this->deleteCache($this->tbl . '*');
                if (!$this->item_ID) :             
                    $this->session->set_flashdata('flashSuccess', $data['success']);
                    redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                endif;
            else : $data['error'] = $this->lang->line('err_cleaning_schedule_save');
            endif;
        endif;
        $data = $this->_initBasicFormEnv($data);
        $this->displayView($data);
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
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_cleaning_schedule_delete'));
        $this->deleteCache($this->tbl . '*');
        //redirect($this->setAppURL($this->list_link));
    }

    public function dataAjaxGrid() {
        $this->grid_settings['fixed_filters'] = ['where'=>['a.cleaning_date >='=> date('Y-m-d')]];
        parent::dataAjaxGrid();
    }

    public function dataAjaxGrid1() {
        $this->grid_settings['fixed_filters'] = ['where'=>['a.cleaning_date <='=> date('Y-m-d')]];
        parent::dataAjaxGrid();
    }

    protected function AjaxGridRecordsList($items1) {
        $list_link = $this->setAppURL($this->list_link);
        $edit_link_attr = ['class' => 'btn btn-xs tooltips', 'data-placement' => 'top', 'data-original-title' => $this->lang->line('edit_item_lbl')];
        $delete_link_attr = ['class' => 'btn btn-xs text-danger', 'data-toggle' => 'confirmation', 'data-placement' => 'left', 'data-original-title' => $this->lang->line('remove_item_lbl'), 'data-popout' => 'true'];
        extract($this->setACL_Btns());
        $this->schedule_status_list = $data['schedule_status_list']  = Modules::run($this->master_app.'/cleaning/schedule_status/utilityList');

        $rowdata = [];
        foreach ($items1 as $k => $v) :
            $encypt_id = $this->encodeData($k);
            $action_links = '<div class="actions">';
            $action_links .= anchor(site_url($list_link . '/edit/' . $encypt_id), '<i class="fa fa-edit"></i>', $edit_link_attr);
            $action_links .= nbs();
            $action_links .= ($remove_btn) ? anchor(site_url($list_link . '/delete/' . $encypt_id), '<i class="fa fa-trash"></i>', $delete_link_attr) : '';
            $action_links .= '</div>';

            $rowdata[] = [
                $v->vessel_name,
                formatDateTime($v->cleaning_date, $this->display_date_frmt),
                $v->diver_name,
                display_status_btn($v->status_id, $this->schedule_status_list),
                formatDateTime($v->modified_on, $this->display_date_full_frmt),
                $action_links
            ];
        endforeach;
        return $rowdata;
    }
}
