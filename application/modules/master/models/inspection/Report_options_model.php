<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Report_options_model extends RPTAPP_Model {
	
	protected $_table = 'vessels_inspection_report_options';
	protected $_fields = ['id'=> 0, 'option_name'=> '', 'option_type'=>0, 'field_type'=>0, 'parent_id'=> 0, 'anode_id'=>0, 'report_type_id'=> 0, 'item_ordering'=>0, 'notes_required'=>1, 'modified_by' => 0, 'modified_on' => '', 'published' => 1];
	protected $db_Select_m = ['a.*', 'b.type_name'];
	protected $db_Joins_m = [
       	['vessels_inspection_report_option_values as v', 'a.id = v.option_id', 'left'],
       	['vessels_inspection_report_types as b', 'a.report_type_id = b.id', 'left']     
    ];
    protected $db_Order_m = ['a.item_ordering'=>'asc'];

    protected $before_update = ['timeStamps(modified_on)', 'alterItemBy(modified_by)', 'itemOrderingProcessBegin'];
    protected $before_insert = ['timeStamps(modified_on)', 'alterItemBy(modified_by)','itemOrderingProcessBegin'];

    protected $after_update = ['preMappingRulesReportOptions', 'manageDataMapping', 'manageParentGroupOrder_OwnTable', 'itemOrderingProcessEnd'];
    protected $after_insert = ['preMappingRulesReportOptions', 'manageDataMapping'];

    protected $_table_mapping = [
        'vessels_inspection_report_option_values' => ['fkey' => 'option_id', 'map_fields' => ['option_value'=>'value'], 'post_field' => 'option_value']
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
	}
	
	public function save($data) {
		return parent::saveItem($data);
	}
	
	public function delete($item_id) {
		return parent::deleteItem($item_id);
	}

	protected function preMappingRulesReportOptions($row) {
		$this->preMappingCleanUp($row, 'option_type', 'vessels_inspection_report_option_values');
		return $row;
	}
	
}
