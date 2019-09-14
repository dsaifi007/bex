<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_list_model extends RPTAPP_Model {
	
	protected $_table = 'vessels_inspection_reports';
	protected $_fields = ['id'=> 0, 'cleaning_data'=> '', 'report_data'=> '', 'report_status_id'=> 0, 'created_on'=>'', 'modified_by' => 0, 'modified_on' => ''];
	protected $db_Select_m = ['a.*'];

    protected $db_Order_m = ['a.created_on'=>'desc'];

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
