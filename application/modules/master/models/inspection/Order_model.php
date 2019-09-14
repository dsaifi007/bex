<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Order_model extends RPTAPP_Model {
	protected $_table = 'vessels_inspection_orders';
	protected $_fields = ['id'=> 0,'invoice_no'=>'', 'user_fullname' => '', 'user_email' => '', 'payment_method_id' =>0, 'payment_status'=>0, 'order_status_id' => 0, 'ip_address'=>'', 'total_price'=>0, 'currency_code'=>'', 'modified_by'=>0, 'modified_on'=>''];
	protected $db_Select_m = ['a.*', 'b.report_type_id', 'b.quantity', 'b.unit_price', 'm.method_name', 'l.order_status', 'v.phone', 'v.address', 'v.address1', 'v.address2', 'v.city', 'v.state_code', 'v.country_code', 'v.zipcode'];
    protected $db_Joins_m = [
        ['vessels_inspection_order_products as b', 'b.order_id = a.id', 'left'],
        ['vessels_inspection_orders_payment_methods as m', 'a.payment_method_id = m.id', 'left'],
        ['vessels_inspection_order_status as l', 'a.order_status_id = l.id', 'left'],
        ['users_info as v', 'a.user_id = v.user_id', 'left']
    ];
    protected $db_Order_m = ['a.created_on'=>'desc'];
        
    protected $before_update = [];
    protected $before_insert = [];
    protected $before_setData = ['json_decode'=>'setOperation(return_parameters)'];

	public $data_items = [];
	public $data_item;
	
	public function __construct() {
		parent::__construct();
    }
	
	public function setItem($item_id) {
		parent::setDataItem($item_id);
		$this->data_item = $this->_DataItem;
	}
	
	public function setItems($filter_arr=[]) {
		parent::setDataItems($filter_arr);
		$this->data_items = $this->Items;
	}
	
	public function save($data) {
		return parent::saveItem($data);
	}
	
	public function delete($item_id) {
		return parent::deleteItem($item_id);
	}
	
}
