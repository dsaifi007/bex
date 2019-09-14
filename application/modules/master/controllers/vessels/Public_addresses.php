<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Public_addresses extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'vessels';
    protected $list_link = 'vessels/public_addresses';
    protected $tbl = 'vessels_public_addresses';
    protected $model_name = 'vessels/Public_address_model';
    protected $model = 'public_address_model';
    protected $is_model = 0;
    protected $utility_cnfg = [
        'select'=> ['a.id', 'a.marina_name', 'a.address','a.city','a.state_name','a.zip_code'],
        'filters'=> ['where'=>['a.published'=>1]],
        'hooks'=> ['FormattedResultList(marina_name.address.city.state_name.zip_code)']
    ];

    protected $grid_settings = [
        'order_cols'=> ['a.marina_name', 'a.zip_code', 'a.city', 'a.state_name', 'a.country_code', 'a.security_code', 'a.published'],
        'search_cols'=> ['a.marina_name', 'a.state_prefix', 'a.zip_code', 'a.city', 'a.state_name', 'a.country_code'],
        'fixed_filters' => []
    ];

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir . '/public_addresses');
        $this->_loadModelEnv();
    }

    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->lang->load($this->parent_dir . '/public_addresses');
        $this->BuildContentEnv(['table']);
        $this->minify->add_js($this->th_custom_js_path . $this->parent_dir.'/public_addresses.js', 'page');
        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir . '/public_addresses';
        $data['view_class'] = 'public_addresses';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if ($this->item_ID) :
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_public_address';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_public_address';
        endif;
        $this->lang->load($this->parent_dir . '/' . $lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        $this->minify->add_js($this->th_custom_js_path . $this->parent_dir . '/public_address_form.js', 'page');
        $data['view_name'] = $this->parent_dir . '/public_address_form';
        $data['view_class'] = 'public_address_form_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $multi_unique_param = [
            'id'=>$this->item_ID,
            'fields'=> ['marina_name', 'zip_code'],
            'table'=>$this->tbl
        ];
        $this->form_validation->set_rules('marina_name', 'lang:err_marina_name', 'trim|required|min_length[2]|max_length[50]|multi_is_unique['.json_encode($multi_unique_param).']');
        $this->form_validation->set_rules('address', 'lang:err_address', 'trim|required|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('zip_code', 'lang:err_zip_code', 'trim|required|numeric|min_length[5]|max_length[8]');
        $this->form_validation->set_rules('city', 'lang:err_city', 'trim|required|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('state_name', 'lang:err_state_name', 'trim|required|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('state_prefix', 'lang:err_state_prefix', 'trim|required|min_length[2]|max_length[5]');
        $this->form_validation->set_rules('country_code', 'lang:err_country_code', 'trim|required|min_length[2]|max_length[3]');
        $this->form_validation->set_rules('security_code', 'lang:err_security_code', 'trim|required|min_length[2]|max_length[25]');
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
                'marina_name' => '',
                'address' => '',
                'zip_code' => '',
                'city' => '',
                'state_name' => '',
                'state_prefix' => '',
                'country_code' => '',
                'security_code' => '',
                'published' => ''
            ]));
            if ($item_id) :
                $data['success'] = $this->lang->line('success_public_address_save');
                $this->deleteCache($this->tbl . '*');
                if (!$this->item_ID) :
                    $this->session->set_flashdata('flashSuccess', $data['success']);
                    redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                endif;
            else : $data['error'] = $this->lang->line('err_public_address_save');
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
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_public_address_delete'));
        $this->deleteCache($this->tbl . '*');
        redirect($this->setAppURL($this->list_link));
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
                $v->marina_name,
                $v->zip_code,
                $v->city,
                $v->state_name . ' (' . $v->state_prefix . ')',
                $v->country_code,
                $v->security_code,
                display_status_btn($v->published),
                $action_links
            ];
        endforeach;
        return $rowdata;
    }

}
