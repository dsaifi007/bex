<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Zipcodes extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'utilities';
    protected $list_link = 'utilities/zipcodes';
    protected $tbl = 'zip_codes';
    protected $model_name = 'utilities/Zipcode_model';
    protected $model = 'zipcode_model';
    protected $is_model = 0;

    protected $utility_cnfg = [
        'select'=> ['a.id', 'a.zip_code', 'a.city', 'a.state_name'],
        'filters'=> ['where'=>['a.published'=>1]],
        'hooks'=> []
    ];

    protected $grid_settings = [
        'order_cols'=> ['a.zip_code', 'a.city', 'a.county', 'a.state_name', 'a.country_code', 'a.published'],
        'search_cols'=> ['a.zip_code', 'a.city', 'a.county', 'a.state_name', 'a.country_code'],
        'fixed_filters'=> []
    ];

    protected $googleMapAPIKey;

    public function __construct() {
        parent::__construct();
        $this->googleMapAPIKey = $this->config->item('google_map_api_key');
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir . '/zipcodes');
        $this->_loadModelEnv();
    }

    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->_initEnv();

        $this->BuildContentEnv(['table']);
        $this->minify->add_js($this->th_custom_js_path . $this->parent_dir . '/zipcodes.js', 'page');

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir . '/zipcodes';
        $data['view_class'] = 'zipcodes';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if ($this->item_ID) :
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_zipcode';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_zipcode';
        endif;
        $this->lang->load($this->parent_dir . '/' . $lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        //$data = $this->BuildAdminEnv($data);
        $this->minify->add_js($this->th_custom_js_path . $this->parent_dir . '/zipcode_form.js', 'page');
        $data['view_name'] = $this->parent_dir . '/zipcode_form';
        $data['view_class'] = 'zipcode_form_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $is_unique = ($this->item_ID > 0 && $this->input->post('zip_code') == $this->{$this->model}->data_item->zip_code) ? '' : '|is_unique[' . $this->tbl . '.zip_code]';
        $this->form_validation->set_rules('zip_code', 'lang:err_zip_code', 'trim|required|numeric|min_length[5]|max_length[8]' . $is_unique);
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
        if ($this->doFormValidation()) :
            $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), [
                'zip_code' => '',
                'city' => '',
                'county' => '',
                'state_name' => '',
                'state_prefix' => '',
                'lat' => 0,
                'lon' => 0,
                'country_code' => '',
                'published' => ''
            ]));
            if ($item_id) :
                $data['success'] = $this->lang->line('success_zip_code_save');
                $this->deleteCache($this->cache_list);
                if (!$this->item_ID) :
                    $this->session->set_flashdata('flashSuccess', $data['success']);
                    redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                endif;
            else : $data['error'] = $this->lang->line('err_zip_code_save');
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
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_zip_code_delete'));
        $this->deleteCache($this->cache_list);
        redirect($this->setAppURL($this->list_link));
    }

    public function getzipinfo($zipcode) {
        $result = [];
        if ($this->input->is_ajax_request()) :
            if (strlen($zipcode) == 5 && (filter_var($zipcode, FILTER_VALIDATE_INT) !== false)) :
                $zipinfo = $this->utilityList(['filters'=>['where' => ['a.zip_code' => $zipcode, 'a.published' => 1]]]);
                if (count($zipinfo) > 0) :
                    $result = current($zipinfo);
                endif;
            endif;
        endif;
        echo jsonAjax($result);
        exit();
    }

    protected function AjaxGridRecordsList($items1) {
        $list_link = $this->setAppURL($this->list_link);
        $edit_link_attr = ['class' => 'btn btn-xs tooltips', 'data-placement' => 'top', 'data-original-title' => $this->lang->line('edit_item_lbl')];
        $delete_link_attr = ['class' => 'btn btn-xs text-danger', 'data-toggle' => 'confirmation', 'data-placement' => 'left', 'data-original-title' => $this->lang->line('remove_item_lbl'), 'data-popout' => 'true'];
        $rowdata = [];
        foreach ($items1 as $k => $v) :
            $encypt_id = $this->encodeData($k);
            $action_links = '<div class="actions">';
            $action_links .= anchor(site_url($list_link . '/edit/' . $encypt_id), '<i class="fa fa-edit"></i>', $edit_link_attr);
            $action_links .= nbs();
            $action_links .= anchor(site_url($list_link . '/delete/' . $encypt_id), '<i class="fa fa-trash"></i>', $delete_link_attr);
            $action_links .= '</div>';

            $rowdata[] = [
                $v->zip_code,
                $v->city,
                $v->county,
                $v->state_name . ' (' . $v->state_prefix . ')',
                $v->country_code,
                display_status_btn($v->published),
                $action_links
            ];
        endforeach;
        return $rowdata;
    }

    protected function getZipcodeInfo($zipcode, $country_code = 'US', $length = 5) {
        $zipdata = [];
        //if ((strlen($zipcode) == $length)) {
            $map_api = "https://maps.googleapis.com/maps/api/geocode/json?key=".$this->googleMapAPIKey."&components=country:" . $country_code . "|postal_code:" . $zipcode;
            //$map_api = "https://maps.googleapis.com/maps/api/geocode/json?key=".$this->googleMapAPIKey."&components=postal_code:" . $zipcode;
            $data = file_get_contents($map_api);
            if ($data != '') {
                $arr = json_decode($data, 1);
                if (isset($arr['results'])) {
                    if (count($arr['results']) > 0) {
                        $main_data = $arr['results'][0];
                        if (isset($main_data['address_components'])) {
                            foreach ($main_data['address_components'] as $v) {
                                $vdata = $v['long_name'];
                                if ($v['types'][0] == 'country') : $vdata = $v['short_name'];
                                endif;
                                if ($v['types'][0] == 'administrative_area_level_1') :
                                    $zipdata['state'] = $v['long_name'];
                                    $zipdata['state_prefix'] = $v['short_name'];
                                endif;
                                $zipdata[$v['types'][0]] = $vdata;
                            }
                        }
                        if (isset($main_data['geometry']['location'])) {
                            $zipdata += $main_data['geometry']['location'];
                        }
                    }
                }
            }
        //}
        return $zipdata;
    }
    
    public function zipcodeinfoGEO($zipcode, $country_code='US') {
        $data = [];
        if ($this->input->is_ajax_request()) :
            $data = $this->getZipcodeInfo($zipcode, $country_code);
        endif;
        echo jsonAjax($data);
        exit();
    }
    

}
