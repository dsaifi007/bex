<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Menu_items extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'settings';
    protected $list_link = 'settings/menu_items';
    protected $tbl = 'menu_items';

    protected $acl_levels_list = [];
    protected $usergroups_list = [];
    protected $parent_list = [];
    protected $menu_types_list = [];
    protected $item_ordering_list = [];
    protected $item_type_arr = [];

    protected $model_name = 'settings/Menu_item_model';
    protected $model = 'menu_item_model';
    protected $is_model = 0;

    protected $utility_cnfg = [
        'select'=> [],
        'filters'=> ['where'=>['a.published'=>1, 'c.published'=>1, 'd.published'=>1, 'e.published'=>1]],
        'hooks'=> ['treeOrder(parent_id, 1)']
    ];
    protected $acl_btn_list = [
        'remove_btn'=>['mthd'=>'', 'func'=>'menu_item_delete_btn', 'actn'=>'view', 'rdrct'=>0],
        'add_btn'=>['mthd'=>0, 'func'=>'menu_item_add_btn', 'actn'=>'common', 'rdrct'=>0]
    ];

    protected $menu_item_acl_manage = 0;

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/menu_items');
        $this->_loadModelEnv();
        $this->load->helper('string');
    }

    public function index()
    {
        $data['error'] = ($this->session->flashdata('flashError'))? $this->session->flashdata('flashError'):'';
        $data['success'] = ($this->session->flashdata('flashSuccess'))? $this->session->flashdata('flashSuccess'):'';
        $this->_initEnv();

        $data['items'] = $this->get_Items(['hooks'=>['treeOrder']]);
        if(count($data['items']) > 0) :
            //$data['usergroup_items'] = Modules::run($this->master_app.'/users/usergroups/utilityList', 1);
            $this->BuildContentEnv(['table']);
            $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/menu_items.js', 'page');
        endif;

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/menu_items';
        $data['view_class'] = 'menu_items';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['browser_nav_arr'] = [0=>$this->lang->line('menu_browser_nav_self_lbl'), 1=>$this->lang->line('menu_browser_nav_new_tab_lbl')];
        $data['add_link'] = '';
        if($this->item_ID) :
            $data['form_action'] = $this->list_link.'/edit/'.$this->encodeData($this->item_ID);
            $lang = 'edit_menu_item';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link.'/add';
            $lang = 'add_menu_item';
        endif;
        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        //$data = $this->BuildAdminEnv($data);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/menu_item_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/menu_item_form';
        $data['view_class'] = 'menu_item_form_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        build_postvar(['user_groups'=>[]]);
        $this->form_validation->set_rules('menu_title', 'lang:err_menu_item_title', 'trim|required|min_length[2]|max_length[50]');
        $this->form_validation->set_rules('item_type', 'lang:err_menu_item_type','trim|required|in_list['.implode(',',array_keys($this->item_type_arr)).']', ['in_list'=>$this->lang->line('err_in_list')]);
        $menu_url_add_on = ($this->input->post('item_type') == 3)? '|required|prep_url':'';
        $this->form_validation->set_rules('menu_url', 'lang:err_err_menu_item_url', 'trim|min_length[1]|max_length[150]'.$menu_url_add_on);
        $this->form_validation->set_rules('menu_type_id', 'lang:err_menu_item_type','trim|required|in_list['.implode(',',array_keys($this->menu_types_list)).']', ['in_list'=>$this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('parent_id', 'lang:err_menu_item_parent','trim|required|in_list['.implode(',',array_keys($this->parent_list)).']', ['in_list'=>$this->lang->line('err_in_list')]);

        $multi_unique_param = [
            'id'=>$this->item_ID,
            'fields'=> ['menu_title', 'menu_type_id', 'parent_id'],
            'table'=>$this->tbl
        ];
        $this->form_validation->set_rules('menu_title', 'lang:err_menu_item_title', 'multi_is_unique['.json_encode($multi_unique_param).']');

        if($this->menu_item_acl_manage) :
            $this->form_validation->set_rules('acl_level_id', 'lang:err_menu_item_acl_level','trim|required|in_list['.implode(',',array_keys($this->acl_levels_list)).']', ['in_list'=>$this->lang->line('err_in_list')]);
            $this->form_validation->set_rules('user_groups[]', 'lang:err_menu_item_usergroups','trim|in_list['.implode(',',array_keys($this->usergroups_list)).']', ['in_list'=>$this->lang->line('err_in_list')]);
        else : destroy_postvar(['acl_level_id', 'user_groups']);
        endif;

        if($this->item_ID > 0 && $this->input->post('parent_id') == $this->{$this->model}->data_item->parent_id) :
            $this->form_validation->set_rules('item_ordering', 'lang:err_menu_item_order','trim|required|in_list['.implode(',',array_keys($this->item_ordering_list)).']', ['in_list'=>$this->lang->line('err_in_list')]);
        endif;
        $this->form_validation->set_rules('menu_icon', 'lang:err_err_menu_item_icon', 'trim|alpha_dash|min_length[2]|max_length[25]');
        $this->form_validation->set_rules('browser_nav', 'lang:err_menu_item_browser_nav', 'trim|in_list['.implode(',',array_keys($this->boolean_arr)).']', ['in_list'=>$this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('published', 'lang:err_published','trim|required|in_list['.implode(',',array_keys($this->boolean_arr)).']', ['in_list'=>$this->lang->line('err_in_list')]);

        return $this->form_validation->run();
    }

    protected function FormProcess($data) {
        $this->_initEnv();
        //$this->load->helper('string');
        $this->{$this->model}->setItem($this->item_ID);
        if($this->item_ID > 0) :
            if($this->item_ID != $this->{$this->model}->data_item->id) :
                $this->unauthorized_access();
            endif;
            $this->authenticate_acl_action_item_level($this->{$this->model}->data_item->acl_level_id, $this->{$this->model}->data_item->user_groups, 1);
        endif;
        $this->menu_item_acl_manage = $data['menu_item_acl_manage'] = $this->authenticate_acl_action(0, 'menu_item_acl_manage', 'common');
        $this->item_type_arr = $data['item_type_arr'] = [0=>$this->lang->line('menu_item_type_link_lbl'), 1=>$this->lang->line('menu_item_type_sep_lbl'), 2=>$this->lang->line('menu_item_type_oth_module_link_lbl'), 3=>$this->lang->line('menu_item_type_external_link_lbl')];

        if($this->menu_item_acl_manage) {
            $this->usergroups_list = $data['usergroups_list'] = Modules::run($this->master_app . '/users/usergroups/utilityList');
            $this->acl_levels_list = $data['acl_levels_list'] = Modules::run($this->master_app . '/acl/access_levels/utilityList');
        }
        $items = $this->get_Items(['hooks'=>['treeOrder']]);
        $this->parent_list = $data['parent_list'] = getParentsList($items, 'menu_title', 1);
        ($this->item_ID)? $this->{$this->model}->setVal('managed_all_items', $items):'';

        $this->menu_types_list = $data['menu_types_list'] = Modules::run($this->master_app.'/settings/menu_types/utilityList');

        $this->item_ordering_list = $data['item_ordering_list'] = ($this->item_ID > 0)? $this->get_Items(['filterArr'=>['db_Select'=>['a.id', 'a.item_ordering', 'a.menu_title'], 'db_Joins'=>[], 'db_Order'=>['a.item_ordering'=> 'asc'], 'db_Filters'=>['where'=>['a.menu_type_id'=>$this->{$this->model}->data_item->menu_type_id,'a.parent_id'=>$this->{$this->model}->data_item->parent_id]]], 'hooks'=>['FormattedResultListOnIndexHierarchical(menu_title,. ,item_ordering)']]):[];


        $this->BuildContentEnv();
        if($this->doFormValidation()) :
            if($this->item_ID > 0 && ($this->item_ID == $this->input->post('parent_id'))) :
                $data['error'] = $this->lang->line('err_menu_item_self_parent');
            else :
                $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), ['menu_title'=>'', 'item_type'=>0, 'menu_url'=>'', 'menu_type_id'=>0, 'parent_id'=>0, 'acl_level_id'=>0, 'item_ordering'=>0, 'menu_icon'=>'', 'browser_nav'=>0, 'user_groups'=>[], 'published'=>1]));
                if($item_id) :
                    $data['success'] = $this->lang->line('success_menu_item_save');
                    $this->deleteCache($this->cache_list);
                    if(!$this->item_ID) :
                        $this->session->set_flashdata('flashSuccess', $data['success']);
                        redirect($this->setAppURL($this->list_link.'/edit/'.$this->encodeData($item_id)));
                    else :
                        $this->{$this->model}->setItem($this->item_ID);
                        $this->item_ordering_list = $data['item_ordering_list'] = $this->get_Items(['filterArr'=>['db_Select'=>['a.id', 'a.item_ordering', 'a.menu_title'], 'db_Joins'=>[], 'db_Order'=>['a.item_ordering'=> 'asc'], 'db_Filters'=>['where'=>['a.menu_type_id'=>$this->{$this->model}->data_item->menu_type_id,'a.parent_id'=>$this->{$this->model}->data_item->parent_id]]], 'hooks'=>['FormattedResultListOnIndexHierarchical(menu_title,. ,item_ordering)']]);
                    endif;
                else : $data['error'] = $this->lang->line('err_menu_item_save'); endif;
            endif;
        endif;

        $data = $this->_initBasicFormEnv($data);
        $this->displayView($data);
    }

    public function add() {
        $data['error'] = ''; $data['success'] = '';
        $this->FormProcess($data);
    }

    public function edit($id) {
        $this->item_ID = $this->validate_decrypt_ID($id);
        $data['error'] = ''; $data['success'] = ($this->session->flashdata('flashSuccess'))? $this->session->flashdata('flashSuccess'):'';
        $this->FormProcess($data);
    }

    public function delete($id) {
        $this->item_ID = $this->validate_decrypt_ID($id);
        $this->_initEnv();
        $children = $this->get_Items(['filterArr'=>['db_Select'=>['a.id'], 'db_Filters'=>['where'=>['a.parent_id'=>$this->item_ID]]]]);
        if(count($children) > 0) :
            $this->session->set_flashdata('flashError', sprintf($this->lang->line('error_menu_item_delete'), count($children)));
        else :
            if(!$this->{$this->model}->delete($this->item_ID)) : $this->unauthorized_access(); endif;
            $this->session->set_flashdata('flashSuccess', $this->lang->line('success_menu_item_delete'));
            $this->deleteCache($this->cache_list);
        endif;
        redirect($this->setAppURL($this->list_link));
    }

}
