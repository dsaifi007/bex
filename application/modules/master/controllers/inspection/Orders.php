<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Orders extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'inspection';
    protected $list_link = 'inspection/orders';
    protected $tbl = 'vessels_inspection_orders';

    protected $model_name = 'inspection/Order_model';
    protected $model = 'order_model';
    protected $is_model = 0;
    protected $view_mode = 0;
    protected $orders_payment_methods_list = [];
    protected $order_status_list = [];
	protected $payment_status_list = [];
    protected $currency_codes_list = [];

    protected $acl_btn_list = [
        'remove_btn'=>['mthd'=>0, 'func'=>'orders_delete_btn', 'actn'=>'common', 'rdrct'=>0]
    ];
    protected $grid_settings = [
        'order_cols'=> ['a.invoice_no', 'a.user_fullname', 'a.user_email', 'm.method_name', 'l.order_status', 'a.currency_code', 'a.total_price', 'a.modified_on'],
        'search_cols'=> ['a.invoice_no', 'a.user_fullname', 'a.user_email', 'm.method_name', 'l.order_status', 'a.currency_code', 'a.total_price', 'a.modified_on'],
        'fixed_filters'=> []
    ];


    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/orders');
        $this->_loadModelEnv();
    }

    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->lang->load($this->parent_dir.'/orders');
        $this->BuildContentEnv(['table']);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/orders.js', 'page');
        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/orders';
        $data['view_class'] = 'orders';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {
       $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if ($this->item_ID) :
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_order';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_order';
        endif;
        $data['list_link'] = $this->setAppURL($this->list_link);
        if(!$this->view_mode) {
            $data['form_action'] = $this->setAppURL($data['form_action']);
            $this->minify->add_js($this->th_custom_js_path . $this->parent_dir . '/order_form.js', 'page');
            $data['view_name'] = $this->parent_dir . '/order_form';
            $data['view_class'] = 'order_form_blk';
        }
        else {
            $lang = 'view_order';
            $data['view_name'] = $this->parent_dir . '/order_view';
            $data['view_class'] = 'order_view_blk';
        }
        $this->lang->load($this->parent_dir.'/'.$lang);
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $this->form_validation->set_rules('order_status_id', 'lang:err_order_status_id', 'trim|required|in_list[' . implode(',', array_keys($this->order_status_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        return $this->form_validation->run();
    }

    protected function FormProcess($data) {
        $this->_initEnv();
        $this->{$this->model}->setItem($this->item_ID);
        if ($this->item_ID > 0) {
            if($this->item_ID != $this->{$this->model}->data_item->id) {
                $this->unauthorized_access();
            }
        }
        $this->orders_payment_methods_list = $data['orders_payment_methods_list'] = Modules::run($this->master_app.'/inspection/orders_payment_methods/utilityList');
        $this->order_status_list = $data['order_status_list'] = Modules::run($this->master_app.'/inspection/order_status/utilityList');
        if(!$this->view_mode) {
            $this->BuildContentEnv();
            if ($this->doFormValidation()) :
                $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), ['order_status_id'=>0]));
                if ($item_id) :
                    $data['success'] = $this->lang->line('success_order_save');
                    $this->deleteCache($this->cache_list);
                    if (!$this->item_ID) :
                        $this->session->set_flashdata('flashSuccess', $data['success']);
                        redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                    endif;
                else : $data['error'] = $this->lang->line('err_order_save');
                endif;
            endif;
        }
        else {
            $this->BuildContentEnv(['view_form']);
        }
        $data = $this->_initBasicFormEnv($data);
        $this->displayView($data);
    }

    public function edit($id) {
        $this->item_ID = $this->validate_decrypt_ID($id);
        $data['error'] = '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->FormProcess($data);
    }

    public function view($id) {
        $this->view_mode = 1;
        $this->currency_codes_list= $data['currency_codes_list'] = $this->config->item('currency_codes_list');
        $this->item_ID = $this->validate_decrypt_ID($id);
        $data['error'] = '';
        $this->FormProcess($data);
    }

    public function delete($id) {
        $this->item_ID = $this->validate_decrypt_ID($id);
        $this->_initEnv();
        if (!$this->{$this->model}->delete($this->item_ID)) : $this->unauthorized_access();
        endif;
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_order_delete'));
        $this->deleteCache($this->cache_list);
        redirect($this->setAppURL($this->list_link));
    }

   protected function AjaxGridRecordsList($items1) {
        $list_link = $this->setAppURL($this->list_link);
        $view_link_attr = ['class' => 'btn btn-xs tooltips', 'data-toggle'=>'modal', 'data-target'=>'#responsive-view-mode', 'data-placement' => 'top', 'data-original-title' => $this->lang->line('view_item_lbl')];
        $view_link_attr = ['class' => 'btn btn-xs tooltips', 'data-placement' => 'top', 'data-original-title' => $this->lang->line('view_item_lbl')];
        $edit_link_attr = ['class' => 'btn btn-xs tooltips', 'data-placement' => 'top', 'data-original-title' => $this->lang->line('edit_item_lbl')];
        $delete_link_attr = ['class' => 'btn btn-xs tooltips text-danger', 'data-toggle' => 'confirmation', 'data-placement' => 'left', 'data-original-title' => $this->lang->line('remove_item_lbl'), 'data-popout' => 'true'];
        extract($this->setACL_Btns());
        $currency_codes_list=$this->config->item('currency_codes_list');
		$payment_status_list=$this->config->item('payment_status');
        $rowdata = [];
        foreach ($items1 as $k => $v) :
            $encypt_id = $this->encodeData($k);
            $action_links = '<div class="actions">';
            $action_links .= anchor(site_url($list_link . '/view/' . $encypt_id), '<i class="fa fa-search"></i>', $view_link_attr);
            $action_links .= nbs();
            $action_links .= anchor(site_url($list_link . '/edit/' . $encypt_id), '<i class="fa fa-edit"></i>', $edit_link_attr);
            $action_links .= nbs();
            $action_links .= ($remove_btn)? anchor(site_url($list_link . '/delete/' . $encypt_id), '<i class="fa fa-trash"></i>', $delete_link_attr):'';
            $action_links .= '</div>';
            $rowdata[] = [
                $v->invoice_no,
                $v->user_fullname,
                $v->user_email, 
                //$v->method_name,
				$payment_status_list[$v->payment_status],
                $v->order_status,
                $currency_codes_list[$v->currency_code]['code'].nbs().$v->total_price,
                formatDateTime($v->created_on, $this->display_date_full_frmt),
                $action_links                           
            ];
        endforeach;
        return $rowdata;
    }
}

?>