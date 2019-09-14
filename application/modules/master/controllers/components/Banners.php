<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Banners extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'components';
    protected $list_link = 'components/banners';
    protected $tbl = 'banners';

    protected $model_name = 'components/Banner_model';
    protected $model = 'banners_model';
    protected $is_model = 0;

    protected $utility_cnfg = [
        'select'=> ['a.id', 'a.banner_name', 'a.description', 'a.click_url', 'a.banner_image', 'a.banner_width', 'a.banner_height'],
        'filters'=> ['where'=>['a.published'=>1, 'b.published'=>1, 'c.published'=>1]],
        'hooks'=> []
    ];

    protected $parent_list = [];
    protected $item_ordering_list = [];
    protected $banner_categories_list = [];

    protected $uploadfile_config = [];

    protected $image_dir = 'banners/';
    protected $image_dir_path = '';
    protected $image_dir_url = '';

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
        $this->lang->load($this->parent_dir.'/banners');
        $this->_loadModelEnv();
    }

    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->_initEnv();

        $data['items'] = $this->get_Items();
        if (count($data['items']) > 0) :
            $this->BuildContentEnv(['table']);
            $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/banners.js', 'page');
        endif;

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/banners';
        $data['view_class'] = 'banners';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        $data['preview_image_url'] = media_url('asset_images_folder').'/no-image.jpg';
        if ($this->item_ID) {
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_banner';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
            if($this->{$this->model}->data_item->banner_image != '' && file_exists($this->image_dir_path.$this->{$this->model}->data_item->banner_image)) {
                $data['preview_image_url'] = $this->image_dir_url.$this->{$this->model}->data_item->banner_image;
            }
        }
        else {
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_banner';
        }
        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        //$data = $this->BuildAdminEnv($data);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/banner_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/banner_form';
        $data['view_class'] = 'banner_form_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $this->form_validation->set_rules('banner_name', 'lang:err_banner_name', 'trim|required|min_length[5]|max_length[150]|alpha_numeric_spaces');
        //$this->form_validation->set_rules('parent_id', 'lang:err_banner_parent', 'trim|required|in_list[' . implode(',', array_keys($this->parent_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('category_id', 'lang:err_banner_category', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->banner_categories_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $multi_unique_param = [
            'id' => $this->item_ID,
            'fields' => ['banner_name', 'category_id'],
            'table' => $this->tbl
        ];
        $this->form_validation->set_rules('banner_name', 'lang:err_banner_name', 'multi_is_unique[' . json_encode($multi_unique_param) . ']');
        $multi_unique_param = [
            'id' => $this->item_ID,
            'fields' => ['banner_name', 'parent_id'],
            'table' => $this->tbl
        ];
        $this->form_validation->set_rules('banner_name', 'lang:err_banner_name', 'multi_is_unique[' . json_encode($multi_unique_param) . ']');
        $this->form_validation->set_rules('click_url', 'lang:err_banner_click_url', 'trim|min_length[5]|max_length[350]|valid_url|prep_url');
        $this->form_validation->set_rules('description', 'lang:err_banner_description', 'trim');
        if ($this->item_ID > 0 && $this->input->post('parent_id') == $this->{$this->model}->data_item->parent_id) :
            $this->form_validation->set_rules('item_ordering', 'lang:err_banner_item_order', 'trim|required|in_list[' . implode(',', array_keys($this->item_ordering_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        endif;
        $required_dimension = ($this->input->post('banner_image_resize') == 1)? '|required':'';
        $this->form_validation->set_rules('banner_width', 'lang:err_banner_width', 'trim|numeric|less_than_equal_to[5000]|greater_than_equal_to[0]'.$required_dimension);
        $this->form_validation->set_rules('banner_height', 'lang:err_banner_height', 'trim|numeric|less_than_equal_to[5000]|greater_than_equal_to[0]'.$required_dimension);
        $this->form_validation->set_rules('banner_image_resize', 'lang:err_banner_image_resize', 'trim|required|in_list[' . implode(',', array_keys($this->boolean_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('published', 'lang:err_published', 'trim|required|in_list[' . implode(',', array_keys($this->boolean_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        return $this->form_validation->run();
    }

    protected function FormProcess($data) {
        $this->_initEnv();
        $this->{$this->model}->setItem($this->item_ID);
        if ($this->item_ID > 0 && ($this->item_ID != $this->{$this->model}->data_item->id)) :
            $this->unauthorized_access();
        endif;
        build_postvar(['item_ordering' => 0]);

        $items = $this->get_Items(['hooks' => ['treeOrder']]);
        $this->parent_list = $data['parent_list'] = getParentsList($items, 'banner_name', 1);
        ($this->item_ID) ? $this->{$this->model}->setVal('managed_all_items', $items) : '';
        $this->item_ordering_list = $data['item_ordering_list'] = ($this->item_ID > 0) ? $this->get_Items(['filterArr' => ['db_Select' => ['a.id', 'a.item_ordering', 'a.banner_name'], 'db_Joins' => [], 'db_Order' => ['a.item_ordering' => 'asc'], 'db_Filters' => ['where' => ['a.category_id'=>$this->{$this->model}->data_item->category_id, 'a.parent_id' => $this->{$this->model}->data_item->parent_id]]], 'hooks' => ['FormattedResultListOnIndexHierarchical(banner_name,. ,item_ordering)']]) : [];
        $this->banner_categories_list = $data['banner_categories_list'] = Modules::run($this->master_app.'/components/banner_categories/utilityList');

        $this->BuildContentEnv(['form', 'form_elements', 'editor']);
        if ($this->doFormValidation()) :
            if($this->item_ID > 0 && ($this->item_ID == $this->input->post('parent_id'))) :
                $data['error'] = $this->lang->line('err_banner_self_parent');
            else :
                $this->setUploadFileConfig();
                $default_items = ['banner_name' => '', 'click_url'=>'', 'description' => '', 'published' =>1, 'category_id'=>0, 'parent_id'=>0, 'item_ordering'=>0, 'banner_width'=>0, 'banner_height'=>0];
                $img_data = $this->do_upload($this->uploadfile_config, 'banner_image_file');

                if(!isset($img_data['error'])) :
                    if(isset($img_data['upload_data'])) :
                        build_postvar(['banner_image' => $img_data['upload_data']['file_name']]);
                        $default_items += ['banner_image'=>''];
                        if($this->input->post('banner_image_resize') == 1) {
                            $this->do_image_resize($img_data['upload_data']['full_path'], ['width'=>$this->input->post('banner_width'), 'height'=>$this->input->post('banner_height')]);
                        }
                    endif;
                    $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), $default_items));
                    if ($item_id) :
                        $data['success'] = $this->lang->line('success_banner_save');
                        $this->deleteCache($this->cache_list);
                        if (!$this->item_ID) :
                            $this->session->set_flashdata('flashSuccess', $data['success']);
                            redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                        else :
                            if(isset($img_data['upload_data']) && $this->{$this->model}->data_item->banner_image !='') {
                                $this->deletefile($this->image_dir_path . $this->{$this->model}->data_item->banner_image);
                            }
                            $this->{$this->model}->setItem($this->item_ID);
                            $this->item_ordering_list = $data['item_ordering_list'] = $this->get_Items(['filterArr' => ['db_Select' => ['a.id', 'a.item_ordering', 'a.banner_name'], 'db_Joins' => [], 'db_Order' => ['a.item_ordering' => 'asc'], 'db_Filters' => ['where' => ['a.category_id'=>$this->{$this->model}->data_item->category_id, 'a.parent_id' => $this->{$this->model}->data_item->parent_id]]], 'hooks' => ['FormattedResultListOnIndexHierarchical(banner_name,. ,item_ordering)']]);
                        endif;
                    else : $data['error'] = $this->lang->line('err_banner_save');
                    endif;
                else :
                    $data['error'] = $this->lang->line('err_banner_image').$img_data['error'];
                endif;
            endif;
        endif;
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
            $this->session->set_flashdata('flashError', sprintf($this->lang->line('error_banner_delete'), count($children)));
        else :
            if (!$this->{$this->model}->delete($this->item_ID)) {
                $this->unauthorized_access();
            }
            $this->session->set_flashdata('flashSuccess', $this->lang->line('success_banner_delete'));
            $this->deleteCache($this->cache_list);
        endif;
        redirect($this->setAppURL($this->list_link));
    }

}
