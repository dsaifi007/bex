<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Userinfo_model extends RPTAPP_Model {

    protected $_table = 'users';
    protected $_fields = ['id' => 0, 'email' => '', 'first_name' => '', 'middle_name' => '', 'last_name' => '', 'display_name' => '', 'password_hash' => '', 'receive_system_emails' => 0, 'block' => 0, 'active' => 1, 'user_groups' => '', 'created_on' => '', 'modified_on' => '', 'address' => '', 'address1' => '', 'address2' => '', 'zipcode' => '', 'city' => '', 'state' => '', 'state_code' => '', 'phone' => '', 'country_code'=>''];
    protected $db_Select_m = ['a.*', 'group_concat(c.group_id) as user_groups', 'd.address', 'd.address1', 'd.address2', 'd.zipcode', 'd.city', 'd.state', 'd.state_code', 'd.phone', 'd.country_code'];
    protected $db_Joins_m = [
        ['users_usergroups_map as c', 'a.id = c.user_id', 'left'],
        ['users_info as d', 'a.id = d.user_id', 'left']
    ];
    protected $db_Groups_m = ['a.id'];
    protected $db_Order_m = ['a.first_name' => 'asc', 'a.middle_name' => 'asc', 'a.last_name' => 'asc'];
    protected $before_setDataItems = ['explode' => 'setOperation(user_groups)'];
    protected $before_setData = ['explode' => 'setOperation(user_groups)'];
    protected $before_update = ['timeStamps(modified_on)', 'alterItemBy(modified_by)', 'password_hash' => 'setOperation(password_hash)'];
    protected $before_insert = ['timeStamps(modified_on,created_on)', 'alterItemBy(modified_by,created_by)', 'password_hash' => 'setOperation(password_hash)'];
    protected $after_update = ['manageDataMapping'];
    protected $after_insert = ['manageDataMapping'];
    public $data_items = [];
    public $data_item;
    protected $_table_mapping = [
        'users_usergroups_map' => ['fkey' => 'user_id', 'map_fields' => ['user_groups' => 'group_id'], 'post_field' => 'user_groups'],
        'users_info' => ['fkey' => 'user_id', 'map_fields' => ['address' => 'address', 'address1' => 'address1', 'address2' => 'address2', 'zipcode' => 'zipcode', 'city' => 'city', 'state' => 'state', 'state_code' => 'state_code', 'phone' => 'phone', 'country_code' => 'country_code'], 'post_field' => 'address']
    ];

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

    public function save($data, $childusr_grps) {
        if(count($childusr_grps) > 0) {
            $this->_table_mapping_delete_filter = [
                'users_usergroups_map' => ['filters' => ['where_in' => ['group_id' => $childusr_grps]]]
            ];
        }
        return parent::saveItem($data);
    }

    public function saveProfile($data) {
        return parent::saveItem($data);
    }

    public function delete($item_id) {
        return parent::deleteItem($item_id);
    }

}
