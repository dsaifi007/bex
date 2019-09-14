<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Testimonial_model extends RPTAPP_Model {
	
	protected $_table = 'testimonials';
	protected $_fields = ['id'=> 0, 'client_name'=> '','content'=>'', 'category_id'=>0, 'modified_by' => 0, 'modified_on' => '', 'parent_id'=>0, 'item_ordering'=>0, 'published' => 1];
	protected $db_Select_m = ['a.*', 'b.category_name', 'c.title as domain'];
    protected $db_Joins_m = [
        ['testimonials_categories as b', 'a.category_id = b.id', 'left'],
        ['domains as c', 'c.id = b.domain_id', 'left']
    ];
    protected $db_Order_m = ['a.category_id'=>'asc', 'a.item_ordering'=>'asc'];
        
    protected $before_update = ['timeStamps(modified_on)', 'alterItemBy(modified_by)', 'itemOrderingProcessBegin'];
    protected $before_insert = ['timeStamps(modified_on)', 'alterItemBy(modified_by)', 'itemOrderingProcessBegin'];
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
