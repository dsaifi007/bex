<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Acl_content_model extends RPTAPP_Model {
	
	protected $_table = 'acl_content';
	protected $_fields = ['id'=> 0, 'content_title'=> '', 'content_type'=>1, 'parent_id'=>0, 'acl_level_id'=>0, 'user_groups'=>[], 'domain_id'=>0, 'description'=>'', 'modified_by' => 0, 'modified_on' => '', 'published' => 1, 'acl_level'=>'', 'domain_title'=>''];
	protected $db_Select_m = ['a.*', 'd.acl_level', 'e.title as domain_title'];
	protected $db_Joins_m = [
            ['acl_access_levels as d', '(a.acl_level_id = d.id and d.published=1)', 'left'],
            ['domains as e', '(a.domain_id = e.id and e.published=1)', 'left']
	];
	protected $db_Order_m = ['a.content_title'=> 'asc'];
        
        protected $before_setDataItems = ['json_decode' =>'setOperation(user_groups)'];
        protected $before_setData = ['json_decode' =>'setOperation(user_groups)'];
        
        protected $before_update = ['timeStamps(modified_on)', 'alterItemBy(modified_by)', 'json_encode' =>'setOperation(user_groups)', 'setParentBasedOnType'];
        protected $before_insert = ['timeStamps(modified_on)', 'alterItemBy(modified_by)', 'json_encode' =>'setOperation(user_groups)', 'setParentBasedOnType'];
        
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
	
        protected function setParentBasedOnType($row) {
            $row[$this->_parent_order_field] = ($row['content_type'] == 1)? 0:$row[$this->_parent_order_field];
        return $row;
        }
        
}
