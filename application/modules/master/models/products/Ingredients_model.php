<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ingredients_model extends RPTAPP_Model {
	
	protected $_table = 'bex_product_ingredients';
	protected $_fields = ['id'=> 0, 'ingredient_name'=> '','modified_by' => 0, 'modified_on' => '', 'published' => 1, 'ingredient_rating'=>''];
	protected $db_Select_m = ['a.*', 'GROUP_CONCAT(b.category_id, "||", b.rating) as ingredient_rating'];
	protected $db_Joins_m = [
       	['bex_product_ingredients_rating as b', 'a.id = b.ingredient_id', 'left'],  
    ];
	
    protected $db_Order_m = ['a.ingredient_name'=>'asc'];
	protected $db_Groups_m = ['a.id'];
	
    protected $before_setData = ['ingedients_rating_render_processing'];    
    protected $before_update = ['timeStamps(modified_on)', 'alterItemBy(modified_by)'];
    protected $before_insert = ['timeStamps(modified_on)', 'alterItemBy(modified_by)'];
	
	protected $after_update = ['preMappingRulesRatingCategory', 'manageDataMapping'];
    protected $after_insert = ['preMappingRulesRatingCategory', 'manageDataMapping'];
	
	 protected $_table_mapping = [
        'bex_product_ingredients_rating' => ['fkey' => 'ingredient_id', 'map_fields' => ['category_id'=>'category_id', 'rating'=>'rating'], 'post_field' => 'category_id']
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
	
	protected function preMappingRulesRatingCategory($row) {
		$this->preMappingCleanUp($row, 'category_id', 'bex_product_ingredients_rating');
		return $row;
	}
	
	protected function ingedients_rating_render_processing($row){
		$arr = [];
		if($row->ingredient_rating != '') {
		    $arr_temp = explode(',', $row->ingredient_rating);
		    if(count($arr_temp) > 0) {
		        foreach($arr_temp as $v) {
		            $arr_temp1 = explode('||', $v);
		            if(count($arr_temp1) > 1) {
		                $arr[$arr_temp1[0]] = $arr_temp1[1];
		            }
		            else {
		                $arr[$arr_temp1[0]] = [];
		            }
		        }
		    }
		    $row->ingredient_rating = $arr;
		}
		return $row;
	}	
	
}
