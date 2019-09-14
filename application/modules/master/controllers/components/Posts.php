<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Posts extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'components';
    protected $list_link = 'components/posts';
    protected $tbl = 'posts';
    protected $model_name = 'components/Post_model';
    protected $model = 'post_model';
    protected $is_model = 0;

    protected $utility_cnfg = [
        'select'=> ['a.id', 'a.parent_id', 'a.post_name', 'd.category_name'],
        'filters'=> ['where'=>['a.published'=>1, 'b.published'=>1, 'd.published'=>1]],
        'hooks'=> ['FormattedResultList(post_name.category_name)']
    ];
    protected $grid_settings = [
        'order_cols'=> ['a.post_name', 'b.title', 'd.category_name', 'a.published', 'a.modified_on'],
        'search_cols'=> ['a.post_name', 'b.title', 'd.category_name', 'a.modified_on'],
        'fixed_filters'=> []
    ];

    protected $parent_list = [];
    protected $item_ordering_list = [];
    protected $post_categories = [];
    protected $domain_list = [];

    protected $image_dir = 'posts/';
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
        $this->lang->load($this->parent_dir.'/posts');
        $this->_loadModelEnv();
    }

    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->lang->load($this->parent_dir.'/posts');
        $this->BuildContentEnv(['table']);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/posts.js', 'page');
        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/posts';
        $data['view_class'] = 'posts';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        $data['preview_image_url'] = media_url('asset_images_folder').'/no-image.jpg';
        if ($this->item_ID) {
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_post';
            $data['add_link'] = $this->setAppURL($this->list_link . '/add');
            if ($this->{$this->model}->data_item->post_image != '' && file_exists($this->image_dir_path . $this->{$this->model}->data_item->post_image)) {
                $data['preview_image_url'] = $this->image_dir_url . $this->{$this->model}->data_item->post_image;
            }
        }
        else {
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_post';
        }
        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        //$data = $this->BuildAdminEnv($data);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/post_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/post_form';
        $data['view_class'] = 'post_form_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $multi_unique_param = [
            'id' => $this->item_ID,
            'fields' => ['post_name', 'post_category_id', 'domain_id'],
            'table' => $this->tbl
        ];
        $this->form_validation->set_rules('post_name', 'lang:err_post_name', 'trim|required|alpha_numeric_spaces|min_length[2]|max_length[255]|multi_is_unique[' . json_encode($multi_unique_param) . ']');
        $is_unique = ($this->item_ID > 0 && strtolower($this->input->post('post_alias')) == strtolower($this->{$this->model}->data_item->post_alias)) ? '' : '|is_unique[' . $this->tbl . '.post_alias]';
        $this->form_validation->set_rules('post_alias', 'lang:err_post_alias', 'trim|alpha_dash|required|min_length[2]|max_length[255]'.$is_unique);
        $this->form_validation->set_rules('post_category_id', 'lang:err_post_category_id', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->post_categories)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('domain_id', 'lang:err_post_domain', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->domain_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        if ($this->item_ID > 0 && $this->input->post('parent_id') == $this->{$this->model}->data_item->parent_id) :
            $this->form_validation->set_rules('item_ordering', 'lang:err_post_item_order', 'trim|required|in_list[' . implode(',', array_keys($this->item_ordering_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        endif;
        $this->form_validation->set_rules('description', 'lang:err_post_description', 'trim');
        $this->form_validation->set_rules('meta_title', 'lang:err_post_meta_title', 'trim|max_length[255]');
        $this->form_validation->set_rules('meta_keywords', 'lang:err_post_meta_keywords', 'trim|max_length[500]');
        $this->form_validation->set_rules('meta_description', 'lang:err_post_meta_description', 'trim|max_length[1000]');
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
        build_postvar(['item_ordering'=> 0, 'post_image'=> $this->{$this->model}->data_item->post_image]);

        $this->post_categories=$data['post_categories'] = Modules::run($this->master_app.'/components/posts_categories/utilityList');
        $items = $this->get_Items(['hooks' => ['treeOrder']]);
        $this->parent_list = $data['parent_list'] = getParentsList($items, 'post_name', 1);
        ($this->item_ID) ? $this->{$this->model}->setVal('managed_all_items', $items) : '';
        $this->item_ordering_list = $data['item_ordering_list'] = ($this->item_ID > 0) ? $this->get_Items(['filterArr' => ['db_Select' => ['a.id', 'a.item_ordering', 'a.post_name'], 'db_Joins' => [], 'db_Order' => ['a.item_ordering' => 'asc'], 'db_Filters' => ['where' => ['a.post_category_id'=>$this->{$this->model}->data_item->post_category_id, 'a.parent_id' => $this->{$this->model}->data_item->parent_id]]], 'hooks' => ['FormattedResultListOnIndex(item_ordering.post_name,. ,item_ordering)']]) : [];
        $this->domain_list = $data['domain_list'] = Modules::run($this->master_app.'/settings/domains/utilityList');

        $this->BuildContentEnv(['form', 'form_elements', 'editor']);
        if ($this->doFormValidation()) {
            if ($this->item_ID > 0 && ($this->item_ID == $this->input->post('parent_id'))) {
                $data['error'] = $this->lang->line('err_post_self_parent');
            } else {
                $this->setUploadFileConfig();
                $img_data = $this->do_upload($this->uploadfile_config, 'post_image_file');
                if (!isset($img_data['error'])) {
                    if (isset($img_data['upload_data'])) {
                        destroy_postvar(['post_image']);
                        build_postvar(['post_image' => $img_data['upload_data']['file_name']]);
                        if ($this->input->post('image_resize') == 1) {
                            $this->do_image_resize($img_data['upload_data']['full_path'], ['width' => $this->input->post('image_width'), 'height' => $this->input->post('image_height')]);
                        }
                    }
                    $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), ['post_name' => '', 'post_alias' => '', 'domain_id' => 0, 'parent_id' => 0, 'item_ordering' => 0, 'post_category_id' => '', 'published' => 1]));
                    if ($item_id) {
                        $data['success'] = $this->lang->line('success_post_save');
                        $this->deleteCache($this->cache_list);
                        if (!$this->item_ID) {
                            $this->session->set_flashdata('flashSuccess', $data['success']);
                            redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                        } else {
                            if (isset($img_data['upload_data']) && $this->{$this->model}->data_item->post_image != '') {
                                $this->deletefile($this->image_dir_path . $this->{$this->model}->data_item->post_image);
                            }
                            $this->{$this->model}->setItem($this->item_ID);
                            $this->item_ordering_list = $data['item_ordering_list'] = $this->get_Items(['filterArr' => ['db_Select' => ['a.id', 'a.item_ordering', 'a.post_name'], 'db_Joins' => [], 'db_Order' => ['a.item_ordering' => 'asc'], 'db_Filters' => ['where' => ['a.post_category_id'=>$this->{$this->model}->data_item->post_category_id, 'a.parent_id' => $this->{$this->model}->data_item->parent_id]]], 'hooks' => ['FormattedResultListOnIndex(item_ordering.post_name,. ,item_ordering)']]);
                        }
                    } else {
                        $data['error'] = $this->lang->line('err_post_save');
                    }
                }
                else {
                    $data['error'] = $this->lang->line('err_post_image').$img_data['error'];
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
        if (!$this->{$this->model}->delete($this->item_ID)) : $this->unauthorized_access();
        endif;
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_post_delete'));
        $this->deleteCache($this->cache_list);
        redirect($this->setAppURL($this->list_link));
    }

    protected function AjaxGridRecordsList($items1) {
        $list_link = $this->setAppURL($this->list_link);
        $edit_link_attr = ['class' => 'btn btn-xs tooltips', 'data-placement' => 'top', 'data-original-title' => $this->lang->line('edit_item_lbl')];
        $delete_link_attr = ['class' => 'btn btn-xs text-danger', 'data-toggle' => 'confirmation', 'data-placement' => 'left', 'data-original-title' => $this->lang->line('remove_item_lbl'), 'data-popout' => 'true'];
        $rowdata = [];
        foreach ($items1 as $k => $v) :
            $encypt_id = $this->encodeData($k);
            $action_links = '<div class="actions">';
            $action_links .= anchor(site_url($list_link . '/edit/' . $encypt_id), '<i class="fa fa-edit"></i>', $edit_link_attr);
            $action_links .= nbs();
            $action_links .= anchor(site_url($list_link . '/delete/' . $encypt_id), '<i class="fa fa-trash"></i>', $delete_link_attr);
            $action_links .= '</div>';

            $rowdata[] = [
                $v->post_name,
                $v->domain,
                $v->category_name,
                display_status_btn($v->published),
                formatDateTime($v->modified_on, $this->display_date_full_frmt),
                $action_links
            ];
        endforeach;
        return $rowdata;
    }
}
