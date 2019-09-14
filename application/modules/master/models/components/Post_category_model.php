<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Post_category_model extends RPTAPP_Model {

    protected $_table = 'posts_categories';
    protected $_fields = [
        'id' => 0,
        'category_name'=> '',
        'category_slug'=>'',
        'domain_id'=>0,
        'item_ordering'=> 0,
        'parent_id'=>0,
        'published'=>1,
        'modified_on' => '',
        'description'=>'',
        'cat_image'=>'',
        'meta_title'=>'',
        'meta_keywords'=>'',
        'meta_description'=>''
    ];
    protected $db_Select_m = ['a.*', 'b.title as domain', 'c.description', 'c.cat_image', 'c.meta_title', 'c.meta_keywords', 'c.meta_description'];
    protected $db_Joins_m = [
        ['posts_categories_description as c', 'a.id = c.category_id', 'left'],
        ['domains as b', 'b.id = a.domain_id', 'left']
    ];
    protected $db_Groups_m = ['a.id'];
    protected $db_Order_m = ['a.item_ordering'=> 'asc'];
    protected $before_setDataItems = [];
    protected $before_setData = [];
    protected $before_update = ['strtolower'=>'setOperation(category_slug)', 'url_title'=>'setOperation(category_slug)', 'timeStamps(modified_on)', 'alterItemBy(modified_by)', 'itemOrderingProcessBegin'];
    protected $before_insert = ['strtolower'=>'setOperation(category_slug)', 'url_title'=>'setOperation(category_slug)', 'timeStamps(modified_on)', 'alterItemBy(modified_by)', 'itemOrderingProcessBegin'];
    protected $after_update = ['manageParentGroupOrder_OwnTable', 'itemOrderingProcessEnd', 'manageDataMapping'];
    protected $after_insert = ['manageDataMapping'];

    protected $_table_mapping = [
        'posts_categories_description' => ['fkey' => 'category_id', 'map_fields' => ['description' => 'description', 'cat_image'=>'cat_image', 'meta_title'=>'meta_title', 'meta_keywords'=>'meta_keywords', 'meta_description'=>'meta_description'], 'post_field' => 'description']
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
