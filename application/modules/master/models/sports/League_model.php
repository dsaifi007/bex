<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class League_model extends RPTAPP_Model {
	
	protected $_table = 'sports_leagues';
	protected $_fields = ['id'=> 0, 'league_name'=> '', 'league_type_id'=>0, 'country_codes'=>[], 'domain_id'=>0, 'starts_on'=>'', 'ends_on'=>'', 'modified_by' => 0, 'modified_on' => '', 'published' => 1, 'teams'=>[]];
	protected $db_Select_m = ['a.*', 'group_concat(b.team_id) as teams'];
    protected $db_Joins_m = [
        ['sports_leagues_teams_map as b', 'a.id = b.league_id', 'left']
    ];
    protected $db_Groups_m = ['a.id'];
	protected $db_Order_m = ['a.starts_on'=>'desc'];

    protected $before_setDataItems = ['json_decode'=>'setOperation(country_codes)', 'explode' => 'setOperation(teams)'];
    protected $before_setData = ['json_decode'=>'setOperation(country_codes)', 'explode' => 'setOperation(teams)'];
    protected $before_update = ['json_encode'=>'setOperation(country_codes)', 'timeStamps(modified_on)', 'alterItemBy(modified_by)', 'Y-m-d H:i:s||GMT'=>'changeDateTime(starts_on,ends_on)'];
    protected $before_insert = ['json_encode'=>'setOperation(country_codes)', 'timeStamps(modified_on)', 'alterItemBy(modified_by)', 'Y-m-d H:i:s||GMT'=>'changeDateTime(starts_on,ends_on)'];
    protected $after_update = ['manageDataMapping'];
    protected $after_insert = ['manageDataMapping'];

    protected $_table_mapping = [
        'sports_leagues_teams_map' => ['fkey' => 'league_id', 'map_fields' => ['teams' => 'team_id'], 'post_field' => 'teams']
    ];

	public $data_items = [];
	public $data_item;
	
	public function __construct() {
		parent::__construct();
		$this->before_setData += ['m/d/Y H:i||'.$this->config->item('time_reference').'||GMT'=>'changeDateTime(starts_on,ends_on)'];
	    $this->before_setDataItems += [$this->config->item('display_date_format_full').'||'.$this->config->item('time_reference').'||GMT'=>'changeDateTime(starts_on,ends_on)'];
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
