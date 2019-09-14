<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_item_model extends RPTAPP_Model {

    protected $_table = 'menu_items';
    protected $_fields = ['id'=> 0, 'menu_title'=> '', 'item_type'=>0, 'menu_url'=>'', 'parent_id'=>0, 'menu_type_id'=>0, 'acl_level_id'=>0, 'user_groups'=>'', 'item_ordering'=>0, 'menu_icon'=>'', 'browser_nav'=>0, 'modified_by' => 0, 'modified_on' => '', 'published' => 1, 'acl_level'=>'', 'menu_type_title'=>''];
    protected $db_Select_m = ['a.*', 'd.acl_level', 'c.title as menu_type_title'];
    protected $db_Joins_m = [
        ['menu_types as c', 'a.menu_type_id = c.id', 'left'],
        ['acl_access_levels as d', 'a.acl_level_id = d.id', 'left'],
        ['domains as e', 'e.id = c.domain_id', 'left']
    ];
    protected $db_Order_m = ['a.menu_type_id'=>'asc', 'a.item_ordering'=> 'asc', 'c.title'=>'asc'];

    protected $before_setDataItems = ['json_decode' =>'setOperation(user_groups)'];
    protected $before_setData = ['json_decode' =>'setOperation(user_groups)'];

    protected $before_update = ['timeStamps(modified_on)', 'alterItemBy(modified_by)', 'json_encode' =>'setOperation(user_groups)', 'itemOrderingProcessBegin', 'setFieldsBasedOnMenuItemType'];
    protected $before_insert = ['timeStamps(modified_on)', 'alterItemBy(modified_by)', 'json_encode' =>'setOperation(user_groups)', 'itemOrderingProcessBegin', 'setFieldsBasedOnMenuItemType'];

    protected $after_update = ['manageParentGroupOrder_OwnTable', 'itemOrderingProcessEnd'];
    protected $_item_ordering_grp_clauses = ['menu_type_id'=>'menu_type_id'];
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

    protected function setFieldsBasedOnMenuItemType($row) {
        if($row['item_type'] == 1) : $row['menu_url'] = ''; $row['browser_nav'] = 0; endif;
        return $row;
    }

}
