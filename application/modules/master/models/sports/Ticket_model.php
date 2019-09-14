<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ticket_model extends RPTAPP_Model {
	protected $_table = 'sports_tickets';
	protected $_fields = ['id'=> 0,'ticket_number'=>'','ticket_pricing_id'=>'','is_sold_out'=>1,'modified_by'=>0, 'modified_on'=>'', 'published'=> 1];
	protected $db_Select_m = ['a.*'];
    protected $db_Order_m = ['a.id'=>'asc','a.ticket_number'=>'asc'];
        
    protected $before_update = ['timeStamps(modified_on)', 'alterItemBy(modified_by)'];
    protected $before_insert = ['timeStamps(modified_on)', 'alterItemBy(modified_by)'];
    //protected $after_update = ['manageParentGroupOrder_OwnTable', 'itemOrderingProcessEnd'];
        
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
