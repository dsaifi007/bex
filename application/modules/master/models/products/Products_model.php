<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products_model extends RPTAPP_Model {
	
	protected $_table = 'bex_products';
	protected $_fields = ['id'=> 0, 'brands_id'=>'', 'product_name'=>'', 'product_url'=>'', 'product_image'=>'', 'description'=>'', 'ingredients'=>'', 'toxic'=>0 ,'modified_by' => 0, 'product_image_width'=>'', 'product_image_height'=>'', 'modified_on' => '', 'published' => 1, 'ingredient_id'=>''];
protected $db_Select_m = ['a.*', 'group_concat(c.ingredient_id) as ingredient_id'];

	protected $db_Joins_m = [
        ['bex_products_ingredients_map as c', 'a.id = c.product_id', 'left']
    ];

    protected $db_Order_m = ['a.product_name'=>'asc'];
    protected $db_Groups_m = ['a.id'];
	
    protected $before_setDataItems = ['explode' => 'setOperation(ingredient_id)'];
    protected $before_setData = ['explode' => 'setOperation(ingredient_id)'];

    protected $before_update = ['timeStamps(modified_on)', 'alterItemBy(modified_by)'];
    protected $before_insert = ['timeStamps(modified_on)', 'alterItemBy(modified_by)'];

    protected $after_update = ['mapProductIngredients', 'manageDataMapping'];
    protected $after_insert = ['mapProductIngredients', 'manageDataMapping'];

    protected $_table_mapping = [
        'bex_products_ingredients_map' => ['fkey' => 'product_id', 'map_fields' => ['ingredient_id'=>'ingredient_id'], 'post_field' => 'ingredient_id']
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

	public function mapProductIngredients($row){
		$ingredient_id = '';
		$ingredients_item =  explode(',', $row['ingredients']);
		//$this->preMappingCleanUp($row, 'ingredient_id', 'bex_products_ingredients_map');
		$this->ingredients_list = Modules::run('/products/ingredients/utilityList');
		foreach($ingredients_item as $v1){
			$value = rtrim(strtolower(str_replace('*', '', trim($v1))), ',');
			$ingredients_list = array_map('strtolower', $this->ingredients_list);
			if(in_array($value, $ingredients_list)){
				$ingredient_id = array_search($value, $ingredients_list);		
			} 
			$row['ingredient_id'] = $ingredient_id;
		}
/*		$indgrnd=explode(' ', trim($row['description']));
		$list = $this->db->get("bex_skin_category");
		$foundIngradints=array();
		foreach ($list->result_array() as $ingrad) {
			$ingrArr=explode(',', $ingrad['ingredients_name']);
			$trimedIngrArr=array_map('trim',$ingrArr);
			$matchedData=array_intersect($trimedIngrArr, $indgrnd);
			if(count($matchedData)){
              $foundIngradints[]=$ingrad;
			}
		}*/
		//$row['found_ingrnd']=$foundIngradints;
		return $row;
	}

	public function productdes_ingradiants()
	{
		$this->db->select('*');
		$this->db->from('bex_skin_category');
		$this->db->join('bex_skin_category_type', 'bex_skin_category_type.id = bex_skin_category.category_type_id')->order_by("category_type_name","desc");
		$list = $this->db->get();
		return $list->result_array();
	}

	public function ingrd_rating()
	{
		$this->db->select('*');
		$this->db->from('bex_product_ingredients');
		$this->db->join('bex_product_ingredients_rating',
		 'bex_product_ingredients.id = bex_product_ingredients_rating.ingredient_id')
		->order_by("ingredient_name","asc");
		//$this->db->where('bex_product_ingredients_rating.category_id',1);
		//$this->db->where('bex_product_ingredients_rating.id',21);
		$list = $this->db->get();
		return $list->result_array();
	}
}
