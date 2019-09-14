<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Posts_categories extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'components';
    protected $list_link = 'components/posts_categories';
    protected $tbl = 'posts_categories';
    protected $model_name = 'components/Post_category_model';
    protected $model = 'post_category_model';
    protected $is_model = 0;

    protected $utility_cnfg = [
        'select'=> ['a.id', 'a.parent_id', 'a.category_name'],
        'filters'=> ['where'=>['a.published'=>1, 'b.published'=>1]],
        'hooks'=> ['treeOrder']
    ];

    protected $parent_list = [];
    protected $item_ordering_list = [];
    protected $domain_list = [];

    protected $image_dir = 'posts/categories/';
    protected $image_dir_path = '';
    protected $image_dir_url = '';
    protected $uploadfile_config = [];

    public function __construct() {
        parent::__construct();
        $this->image_dir_path = media_path('asset_images_folder').$this->image_dir;
        $this->image_dir_url = media_url('asset_images_folder').$this->image_dir;
    }

    protected function setUploadFileConfig() {
        $this->load->helper('string');
        $this->uploadfile_config['upload_path']          = $this->image_dir_path;
        $this->uploadfile_config['allowed_types']        = 'gif|png|jpg';
        $this->uploadfile_config['max_size']             = $this->config->item('max_upload_limit_files'); // KB
        $this->uploadfile_config['max_width']            = $this->config->item('max_image_width');
        $this->uploadfile_config['max_height']           = $this->config->item('max_image_height');
        //$this->uploadfile_config['file_ext_tolower']     = TRUE;
        $this->uploadfile_config['file_name']            = (random_string('numeric')+time()).random_string();
    }

    private function _initEnv() {
        $this->loadLanguage($this->parent_dir . '/posts_categories');
        $this->_loadModelEnv();
        $this->load->helper('string');
    }

    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->_initEnv();

        $data['items'] = $this->get_Items(['hooks' => ['treeOrder']]);
        if (count($data['items']) > 0) :
            $this->BuildContentEnv(['table']);
            $this->minify->add_js($this->th_custom_js_path . $this->parent_dir . '/posts_categories.js', 'page');
        endif;

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir . '/posts_categories';
        $data['view_class'] = 'posts_categories';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        $data['preview_image_url'] = media_url('asset_images_folder').'/no-image.jpg';
        if ($this->item_ID) {
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_post_category';
            $data['add_link'] = $this->setAppURL($this->list_link . '/add');
            if ($this->{$this->model}->data_item->cat_image != '' && file_exists($this->image_dir_path . $this->{$this->model}->data_item->cat_image)) {
                $data['preview_image_url'] = $this->image_dir_url . $this->{$this->model}->data_item->cat_image;
            }
        }
        else {
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_post_category';
        }
        $this->loadLanguage($this->parent_dir . '/' . $lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        //$data = $this->BuildAdminEnv($data);
        $this->minify->add_js($this->th_custom_js_path . $this->parent_dir . '/post_category_form.js', 'page');
        $data['view_name'] = $this->parent_dir . '/post_category_form';
        $data['view_class'] = 'post_category_form_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $multi_unique_param = [
            'id' => $this->item_ID,
            'fields' => ['category_name', 'parent_id', 'domain_id'],
            'table' => $this->tbl
        ];
        $this->form_validation->set_rules('category_name', 'lang:err_category_name', 'trim|required|min_length[2]|max_length[100]|multi_is_unique[' . json_encode($multi_unique_param) . ']');
        $is_unique = ($this->item_ID > 0 && strtolower($this->input->post('category_slug')) == strtolower($this->{$this->model}->data_item->category_slug)) ? '' : '|is_unique[' . $this->tbl . '.category_slug]';
        $this->form_validation->set_rules('category_slug', 'lang:err_category_slug', 'trim|required|min_length[2]|max_length[150]'.$is_unique);
        $this->form_validation->set_rules('parent_id', 'lang:err_post_category_parent', 'trim|required|in_list[' . implode(',', array_keys($this->parent_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('domain_id', 'lang:err_post_category_domain', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->domain_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        if ($this->item_ID > 0 && $this->input->post('parent_id') == $this->{$this->model}->data_item->parent_id) :
            $this->form_validation->set_rules('item_ordering', 'lang:err_category_item_order', 'trim|required|in_list[' . implode(',', array_keys($this->item_ordering_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        endif;
        $this->form_validation->set_rules('description', 'lang:err_post_category_description', 'trim');
        $this->form_validation->set_rules('meta_title', 'lang:err_post_category_meta_title', 'trim|max_length[255]');
        $this->form_validation->set_rules('meta_keywords', 'lang:err_post_category_meta_keywords', 'trim|max_length[500]');
        $this->form_validation->set_rules('meta_description', 'lang:err_post_category_meta_description', 'trim|max_length[1000]');
        $required_dimension = ($this->input->post('image_resize') == 1)? '|required':'';
        $this->form_validation->set_rules('image_width', 'lang:err_image_width', 'trim|numeric|less_than_equal_to[5000]|greater_than_equal_to[0]'.$required_dimension);
        $this->form_validation->set_rules('image_height', 'lang:err_image_height', 'trim|numeric|less_than_equal_to[5000]|greater_than_equal_to[0]'.$required_dimension);
        $this->form_validation->set_rules('image_resize', 'lang:err_image_resize', 'trim|required|in_list[' . implode(',', array_keys($this->boolean_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('published', 'lang:err_published', 'trim|required|in_list[' . implode(',', array_keys($this->boolean_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        return $this->form_validation->run();
    }

    protected function FormProcess($data) {
        $this->_initEnv();
        $this->{$this->model}->setItem($this->item_ID);
        if ($this->item_ID > 0 && ($this->item_ID != $this->{$this->model}->data_item->id)) :
            $this->unauthorized_access();
        endif;
        build_postvar(['item_ordering' => 0, 'cat_image'=> $this->{$this->model}->data_item->cat_image]);

        $items = $this->get_Items(['hooks' => ['treeOrder']]);
        $this->parent_list = $data['parent_list'] = getParentsList($items, 'category_name', 1);
        ($this->item_ID) ? $this->{$this->model}->setVal('managed_all_items', $items) : '';
        $this->item_ordering_list = $data['item_ordering_list'] = ($this->item_ID > 0) ? $this->get_Items(['filterArr' => ['db_Select' => ['a.id', 'a.item_ordering', 'a.category_name'], 'db_Order' => ['a.item_ordering' => 'asc'], 'db_Filters' => ['where' => ['a.parent_id' => $this->{$this->model}->data_item->parent_id]]], 'hooks' => ['FormattedResultListOnIndex(item_ordering.category_name,. ,item_ordering)']]) : [];
        $this->domain_list = $data['domain_list'] = Modules::run($this->master_app.'/settings/domains/utilityList');

        $this->BuildContentEnv(['form', 'form_elements', 'editor']);
        if ($this->doFormValidation()) {
            if ($this->item_ID > 0 && ($this->item_ID == $this->input->post('parent_id'))) {
                $data['error'] = $this->lang->line('err_post_category_self_parent');
            } else {
                $this->setUploadFileConfig();
                $img_data = $this->do_upload($this->uploadfile_config, 'post_category_image_file');
                if (!isset($img_data['error'])) {

                    if(isset($img_data['upload_data'])) {
                        destroy_postvar(['cat_image']);
                        build_postvar(['cat_image' => $img_data['upload_data']['file_name']]);
                        if ($this->input->post('image_resize') == 1) {
                            $this->do_image_resize($img_data['upload_data']['full_path'], ['width' => $this->input->post('image_width'), 'height' => $this->input->post('image_height')]);
                        }
                    }

                    $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), [
                        'category_name' => '',
                        'category_slug' => '',
                        'domain_id' => 0,
                        'parent_id' => 0,
                        'item_ordering' => 0,
                        'published' => 1
                    ]));
                    if ($item_id) :
                        $data['success'] = $this->lang->line('success_post_category_save');
                        $this->deleteCache($this->cache_list);
                        if (!$this->item_ID) :
                            $this->session->set_flashdata('flashSuccess', $data['success']);
                            redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                        else :
                            if(isset($img_data['upload_data']) && $this->{$this->model}->data_item->cat_image !='') {
                                $this->deletefile($this->image_dir_path . $this->{$this->model}->data_item->cat_image);
                            }
                            $this->{$this->model}->setItem($this->item_ID);
                            $this->item_ordering_list = $data['item_ordering_list'] = $this->get_Items(['filterArr' => ['db_Select' => ['a.id', 'a.item_ordering', 'a.category_name'], 'db_Joins' => [], 'db_Order' => ['a.item_ordering' => 'asc'], 'db_Filters' => ['where' => ['a.parent_id' => $this->{$this->model}->data_item->parent_id]]], 'hooks' => ['FormattedResultListOnIndex(item_ordering.category_name,. ,item_ordering)']]);
                        endif;
                    else : $data['error'] = $this->lang->line('err_post_category_save');
                    endif;
                } else {
                    $data['error'] = $this->lang->line('err_post_category_image').$img_data['error'];
                }
            }
        }
        $data = $this->_initBasicFormEnv($data);
        $this->displayView($data);
    }

    public function add() {
        $data['error'] = '';
        $data['success'] = '';
        $this->FormProcess($data);
    }

    public function edit($id) {
        $this->item_ID = $this->validate_decrypt_ID($id);
        $data['error'] = '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->FormProcess($data);
    }

    public function delete($id) {
        $this->item_ID = $this->validate_decrypt_ID($id);
        $this->_initEnv();
        $children = $this->get_Items(['filterArr' => ['db_Select' => ['a.id'], 'db_Joins'=>[], 'db_Filters' => ['where' => ['a.parent_id' => $this->item_ID]]]]);
        if (count($children) > 0) :
            $this->session->set_flashdata('flashError', sprintf($this->lang->line('error_category_delete'), count($children)));
        else :
            if (!$this->{$this->model}->delete($this->item_ID)) : $this->unauthorized_access();
            endif;
            $this->session->set_flashdata('flashSuccess', $this->lang->line('success_post_category_delete'));
            $this->deleteCache($this->cache_list);
        endif;
        redirect($this->setAppURL($this->list_link));
    }

    public function utilityList($noParentList=0, $cnfg1=[]) {
        if(!$noParentList) :
            $this->load->helper('string');
            array_push($this->utility_cnfg['hooks'], 'getParentsList(category_name)');
        endif;
        return parent::utilityList($cnfg1);
    }
}
