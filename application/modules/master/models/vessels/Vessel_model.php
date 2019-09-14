<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vessel_model extends RPTAPP_Model {
	
	protected $_table = 'vessels';
	protected $_fields = ['id'=> 0, 'vessel_name'=> '', 'user_id'=>'', 'vessel_type_id'=>'', 'style_id'=>'', 'manufacturer_id'=>'', 'loa'=>'', 'drive_type_id'=> '','no_of_drives'=>'', 'location_type'=>'','location'=>'', 'schedule_id'=>'', 'schedule_type'=>'', 'next_cleaning_date'=>'', 'modified_by' => 0, 'modified_on' => '', 'published' => 1, 'vessel_prices' => ''];

	protected $db_Select_m = ['a.*', 'b.style_name', 'c.manufacturer_name', 'd.drive_type_name', 'u.first_name', 'u.middle_name', 'u.last_name', 'GROUP_CONCAT(v.price_type_id, "||", v.price_id) as vessel_prices'];
	protected $db_Joins_m = [
		['vessels_styles as b', 'a.style_id = b.id', 'left'],
		['vessels_manufacturers as c', 'a.manufacturer_id = c.id', 'left'], 
		['vessels_drive_types as d', 'a.drive_type_id = d.id', 'left'],   
        ['users as u', 'a.user_id = u.id', 'left'],
        ['vessels_price_type_map as v', 'a.id = v.vessel_id', 'left']                    
	];
    protected $db_Order_m = ['a.vessel_name'=>'asc'];
    protected $db_Groups_m = ['a.id'];

    protected $before_setDataItems = ['json_decode' => 'setOperation(location)'];
    protected $before_setData = ['json_decode' => 'setOperation(location)' ,'price_render_processing'];
    protected $before_update = ['timeStamps(modified_on)', 'alterItemBy(modified_by)', 'json_encode' => 'setOperation(location)', 'Y-m-d H:i:s'=>'changeDateTime(next_cleaning_date)'];
    protected $before_insert = ['timeStamps(modified_on)', 'alterItemBy(modified_by)', 'json_encode' => 'setOperation(location)', 'Y-m-d H:i:s'=>'changeDateTime(next_cleaning_date)'];
    protected $after_update = ['manageDataMapping'];
    protected $after_insert = ['manageDataMapping'];

    protected $_table_mapping = [
        'vessels_price_type_map' => ['fkey' => 'vessel_id', 'map_fields' => ['price_type'=>'price_type_id', 'price_id'=>'price_id'], 'post_field' => 'price_id']
    ];

	public $data_items = [];
	public $data_item;
	
	public function __construct() {
		parent::__construct();
		$this->before_setData += ['m/d/Y'=>'changeDateTime(next_cleaning_date)'];
	    $this->before_setDataItems += [$this->config->item('display_date_format_full')=>'changeDateTime(next_cleaning_date)'];
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

	protected function price_render_processing($row){
		$arr = [];
		if($row->vessel_prices != '') {
		    $arr_temp = explode(',', $row->vessel_prices);
		    if(count($arr_temp) > 0) {
		        foreach($arr_temp as $v) {
		            $arr_temp1 = explode('||', $v);
		            if(count($arr_temp1) > 1) {
		                $arr[$arr_temp1[0]][] = $arr_temp1[1];
		            }
		            else {
		                $arr[$arr_temp1[0]] = [];
		            }
		        }
		    }
		    $row->vessel_prices = $arr;
		}
		return $row;
	}	
}
