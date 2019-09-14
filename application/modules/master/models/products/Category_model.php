<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends RPTAPP_Model {
	
	protected $_table = 'bex_skin_category';
	protected $_fields = ['id'=> 0, 'category_type_id' => 0, 'frontend_name'=> '','backend_name'=> '', 'tips'=>'', 'modified_by' => 0, 'modified_on' => '', 'published' => 1];
	protected $db_Select_m = ['a.*', 'b.category_type_name'];
	protected $db_Joins_m = [
        ['bex_skin_category_type as b', 'b.id = a.category_type_id', 'left']
    ];
    protected $db_Order_m = ['a.category_type_id'=>'asc'];
        
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
