<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Access_level_model extends RPTAPP_Model {
	
	protected $_table = 'acl_access_levels';
	protected $_fields = ['id'=> 0, 'acl_level'=> '', 'user_groups'=>'', 'modified_by' => 0, 'modified_on' => '', 'published' => 1];
	protected $db_Select_m = ['a.*'];
	protected $db_Order_m = ['a.acl_level'=> 'asc'];
        
        protected $before_setDataItems = ['json_decode' =>'setOperation(user_groups)'];
        protected $before_setData = ['json_decode' =>'setOperation(user_groups)'];
        
        protected $before_update = ['timeStamps(modified_on)', 'alterItemBy(modified_by)', 'json_encode' =>'setOperation(user_groups)'];
        protected $before_insert = ['timeStamps(modified_on)', 'alterItemBy(modified_by)', 'json_encode' =>'setOperation(user_groups)'];
        
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
