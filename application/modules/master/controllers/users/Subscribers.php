<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Subscribers extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'users';
    protected $list_link = 'users/subscribers';
    protected $tbl = 'bex_subscribers';
    protected $usergroups_list = [];
    protected $model_name = 'users/subscriber_model';
    protected $model = 'subscriber_model';
    protected $is_model = 0;
    protected $view_mode = 0;
    protected $image_dir = 'options/';

    protected $utility_cnfg = [];
    protected $grid_settings = [
        'order_cols'=> ['a.name', 'a.email', 'a.created_on'],
        'search_cols'=> ['a.name', 'a.email', 'a.created_on'],
        'fixed_filters'=> []
    ];

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir . '/subscribers');
        $this->_loadModelEnv();
    }

    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->lang->load($this->parent_dir . '/subscribers');
        $this->BuildContentEnv(['table']);
        $this->minify->add_js($this->th_custom_js_path . $this->parent_dir . '/subscribers.js', 'page');
        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir . '/subscribers';
        $data['view_class'] = 'subscribers';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {
        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['list_link'] = $this->setAppURL($this->list_link);  
        $data['view_name'] = $this->parent_dir . '/subscriber_view';
        $data['view_class'] = 'subscriber_view_blk';
        $this->lang->load($this->parent_dir.'/view_subscriber');
        return $data;
    }

    protected function FormProcess($data) {
        $this->_initEnv();
        $this->{$this->model}->setItem($this->item_ID);
        if ($this->item_ID > 0 && ($this->item_ID != $this->{$this->model}->data_item->id)) :
            $this->unauthorized_access();
        endif;
        $data['question_list'] =  Modules::run($this->master_app.'/products/questionnaire/utilityList');
        $data['options_list'] =  Modules::run($this->master_app.'/products/questionnaire/utilityList', ['select'=>['b.id', 'b.category_id', 'b.frontend_option','b.backend_option'], 'hooks'=>[]]);
        $data['categories_list'] = Modules::run($this->master_app.'/products/category/utilityList');
        $data['image_dir_path'] = media_url('asset_images_folder').$this->image_dir;
        $data['toxic_backend']=$this->config->item('toxic_backend');
        if($this->view_mode) {
            $this->BuildContentEnv(['view_form']);
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

    public function delete($id) {
        $this->item_ID = $this->validate_decrypt_ID($id);
        $this->_initEnv();
        if (!$this->{$this->model}->delete($this->item_ID)) : $this->unauthorized_access();
        endif;
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_subscriber_delete'));
        $this->deleteCache($this->cache_list);
        redirect($this->setAppURL($this->list_link));
    }

    protected function AjaxGridRecordsList($items1) {
        $list_link = $this->setAppURL($this->list_link);
        $view_link_attr = ['class' => 'btn btn-xs tooltips', 'data-placement' => 'top', 'data-original-title' => $this->lang->line('view_item_lbl')];
        $delete_link_attr = ['class' => 'btn btn-xs text-danger', 'data-toggle' => 'confirmation', 'data-placement' => 'left', 'data-original-title' => $this->lang->line('remove_item_lbl'), 'data-popout' => 'true'];
        $rowdata = [];
        foreach ($items1 as $k => $v) :
            $encypt_id = encodeData($k);
            $action_links = '<div class="actions">';
            $action_links .= anchor(site_url($list_link . '/view/' . $encypt_id), '<i class="fa fa-search"></i>', $view_link_attr);
            $action_links .= nbs();
            $action_links .= anchor(site_url($list_link . '/delete/' . $encypt_id), '<i class="fa fa-trash"></i>', $delete_link_attr);
            $action_links .= '</div>';

            $rowdata[] = [
                $v->name,
                $v->email,
                formatDateTime($v->created_on, $this->display_date_full_frmt),
                $action_links
            ];
        endforeach;
        return $rowdata;
    }

}
