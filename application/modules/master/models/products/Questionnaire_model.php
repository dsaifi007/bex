<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Questionnaire_model extends RPTAPP_Model {
	
	protected $_table = 'bex_questions';
	protected $_fields = ['id'=> 0, 'question'=> '', 'field_type'=>0, 'ques_type'=>0, 'required'=>1, 'tips'=>'','parent_id'=>0, 'item_ordering'=>0,  'modified_by' => 0, 'modified_on' => '', 'published' => 1];
	protected $db_Select_m = ['a.*'];
	protected $db_Joins_m = [
       	['bex_question_options as b', 'a.id = b.question_id', 'left'],  
    ];
	
    protected $db_Order_m = ['a.item_ordering'=>'asc'];
        
    protected $before_update = ['timeStamps(modified_on)', 'alterItemBy(modified_by)', 'itemOrderingProcessBegin'];
    protected $before_insert = ['timeStamps(modified_on)', 'alterItemBy(modified_by)', 'itemOrderingProcessBegin'];
	
	protected $after_update = ['mapQuestionnaireOptions', 'manageDataMapping', 'manageParentGroupOrder_OwnTable', 'itemOrderingProcessEnd'];
    protected $after_insert = ['mapQuestionnaireOptions', 'manageDataMapping'];

    //protected $_item_ordering_grp_clauses = ['menu_type_id'=>'menu_type_id'];
	
	 protected $_table_mapping = [
        'bex_question_options' => ['fkey' => 'question_id', 'map_fields' => ['category_id'=>'category_id', 'frontend_option'=>'frontend_option', 'backend_option'=>'backend_option'], 'post_field' => 'category_id']
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
	
	protected function preMappingRulesQuestionnaire($row) {
		$this->preMappingCleanUp($row, 'frontend_option', 'bex_question_options');
		return $row;
	}
	
	public function mapQuestionnaireOptions($row){
		$frontend_option = [];
		if($row['field_type'] == 4){
			if(count($row['img_data']) > 0){
				foreach($row['img_data'] as $v){
					$frontend_option[] = $v['upload_data']['file_name'];
				}	
				$row['frontend_option'] = $frontend_option;
			}	
		}
		return $row;
	}
	
}
