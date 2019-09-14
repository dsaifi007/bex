<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Post_model extends RPTAPP_Model {

    protected $_table = 'posts';
    protected $_fields = [
        'id'=> 0,
        'post_name' => '',
        'post_alias' => '',
        'parent_id' => 0,
        'item_ordering' => 0,
        'post_category_id' => '',
        'domain_id'=>'',
        'modified_on' => '',
        'post_image'=>'',
        'description'=>'',
        'meta_title'=>'',
        'meta_keywords'=>'',
        'meta_description'=>'',
        'published' => 1
    ];
    protected $db_Select_m = ['a.*', 'b.title as domain', 'd.category_name', 'c.description', 'c.post_image', 'c.meta_title', 'c.meta_keywords', 'c.meta_description'];
    protected $db_Order_m = ['a.post_category_id'=>'asc', 'a.item_ordering'=> 'asc'];
    protected $db_Joins_m = [
        ['posts_description as c', 'a.id = c.post_id', 'left'],
        ['domains as b', 'b.id = a.domain_id', 'left'],
        ['posts_categories as d', 'd.id = a.post_category_id', 'left']
    ];
    protected $before_setDataItems = [];
    protected $before_setData = [];

    protected $before_update = ['strtolower'=>'setOperation(post_alias)', 'url_title'=>'setOperation(post_alias)', 'timeStamps(modified_on)', 'alterItemBy(modified_by)', 'itemOrderingProcessBegin'];
    protected $before_insert = ['strtolower'=>'setOperation(post_alias)', 'url_title'=>'setOperation(post_alias)', 'timeStamps(modified_on)', 'alterItemBy(modified_by)', 'itemOrderingProcessBegin'];

    protected $after_update = ['manageParentGroupOrder_OwnTable', 'itemOrderingProcessEnd', 'manageDataMapping'];
    protected $after_insert = ['manageDataMapping'];
    protected $_table_mapping = [
        'posts_description' => ['fkey' => 'post_id', 'map_fields' => ['description' => 'description', 'post_image'=>'post_image', 'meta_title'=>'meta_title', 'meta_keywords'=>'meta_keywords', 'meta_description'=>'meta_description'], 'post_field' => 'description']
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

}
