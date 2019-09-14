<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Match_type_model extends RPTAPP_Model {
	protected $_table = 'sports_match_types';
	protected $_fields = ['id'=> 0,'type_name'=>'','league_type_id'=>'','modified_by'=>0, 'modified_on'=>'', 'published'=> 1];
	protected $db_Select_m = ['a.*', 'b.type_name as league_type_name'];
    protected $db_Joins_m = [
        ['sports_leagues_types as b', 'a.league_type_id = b.id', 'left']
    ];
    protected $db_Order_m = ['a.league_type_id'=>'asc', 'a.type_name'=>'asc'];
        
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
