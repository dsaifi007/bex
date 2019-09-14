<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Category_model extends RPTAPP_Model {

    protected $_table = 'shop_categories';
    protected $_fields = [
        'id' => 0, 
        'category_name'=> '', 
        'category_description'=> '', 
        'item_ordering'=> 0,
        'parent_id'=>0, 
        'published'=>1, 
        'modified_on' => '',
        'stores'=>[]
    ];
    protected $db_Select_m = ['a.*', 'group_concat(b.store_id) as stores'];
    protected $db_Joins_m = [
        ['shop_stores_categories_map as b', 'a.id = b.category_id', 'left']
    ];
    protected $db_Groups_m = ['a.id'];
    protected $db_Order_m = ['a.item_ordering'=> 'asc'];
    protected $before_setDataItems = ['explode' => 'setOperation(stores)'];
    protected $before_setData = ['explode' => 'setOperation(stores)'];
    protected $before_update = ['timeStamps(modified_on)', 'alterItemBy(modified_by)', 'itemOrderingProcessBegin'];
    protected $before_insert = ['timeStamps(modified_on)', 'alterItemBy(modified_by)', 'itemOrderingProcessBegin'];
    protected $after_update = ['manageParentGroupOrder_OwnTable', 'itemOrderingProcessEnd', 'manageDataMapping'];
    protected $after_insert = ['manageDataMapping'];

    protected $_table_mapping = [
        'shop_stores_categories_map' => ['fkey' => 'category_id', 'map_fields' => ['stores' => 'store_id'], 'post_field' => 'stores']
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

    public function setItems($filter_arr = []) {
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
