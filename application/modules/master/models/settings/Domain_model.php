<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Domain_model extends RPTAPP_Model {
	
	protected $_table = 'domains';
	protected $_fields = ['id'=> 0, 'title'=> '', 'slug'=>'', 'url'=>'', 'is_down'=>0, 'down_message'=>'', 'display_notice'=>0, 'notice_message'=>'', 'user_groups'=>[], 'modified_by' => 0, 'modified_on' => '', 'published' => 1];
	protected $db_Select_m = ['a.*', 'group_concat(c.group_id) as user_groups'];
	protected $db_Joins_m = [
            ['users_groups_domains_map as c', 'a.id = c.domain_id', 'left']            
	];
        protected $db_Groups_m = ['a.id'];
	protected $db_Order_m = ['a.id'=>'desc'];
        
        protected $before_setDataItems = ['explode'=>'setOperation(user_groups)'];
        protected $before_setData = ['explode'=>'setOperation(user_groups)'];
        
        protected $before_update = ['timeStamps(modified_on)', 'alterItemBy(modified_by)'];
        protected $before_insert = ['timeStamps(modified_on)', 'alterItemBy(modified_by)'];
        
        protected $after_update = ['manageDataMapping'];
        protected $after_insert = ['manageDataMapping'];
        
	public $data_items = [];
	public $data_item;
		protected $_table_mapping = [
            'users_groups_domains_map'=>['fkey'=>'domain_id', 'map_fields'=>['user_groups'=>'group_id'], 'post_field'=>'user_groups']
        ];
	
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
        
        protected function noMasterAlter($row) {
            if($this->data_item->id == 5) : 
                $row['is_down'] = 0;
                $row['published'] = 1;
            endif;
        return $row;
        }

}
