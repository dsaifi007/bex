<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Subscriber_model extends RPTAPP_Model {

    protected $_table = 'bex_subscribers';
    protected $_fields = [];
    protected $db_Select_m = ['a.*'];
    protected $db_Order_m = ['a.name' => 'asc'];
   
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
