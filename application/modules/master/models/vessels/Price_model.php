<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Price_model extends RPTAPP_Model {
	
	protected $_table = 'vessels_prices';
	protected $_fields = ['id'=> 0, 'price_type_id'=> '0','price_label'=> '', 'price'=> '0.00','description'=> '', 'modified_by' => 0, 'modified_on' => '', 'published' => 1];
	protected $db_Select_m = ['a.*', 'b.type_name', 'b.currency_code'];
	protected $db_Joins_m = [
		['vessels_price_types as b', 'a.price_type_id = b.id', 'left']
	];
    protected $db_Order_m = ['b.type_name'=>'asc', 'a.price'=>'asc'];
        
    protected $before_update = ['timeStamps(modified_on)', 'alterItemBy(modified_by)'];
    protected $before_insert = ['timeStamps(modified_on)', 'alterItemBy(modified_by)'];

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
