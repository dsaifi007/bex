<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Cleaning_schedule_model extends RPTAPP_Model {
	
	protected $_table = 'vessels_cleaning_schedule';
	protected $_fields = ['id'=> 0, 'vessel_id'=> 0, 'status_id'=>0, 'notes'=>'', 'assigned_to'=>'', 'cleaning_date'=>'', 'assigned_on'=>'','created_on'=>'', 'modified_by' => 0, 'modified_on' => '', 'published' => 1, 'diver_id' => []];
	protected $db_Select_m = ['a.*', 'b.vessel_name', 'c.status_label',  'GROUP_CONCAT(d.diver_id) as diver_id', 'GROUP_CONCAT(u.first_name , " ", u.middle_name , " ", u.last_name separator "<br>") as diver_name'];
	protected $db_Joins_m = [
		['vessels as b', 'a.vessel_id = b.id', 'left'],
		['vessels_cleaning_schedule_status as c', 'a.status_id = c.id', 'left'], 
		['vessels_cleaning_schedule_divers_map as d', 'a.id = d.cleaning_id', 'left'],
		['users as u', 'd.diver_id = u.id', 'left'],              
	];
    protected $db_Order_m = ['a.cleaning_date'=>'asc'];
    protected $db_Groups_m = ['a.id'];    

    protected $before_setData = [ 'explode' => 'setOperation(diver_id)'];

    protected $before_update = ['timeStamps(modified_on)', 'alterItemBy(modified_by)'];
    protected $before_insert = ['timeStamps(modified_on)', 'alterItemBy(modified_by)'];

    protected $after_update = ['manageDataMapping'];
    protected $after_insert = ['manageDataMapping'];

    protected $_table_mapping = [
        'vessels_cleaning_schedule_divers_map' => ['fkey' => 'cleaning_id', 'map_fields' => ['diver_id'=>'diver_id'], 'post_field' => 'diver_id']
    ];

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
			//	d($this->db->last_query());
	}
	
	public function save($data) {
		return parent::saveItem($data);
	}
	
	public function delete($item_id) {
		return parent::deleteItem($item_id);
	}
	
}
