<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_status_model extends RPTAPP_Model {
	
	protected $_table = 'vessels_inspection_report_status';
	protected $_fields = ['id'=> 0, 'report_status'=> '','parent_id'=>0,'item_ordering' => 0,'modified_by' => 0, 'modified_on' => '', 'published' => 1];
	protected $db_Select_m = ['a.*'];
    protected $db_Order_m = ['a.item_ordering'=>'asc'];
        
    protected $before_update = ['timeStamps(modified_on)', 'alterItemBy(modified_by)'];
    protected $before_insert = ['timeStamps(modified_on)', 'alterItemBy(modified_by)'];

    protected $after_update = ['manageParentGroupOrder_OwnTable', 'itemOrderingProcessEnd'];
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
