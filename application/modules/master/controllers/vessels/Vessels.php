<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Vessels extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'vessels';
    protected $list_link = 'vessels/vessels';
    protected $tbl = 'vessels';
    
    protected $model_name = 'vessels/Vessel_model';
    protected $model = 'vessel_model';
    protected $is_model = 0;
    protected $view_mode = 0;

    protected $users_list = [];
    protected $vessel_type_list = [];
    protected $style_list = [];
    protected $manufacturer_list = [];
    protected $drive_type_list = [];
    protected $public_address_list= [];
    protected $schedule_list = [];
    protected $price_type_list = [];
    protected $price_list = [];
    protected $price_type_map_list = [];
    protected $schedule_map_list = [];
    protected $currency_codes_list = [];

    protected $utility_cnfg = [
        'select'=> ['a.id', 'a.vessel_name'],
        'filters'=> ['where'=>['a.published'=>1]],
        'hooks'=> ['FormattedResultList(vessel_name)']
    ];

    protected $grid_settings = [
        'order_cols'=> ['a.vessel_name', 'u.first_name', 'a.style_name', 'a.manufacturer_name', 'a.drive_type_name', 'a.published'],
        'search_cols'=> ['a.vessel_name', 'u.first_name', 'u.middle_name', 'u.last_name', 'a.style_name', 'a.manufacturer_name', 'a.drive_type_name'],
        'fixed_filters' => []
    ];

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/vessels');
        $this->_loadModelEnv();
    }
    
    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->_initEnv();
        $this->BuildContentEnv(['table']);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/vessels.js', 'page');
        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/vessels';
        $data['view_class'] = 'vessels';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if ($this->item_ID) :
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_vessel';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_vessel';
        endif;
       
        if(!empty($this->uri->segment('6'))):
            $user = $this->uri->segment('6');
            $data['item']->user_id = $this->validate_decrypt_ID($user);
        endif;  

        $data['list_link'] = $this->setAppURL($this->list_link);  
        if(!$this->view_mode) {
            $data['form_action'] = $this->setAppURL($data['form_action']);
            $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/vessel_form.js', 'page');
            $data['view_name'] = $this->parent_dir . '/vessel_form';
            $data['view_class'] = 'vessel_blk';
        }
        else {
            $lang = 'view_vessel';
            $data['view_name'] = $this->parent_dir . '/vessel_view';
            $data['view_class'] = 'vessel_view_blk';
        }
        $this->lang->load($this->parent_dir.'/'.$lang);
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $this->form_validation->set_rules('vessel_name', 'lang:err_vessel_name', 'trim|required|min_length[2]|max_length[50]');
        $multi_unique_param = [
            'id'=>$this->item_ID,
            'fields'=> ['vessel_name', 'user_id'],
            'table'=>$this->tbl
        ];
        $this->form_validation->set_rules('vessel_name', 'lang:err_vessel_name', 'multi_is_unique['.json_encode($multi_unique_param).']');
        $this->form_validation->set_rules('user_id', 'lang:err_vessel_user_type', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->users_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('vessel_type_id', 'lang:err_vessel_type', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->vessel_type_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('style_id', 'lang:err_vessel_style', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->style_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('manufacturer_id', 'lang:err_vessel_manufacturer', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->manufacturer_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('drive_type_id', 'lang:err_vessel_drive_type', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->drive_type_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('loa', 'lang:err_loa', 'trim|required|numeric|greater_than_equal_to[1]|less_than_equal_to[1000]');
        $this->form_validation->set_rules('no_of_drives', 'lang:err_vessel_no_of_drives', 'trim|required|numeric|greater_than_equal_to[1]|less_than_equal_to[100]');
        $this->form_validation->set_rules('location_type', 'lang:err_vessel_location_type', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->location_type_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);   

        if($this->input->post('location_type') == 1){
            $this->form_validation->set_rules('location[address]', 'lang:err_vessel_address', 'trim|required|min_length[2]|max_length[50]');
            $this->form_validation->set_rules('location[zip_code]', 'lang:err_vessel_zip_code', 'trim|required|numeric|min_length[5]|max_length[8]');
            $this->form_validation->set_rules('location[city]', 'lang:err_vessel_city', 'trim|required|min_length[2]|max_length[50]');
            $this->form_validation->set_rules('location[state_name]', 'lang:err_vessel_state_name', 'trim|required|min_length[2]|max_length[50]');
            $this->form_validation->set_rules('location[state_prefix]', 'lang:err_vessel_state_prefix', 'trim|required|min_length[2]|max_length[5]');
            $this->form_validation->set_rules('location[country_code]', 'lang:err_vessel_country_code', 'trim|required|min_length[2]|max_length[3]');
        }else if($this->input->post('location_type') == 3){
            $this->form_validation->set_rules('location[public_address]', 'lang:err_vessel_public_address_lbl', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->public_address_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        }

        $this->form_validation->set_rules('schedule_type', 'lang:err_vessel_schedule_type', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->schedule_type_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);  

        if($this->input->post('schedule_type') == 0){
            $this->form_validation->set_rules('schedule_id', 'lang:err_vessel_schedule', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->schedule_map_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
            $this->form_validation->set_rules('next_cleaning_date', 'lang:err_next_cleaning_date', 'trim|required|validate_date[m/d/Y]');
        }
        
        // price type array loop 
        $_POST['price_id'] = (is_array($this->input->post('price_id')))? array_filter($this->input->post('price_id')):[];
        if(count($this->price_type_list) == count($_POST['price_id'])) {
            array_shift($_POST['price_id']);
        }
        foreach($this->price_type_list as $k=> $v) {
            if(!in_array($k, array_keys($_POST['price_id']))) continue;
            if($v->display_type == 0) {
                $this->form_validation->set_rules('price_id['.$k.']', 'lang:err_vessel_price', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->price_type_map_list[$k])) . ']', ['in_list' => $this->lang->line('err_in_list')]);
            }
            else {
                $this->form_validation->set_rules('price_id['.$k.'][]', 'lang:err_vessel_price', 'trim|required|in_list[' . implode(',', array_keys($this->price_type_map_list[$k])) . ']', ['in_list' => $this->lang->line('err_in_list')]);
            }
        }

        $this->form_validation->set_rules('published', 'lang:err_published', 'trim|required|in_list[' . implode(',', array_keys($this->boolean_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        return $this->form_validation->run();
    }

    protected function FormProcess($data) {
        $this->_initEnv();
        $this->{$this->model}->setItem($this->item_ID);
        if ($this->item_ID > 0 && ($this->item_ID != $this->{$this->model}->data_item->id)) :
            $this->unauthorized_access();
        endif;
        
        $data['user_list_link'] = $this->setAppURL('users/usersinfo');
        $this->users_list = $data['users_list'] = Modules::run($this->master_app.'/users/users/utilityList', ['filters'=> ['where'=>['a.active'=>1, 'a.block'=>0, 'c.group_id'=>7]]]);
        $this->vessel_type_list = $data['vessel_type_list'] = Modules::run($this->master_app.'/vessels/types/utilityList');
        $this->style_list = $data['style_list'] = Modules::run($this->master_app.'/vessels/styles/utilityList');
        $this->manufacturer_list = $data['manufacturer_list'] = Modules::run($this->master_app.'/vessels/manufacturers/utilityList');
        $this->drive_type_list = $data['drive_type_list'] = Modules::run($this->master_app.'/vessels/drive_types/utilityList');
        $this->location_type_arr = $data['location_type_arr'] = [0=>$this->lang->line('vessel_location_type_billing_lbl'), 1=>$this->lang->line('vessel_location_type_manual_lbl'), 2=>$this->lang->line('vessel_location_type_public_address_lbl')];
        $this->public_address_list = $data['public_address_list'] = Modules::run($this->master_app.'/vessels/public_addresses/utilityList');
        $this->schedule_list = $data['schedule_list'] = Modules::run($this->master_app.'/vessels/schedule/utilityList',['select'=>['a.id', 'a.schedule_name', 'a.range_from','a.range_to'], 'hooks'=>[]]);
        $this->schedule_type_arr = $data['schedule_type_arr'] = [0=>$this->lang->line('vessel_schedule_type_automatic_lbl'), 1=>$this->lang->line('vessel_schedule_type_contact_lbl'), 2=>$this->lang->line('vessel_schedule_type_no_schedule_lbl')];
        $this->price_type_list = $data['price_type_list'] = Modules::run($this->master_app.'/vessels/price_types/utilityList', ['select'=>['a.id', 'a.type_name', 'a.display_type','a.currency_code'], 'hooks'=>[]]);
        $this->price_list = $data['price_list'] = Modules::run($this->master_app.'/vessels/prices/utilityList', ['select'=>['a.id', 'a.price_type_id', 'a.price_label', 'a.price'], 'hooks'=>[]]);

        $this->currency_codes_list  = $data['currency_codes_list']  =$this->config->item('currency_codes_list');
        $final_price_arr= [];
        if(count($this->price_type_list) > 0 && count($this->price_list) > 0) { 
            foreach($this->price_list as $m){
                if(!isset($this->price_type_list[$m->price_type_id])) { continue; }
                  $final_price_arr[$m->price_type_id][$m->id] = $m->price_label. ' | '. $this->currency_codes_list[$this->price_type_list[$m->price_type_id]->currency_code]['code'].nbs().$m->price; 
            }
        }
        $this->price_type_map_list  = $data['price_type_map_list']  = $final_price_arr;

        $final_schedule_arr= [];
        if(count($this->schedule_list) > 0) { 
            foreach($this->schedule_list as $m){
                  $final_schedule_arr[$m->id] = $m->schedule_name; 
            }
        }
        $this->schedule_map_list  = $data['schedule_map_list']  = $final_schedule_arr;

        if(!$this->view_mode) {
            $this->BuildContentEnv(['form', 'form_elements','daterangepicker']);
            if ($this->doFormValidation()) :
               $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), [ 'vessel_name'=> '', 'user_id'=>0, 'vessel_type_id'=> 0, 'style_id'=> 0, 'manufacturer_id'=>0, 'loa'=>0, 'drive_type_id'=>0, 'no_of_drives'=>0, 'location_type'=> 0, 'location'=> [], 'schedule_id'=> 0, 'schedule_type'=>'', 'next_cleaning_date'=> '', 'published' =>1]));

                        
                if ($item_id) :
                    $data['success'] = $this->lang->line('success_vessel_save');
                    $this->deleteCache($this->tbl . '*');
                    if (!$this->item_ID) :             
                        $this->session->set_flashdata('flashSuccess', $data['success']);
                        redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                    else : 
                        $this->cleaning_schedule_list =  Modules::run($this->master_app.'/cleaning/cleaning_schedule/utilityList');
                        if(count($this->cleaning_schedule_list) > 0){
                            foreach($this->cleaning_schedule_list as $v){
                                if((formatDateTime($this->input->post('next_cleaning_date'), 'Y-m-d') > date('Y-m-d')) &&  ($v->vessel_id == $item_id)){
                                    Modules::run($this->master_app.'/cleaning/cleaning_schedule/delete', $this->encodeData($v->id));
                                }
                            }
                        }
                    endif;
                else : $data['error'] = $this->lang->line('err_vessel_save');
                endif;
            endif;
        }else {
            $this->BuildContentEnv(['view_form']);
            $user_id = $this->{$this->model}->data_item->user_id;
            $location_type = $this->{$this->model}->data_item->location_type;
            $location = $this->{$this->model}->data_item->location;

            if($location_type == 0){
                $billing = Modules::run($this->master_app.'/users/usersinfo/utilityList', ['filters'=> ['where'=>['a.active'=>1, 'a.block'=>0, 'd.user_id'=>$user_id]]]);
                $location_info  = $billing[$user_id]->address; 
                $location_info .= ($billing[$user_id]->address1)? nbs().$billing[$user_id]->address1:'';  
                $location_info .= ($billing[$user_id]->address2)? nbs().$billing[$user_id]->address2:'';  
                $location_info .= ($billing[$user_id]->city)? nbs().$billing[$user_id]->city:'';  
                $location_info .= ($billing[$user_id]->state)? nbs().$billing[$user_id]->state:''; 
                $location_info .= ($billing[$user_id]->country_code)? nbs().$billing[$user_id]->country_code:''; 
                $location_info .=  nbs().$billing[$user_id]->zipcode;

            }else if($location_type == 2){
                $public_add = $location->public_address;
                $public = Modules::run($this->master_app.'/vessels/public_addresses/utilityList',['select'=>['a.id', 'a.marina_name', 'a.zip_code', 'a.city', 'a.state_name', 'a.country_code'], 'filters'=> ['where'=>['a.id'=>$public_add]], 'hooks'=>[]]);
                $location_info = $public[$public_add]->marina_name; 
                $location_info.= ($public[$public_add]->city)? nbs().$public[$public_add]->city:'';
                $location_info .= ($public[$public_add]->state_name)? nbs().$public[$public_add]->state_name:''; 
                $location_info .= ($public[$public_add]->country_code)? nbs().$public[$public_add]->country_code:''; 
                $location_info .=  nbs().$public[$public_add]->zip_code;

            }else{
                $location_info  = $location->address; 
                $location_info .= ($location->city)? nbs().$location->city:'';  
                $location_info .= ($location->state_name)? nbs().$location->state_name:''; 
                $location_info .= ($location->country_code)? nbs().$location->country_code:''; 
                $location_info .=  nbs().$location->zip_code;
            }
            
            $this->location = $data['location']  = $location_info;
        }    
        $data = $this->_initBasicFormEnv($data);
        $this->displayView($data);
    }

    public function view($id) {
        $this->view_mode = 1;
        $this->item_ID = $this->validate_decrypt_ID($id);
        $data['error'] = '';
        $this->FormProcess($data);
    }

    public function add() {
        $data['error'] = '';
        $data['success'] = '';
        $this->FormProcess($data);
    }

    public function edit($id) {
        $this->item_ID = $this->validate_decrypt_ID($id);
        $this->price_id = (is_array($this->input->post('price_id')))? array_filter($this->input->post('price_id')):[];
        $data['error'] = '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->FormProcess($data);
    }

    public function delete($id) {
        $this->item_ID = $this->validate_decrypt_ID($id);
        $this->_initEnv();
        if (!$this->{$this->model}->delete($this->item_ID)) : $this->unauthorized_access();
        endif;
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_vessel_delete'));
        $this->deleteCache($this->tbl . '*');
        redirect($this->setAppURL($this->list_link));
    }

    protected function AjaxGridRecordsList($items1) {
        $list_link = $this->setAppURL($this->list_link);
        $view_link_attr = ['class' => 'btn btn-xs tooltips', 'data-placement' => 'top', 'data-original-title' => $this->lang->line('view_item_lbl')];
        $edit_link_attr = ['class' => 'btn btn-xs tooltips', 'data-placement' => 'top', 'data-original-title' => $this->lang->line('edit_item_lbl')];
        $delete_link_attr = ['class' => 'btn btn-xs text-danger', 'data-toggle' => 'confirmation', 'data-placement' => 'left', 'data-original-title' => $this->lang->line('remove_item_lbl'), 'data-popout' => 'true'];
        $rowdata = [];
        foreach ($items1 as $k => $v) :
            $name = $v->first_name; $name .= ($v->middle_name)? nbs().$v->middle_name:''; $name .= nbs().$v->last_name;
            $encypt_id = $this->encodeData($k);
            $action_links = '<div class="actions">';
            $action_links .= anchor(site_url($list_link . '/view/' . $encypt_id), '<i class="fa fa-search"></i>', $view_link_attr);
            $action_links .= anchor(site_url($list_link . '/edit/' . $encypt_id), '<i class="fa fa-edit"></i>', $edit_link_attr);
            $action_links .= nbs();
            $action_links .= anchor(site_url($list_link . '/delete/' . $encypt_id), '<i class="fa fa-trash"></i>', $delete_link_attr);
            $action_links .= '</div>';

            $rowdata[] = [
                $v->vessel_name,
                $name,
                $v->style_name,
                $v->manufacturer_name,
                $v->drive_type_name,
                display_status_btn($v->published),
                $action_links
            ];
        endforeach;
        return $rowdata;
    }

}
