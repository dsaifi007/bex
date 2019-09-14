<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Match_model extends RPTAPP_Model {
	protected $_table = 'sports_matches';
	protected $_fields = ['id'=> 0, 'home_team_id'=>0, 'away_team_id'=>0, 'match_timing'=>'', 'match_type_id'=>0, 'stadium_id'=>0,'league_id'=>0, 'league_type_id'=>0,'modified_by'=>0, 'modified_on'=>'', 'published'=> 1];
	protected $db_Select_m = ['a.*'];
    protected $db_Order_m = ['a.match_timing'=>'desc'];
        
    protected $before_update = ['timeStamps(modified_on)', 'alterItemBy(modified_by)', 'Y-m-d H:i:s||GMT'=>'changeDateTime(match_timing)'];
    protected $before_insert = ['timeStamps(modified_on,created_on)', 'alterItemBy(modified_by,created_by)', 'Y-m-d H:i:s||GMT'=>'changeDateTime(match_timing)'];

	public $data_items = [];
	public $data_item;
	
	public function __construct() {
		parent::__construct();
        $this->before_setData = ['m/d/Y H:i||'.$this->config->item('time_reference').'||GMT'=>'changeDateTime(match_timing)'];
        $this->before_setDataItems = [$this->config->item('display_date_format_full').'||'.$this->config->item('time_reference').'||GMT'=>'changeDateTime(match_timing)'];
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
