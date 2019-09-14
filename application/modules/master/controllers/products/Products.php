<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'products';
    protected $list_link = 'products/products';
    protected $tbl = 'bex_products';
    
    protected $model_name = 'products/Products_model';
    protected $model = 'products_model';
    protected $is_model = 0;
    protected $ingredients_list =[];
    protected $brands_name=[];
    protected $category_list=[];
    protected $uploadfile_config = [];
    protected $image_dir = 'products/';

    protected $utility_cnfg = [
        'select'=> ['a.id', 'a.product_name'],
        'filters'=> ['where'=>['a.published'=>1]],
        'hooks'=> ['FormattedResultList(product_name)']
    ];

    public function __construct() {
        parent::__construct();
        $this->image_dir_path = media_path('asset_images_folder').$this->image_dir;
        $this->image_dir_url = media_url('asset_images_folder').$this->image_dir;
    }

    protected function setUploadFileConfig() {
        $this->load->helper('string');
        $this->uploadfile_config['upload_path']   = $this->image_dir_path;
        $this->uploadfile_config['allowed_types'] = 'gif|png|jpg';
        $this->uploadfile_config['max_size']      = $this->config->item('max_upload_limit_files'); // KB
        $this->uploadfile_config['max_width']     = $this->config->item('max_image_width');
        $this->uploadfile_config['max_height']    = $this->config->item('max_image_height');
        //$this->uploadfile_config['file_ext_tolower']     = TRUE;
        $this->uploadfile_config['file_name']     = (random_string('numeric')+time()).random_string();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/products');
        $this->_loadModelEnv();
    }
    
    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->_initEnv();
        $data['boolean_arr'] = $this->boolean_arr;
        $data['items'] = $this->get_Items();
        $this->brands_name = $data['brand_name'] = Modules::run($this->master_app.'/products/brands/utilityList');
        if (count($data['items']) > 0) :
            $this->BuildContentEnv(['table']);
            $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/products.js', 'page');
        endif;

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/products';
        $data['view_class'] = 'products';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {
        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        $data['preview_image_url'] = media_url('asset_images_folder').'/no-image.jpg';
        if ($this->item_ID) {
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_product';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
            if($this->{$this->model}->data_item->product_image != '' && file_exists($this->image_dir_path.$this->{$this->model}->data_item->product_image)) {
                $data['preview_image_url'] = $this->image_dir_url.$this->{$this->model}->data_item->product_image;
            }
        }else{
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_product';
        }
        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/product_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/product_form';
        $data['view_class'] = 'product_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $is_unique = ($this->item_ID > 0 && strtolower($this->input->post('product_name')) == strtolower($this->{$this->model}->data_item->product_name)) ? '' : '|is_unique[' . $this->tbl . '.product_name]';
        $this->form_validation->set_rules('product_name', 'lang:err_product_name', 'trim|required|min_length[2]|max_length[150]'.$is_unique);
        $this->form_validation->set_rules('brands_id', 'lang:err_brand_id', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->brands_name)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('description', 'lang:err_product_desc', 'trim|required|min_length[2]|max_length[1000]');
        $this->form_validation->set_rules('toxic', 'lang:err_product_toxic', 'trim|required|in_list[' . implode(',', array_keys($this->boolean_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $required_dimension = ($this->input->post('product_image_resize') == 1)? '|required':'';
        $this->form_validation->set_rules('product_image_width', 'lang:err_product_image_width', 'trim|numeric|less_than_equal_to[5000]|greater_than_equal_to[0]'.$required_dimension);
        $this->form_validation->set_rules('product_image_height', 'lang:err_product_image_height', 'trim|numeric|less_than_equal_to[5000]|greater_than_equal_to[0]'.$required_dimension);
        $this->form_validation->set_rules('product_image_resize', 'lang:err_product_image_resize', 'trim|required|in_list[' . implode(',', array_keys($this->boolean_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('published', 'lang:err_published', 'trim|required|in_list[' . implode(',', array_keys($this->boolean_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        return $this->form_validation->run();
    }

    protected function FormProcess($data) {
        $this->_initEnv();
        $this->{$this->model}->setItem($this->item_ID);
        if ($this->item_ID > 0 && ($this->item_ID != $this->{$this->model}->data_item->id)) :
            $this->unauthorized_access();
        endif;
        $data['ingradiants_data']=$this->{$this->model}->productdes_ingradiants(); 
        $this->BuildContentEnv(['form', 'form_elements', 'editor']);
        $this->ingredients_list = $data['ingredients_list'] = Modules::run($this->master_app.'/products/ingredients/utilityList');
        $this->brands_name = $data['brand_name'] = Modules::run($this->master_app.'/products/brands/utilityList');
        $this->category_list = $data['category_list'] = Modules::run($this->master_app.'/products/category/utilityList');
        if ($this->doFormValidation()) :
            $this->setUploadFileConfig();
         
            $default_items = ['brands_id'=>'', 'product_name'=> '', 'product_url'=>'', 'description'=>'', 'ingredients'=>'', 'toxic'=>0, 'product_image_width'=>0, 'product_image_height'=>0, 'published' =>1];
            $img_data = $this->do_upload($this->uploadfile_config, 'product_image_file');

            if(!isset($img_data['error'])) :
                if(isset($img_data['upload_data'])) :
                    build_postvar(['product_image' => $img_data['upload_data']['file_name']]);
                    $default_items += ['product_image'=>''];
                    if($this->input->post('product_image_resize') == 1) {
                        $this->do_image_resize($img_data['upload_data']['full_path'], ['width'=>$this->input->post('product_image_width'), 'height'=>$this->input->post('product_image_height')]);
                    }
                    endif;
                $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), $default_items));
                if ($item_id) :
                    $data['success'] = $this->lang->line('success_product_save');
                    $this->deleteCache($this->tbl . '*');
                    if (!$this->item_ID) :                                 
                        $this->session->set_flashdata('flashSuccess', $data['success']);
                        redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                    else:
                        if(isset($img_data['upload_data']) && $this->{$this->model}->data_item->product_image 
                            !='') {
                            $this->deletefile($this->image_dir_path . $this->{$this->model}->data_item->product_image);
                        }
                        $this->{$this->model}->setItem($this->item_ID);    
                    endif;
                else : $data['error'] = $this->lang->line('err_product_save');
                endif;
            else :
                $data['error'] = $this->lang->line('err_product_image').$img_data['error'];
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
        if (!$this->{$this->model}->delete($this->item_ID)) : $this->unauthorized_access();
        endif;
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_product_delete'));
        $this->deleteCache($this->tbl . '*');
        redirect($this->setAppURL($this->list_link));
    }
    public function abcd()
    {
        $this->_initEnv();
        $result=$this->{$this->model}->ingrd_rating(); 
        d($result);
    }
}
