<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Extensions extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'components';
    protected $list_link = 'components/extensions';
    protected $tbl = 'extensions';
    protected $acl_levels_list = [];
    protected $model_name = 'components/Extension_model';
    protected $model = 'extension_model';
    protected $is_model = 0;

    protected $utility_cnfg = [
        'select'=> ['a.*'],
        'filters'=> ['where'=>['a.published'=>1, 'b.published'=>1, 'c.published'=>1]],
        'hooks'=> []
    ];
    protected $acl_btn_list = [
        'remove_btn'=>['mthd'=>'', 'func'=>'extensions_delete_btn', 'actn'=>'view', 'rdrct'=>0],
        'add_btn'=>['mthd'=>0, 'func'=>'extensions_add_btn', 'actn'=>'common', 'rdrct'=>0]
    ];
	protected $banner_categories = [];
    protected $testimonial_categories = [];
    protected $text_widgets = [];
	protected $domain_list = [];
	protected $menu_types = [];
	protected $extension_types = [];
    protected $usergroups_list = [];
    protected $posts_categories = [];
    protected $posts_list = [];
    protected $positions_list = [];

    protected $ext_acl_manage = 0;
	protected $url_domain_id = '';

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/extensions');
        $this->_loadModelEnv();
    }
    
    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->_initEnv();

        $data['items'] = $this->get_Items();
        if (count($data['items']) > 0) :
            $this->BuildContentEnv(['table']);
            $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/extensions.js', 'page');
        endif;

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/extensions';
        $data['view_class'] = 'extensions';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if ($this->item_ID) :
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_extension';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_extension';
        endif;
        $data['url_domain_id'] = $this->url_domain_id;
        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        //$data = $this->BuildAdminEnv($data);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/extension_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/extension_form';
        $data['view_class'] = 'extension_form_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $is_unique = ($this->item_ID > 0 && strtolower($this->input->post('ext_name')) == strtolower($this->{$this->model}->data_item->ext_name)) ? '' : '|is_unique[' . $this->tbl . '.ext_name]';
        $this->form_validation->set_rules('ext_name', 'lang:err_extension_name', 'trim|required|min_length[2]|max_length[100]|alpha_numeric_spaces'.$is_unique);
        $this->form_validation->set_rules('ext_heading', 'lang:err_ext_heading', 'trim|required|min_length[2]|max_length[255]');
		$this->form_validation->set_rules('ext_show_heading', 'lang:err_extension_show_heading', 'trim|required|in_list[' . implode(',', array_keys($this->boolean_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('domain_id', 'lang:err_ext_domain', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->domain_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('ext_type', 'lang:err_extension_type', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->extension_types)) . ']', ['in_list' => $this->lang->line('err_in_list')]);

        switch($this->input->post('ext_type')) {
            case '1':
                build_postvar(['module_id'=>$this->input->post('text_widget')]);
                $this->form_validation->set_rules('text_widget', 'lang:err_extension_module','trim|required|in_list['.implode(',',array_keys($this->text_widgets)).']', ['in_list'=>$this->lang->line('err_in_list')]);
            break;
            case '2':
                build_postvar(['module_id'=>$this->input->post('menu_type')]);
                $this->form_validation->set_rules('menu_type', 'lang:err_extension_module','trim|required|in_list['.implode(',',array_keys($this->menu_types)).']', ['in_list'=>$this->lang->line('err_in_list')]);
            break;
            case '3':
                build_postvar(['module_id'=>$this->input->post('banner_category')]);
                $this->form_validation->set_rules('banner_category', 'lang:err_extension_module','trim|required|in_list['.implode(',',array_keys($this->banner_categories)).']', ['in_list'=>$this->lang->line('err_in_list')]);
            break;
            case '4':
                build_postvar(['module_id'=>$this->input->post('testimonial_category')]);
                $this->form_validation->set_rules('testimonial_category', 'lang:err_extension_module','trim|required|in_list['.implode(',',array_keys($this->testimonial_categories)).']', ['in_list'=>$this->lang->line('err_in_list')]);
            break;
            case '5':
                build_postvar(['module_id'=>$this->input->post('post_category')]);
                $this->form_validation->set_rules('post_category', 'lang:err_extension_module','trim|required|in_list['.implode(',',array_keys($this->posts_categories)).']', ['in_list'=>$this->lang->line('err_in_list')]);
            break;
            case '6':
                build_postvar(['module_id'=>0]);
            break;
            case '7':
                build_postvar(['module_id'=>$this->input->post('post_id')]);
                $this->form_validation->set_rules('post_id', 'lang:err_extension_module','trim|required|in_list['.implode(',',array_keys($this->posts_list)).']', ['in_list'=>$this->lang->line('err_in_list')]);
                break;
        }

        if($this->ext_acl_manage) :
            $this->form_validation->set_rules('acl_level_id', 'lang:err_ext_acl_level','trim|required|in_list['.implode(',',array_keys($this->acl_levels_list)).']', ['in_list'=>$this->lang->line('err_in_list')]);
            $this->form_validation->set_rules('user_groups[]', 'lang:err_extension_usergroups','trim|in_list['.implode(',',array_keys($this->usergroups_list)).']', ['in_list'=>$this->lang->line('err_in_list')]);
        else : destroy_postvar(['acl_level_id', 'user_groups']);
        endif;

        $this->form_validation->set_rules('position_id', 'lang:err_extension_position','trim|required|in_list['.implode(',',array_keys($this->positions_list)).']', ['in_list'=>$this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('is_ext_global', 'lang:err_extension_global', 'trim|required|in_list[' . implode(',', array_keys($this->boolean_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('selected_pages', 'lang:err_extension_selected_pages', 'trim');
        $this->form_validation->set_rules('published', 'lang:err_published', 'trim|required|in_list[' . implode(',', array_keys($this->boolean_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        
		return $this->form_validation->run();
    }

    protected function FormProcess($data) {
        $this->_initEnv();
        $this->{$this->model}->setItem($this->item_ID);
        if($this->item_ID > 0) :
            if($this->item_ID != $this->{$this->model}->data_item->id) :
                $this->unauthorized_access();
            endif;
            $this->authenticate_acl_action_item_level($this->{$this->model}->data_item->acl_level_id, $this->{$this->model}->data_item->user_groups, 1);
        endif;

        $this->ext_acl_manage = $data['ext_acl_manage'] = $this->authenticate_acl_action(0, 'extension_acl_manage', 'common');
        $this->url_domain_id = ((!$this->url_domain_id) && $this->{$this->model}->data_item->domain_id > 0)? $this->{$this->model}->data_item->domain_id:$this->url_domain_id;
        $filters = ($this->url_domain_id)? ['where'=>['a.published'=>1, 'b.published'=>1, 'a.domain_id'=>$this->url_domain_id]]:[];
        $this->domain_list = $data['domain_list'] = Modules::run($this->master_app.'/settings/domains/utilityList');
        if($this->ext_acl_manage) {
            $this->acl_levels_list = $data['acl_levels_list'] = Modules::run($this->master_app . '/acl/access_levels/utilityList');
            $this->usergroups_list = $data['usergroups_list'] = Modules::run($this->master_app . '/users/usergroups/utilityList');
        }
        $this->extension_types = $data['extension_types'] = $this->config->item('extension_types');
		$this->banner_categories = $data['banner_categories'] = Modules::run($this->master_app.'/components/banner_categories/utilityList', ['filters'=>$filters]);
        $this->testimonial_categories = $data['testimonial_categories'] = Modules::run($this->master_app.'/components/testimonial_categories/utilityList', ['filters'=>$filters]);
        $this->text_widgets = $data['text_widgets'] = Modules::run($this->master_app.'/components/text_widgets/utilityList', ['filters'=>$filters]);
        $this->menu_types = $data['menu_types'] = Modules::run($this->master_app.'/settings/menu_types/utilityList', ['filters'=>$filters]);
        $this->posts_categories = $data['posts_categories'] = Modules::run($this->master_app.'/components/posts_categories/utilityList', 0, ['filters'=>$filters]);
        $this->positions_list = $data['positions_list'] = Modules::run($this->master_app.'/components/extensions_positions/utilityList', ['filters'=>$filters]);

        if(count($filters) > 0) { $filters['where']['d.published'] = 1; }
        $this->posts_list = $data['posts_list'] = Modules::run($this->master_app.'/components/posts/utilityList', ['filters'=>$filters]);

		$this->BuildContentEnv();
        if ($this->doFormValidation()) :
            $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), ['ext_name' => '', 'ext_type'=>0, 'module_id'=>0, 'ext_heading'=>'', 'ext_show_heading'=>'', 'domain_id'=>0, 'acl_level_id'=>0, 'user_groups'=>[], 'position_id'=>0, 'is_ext_global'=>0, 'selected_pages'=>'', 'published' =>1]));
            if ($item_id) :
                $data['success'] = $this->lang->line('success_extension_save');
                $this->deleteCache($this->cache_list);
                if (!$this->item_ID) :
                    
                    $this->session->set_flashdata('flashSuccess', $data['success']);
                    redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                endif;
            else : $data['error'] = $this->lang->line('err_extension_save');
            endif;
        endif;
        $data = $this->_initBasicFormEnv($data);
        $this->displayView($data);
    }

    public function add($domain_id='') {
        $data['error'] = '';
        $data['success'] = '';
        $this->url_domain_id = (is_numeric($domain_id))? $domain_id:'';
        $this->url_domain_id = ($this->url_domain_id == '' && $this->input->post('domain_id') > 0)? $this->input->post('domain_id'):$this->url_domain_id;
        $this->FormProcess($data);
    }

    public function edit($id, $domain_id='') {
        $this->item_ID = $this->validate_decrypt_ID($id);
        $this->url_domain_id = (is_numeric($domain_id))? $domain_id:'';
        $this->url_domain_id = ($this->url_domain_id == '' && $this->input->post('domain_id') > 0)? $this->input->post('domain_id'):$this->url_domain_id;
        $data['error'] = '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->FormProcess($data);
    }

    public function delete($id) {
        $this->item_ID = $this->validate_decrypt_ID($id);
        $this->_initEnv();
        if (!$this->{$this->model}->delete($this->item_ID)) : $this->unauthorized_access();
        endif;
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_extension_delete'));
        $this->deleteCache($this->cache_list);
        redirect($this->setAppURL($this->list_link));
    }

}
