<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'shop';
    protected $list_link = 'shop/products';
    protected $tbl = 'shop_products';
    
    protected $model_name = 'shop/Product_model';
    protected $model = 'product_model';
    protected $is_model = 0;
	protected $manufacturer_list = [];
	protected $categories_list = [];
    protected $domain_list = [];

    protected $uploadfile_config = [];

    protected $product_dir = 'cwa/products/';
    protected $product_dir_path = '';
    protected $product_dir_url = '';

    public function __construct() {
        parent::__construct();
        $this->product_dir_path = media_path('asset_images_folder').$this->product_dir;
        $this->product_dir_url = media_url('asset_images_folder').$this->product_dir;
    }

    protected function setUploadFileConfig() {
        $this->load->helper('string');
        $this->uploadfile_config['upload_path']          = $this->product_dir_path;
        $this->uploadfile_config['allowed_types']        = 'gif|png|jpg';
        $this->uploadfile_config['max_size']             = $this->config->item('max_upload_limit_files'); // KB
        $this->uploadfile_config['max_width']            = $this->config->item('max_image_width');
        $this->uploadfile_config['max_height']           = $this->config->item('max_image_height');
        //$this->uploadfile_config['file_ext_tolower']     = TRUE;
        $this->uploadfile_config['file_name']            = (random_string('numeric')+time()).random_string();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/products');
        $this->_loadModelEnv();
    }
    
    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->_initEnv();
		
        $data['items'] = $this->get_Items();
        if (count($data['items']) > 0) :
            $this->BuildContentEnv(['table']);
            $data['domain_list'] = Modules::run($this->master_app.'/settings/domains/utilityDomainsList');
			$data['manufacturer_list'] = Modules::run($this->master_app.'/shop/manufacturers/utilityManufacturersList');
			$data['categories_list'] = Modules::run($this->master_app.'/shop/Categories/utilityProductsCategoriesList');
			$this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/products.js', 'page');
        endif;

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/Products';
        $data['view_class'] = 'products';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        $data['preview_image_url'] = media_url('asset_images_folder').$this->current_app.'/no-image.jpg';
        if ($this->item_ID) {
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_product';
            $data['add_link'] = $this->setAppURL($this->list_link . '/add');
            if ($this->{$this->model}->data_item->product_image != '' && file_exists($this->product_dir_path . $this->{$this->model}->data_item->product_image)) {
                $data['preview_image_url'] = $this->product_dir_url . $this->{$this->model}->data_item->product_image;
            }
        }
        else {
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_product';
        }
        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        //$data = $this->BuildAdminEnv($data);
		$this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/product_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/Product_form';
        $data['view_class'] = 'product_form_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
		$multi_unique_param = [
            'id' => $this->item_ID,
            'fields' => ['product_name', 'product_sku', 'product_category_id'],
            'table' => $this->tbl
        ];
		$this->form_validation->set_rules('product_name', 'lang:err_product_name', 'trim|required|min_length[2]|max_length[255]multi_is_unique[' . json_encode($multi_unique_param) . ']');
        $this->form_validation->set_rules('product_description', 'lang:err_product_description', 'trim|required|min_length[4]' );
        $is_unique = ($this->item_ID > 0 && $this->input->post('product_sku') == $this->{$this->model}->data_item->product_sku) ? '' : '|is_unique[' . $this->tbl . '.product_sku]';
        $this->form_validation->set_rules('product_sku', 'lang:err_product_sku', 'trim|min_length[2]|max_length[50]'.$is_unique);
        $this->form_validation->set_rules('product_model_no', 'lang:err_product_model_no','trim|required|min_length[2]|max_length[30]');
	    $this->form_validation->set_rules('product_quality_certification', 'lang:err_product_quality_certification','trim|required|min_length[2]|max_length[155]');      
		$this->form_validation->set_rules('product_manfacturer_id', 'lang:err_manufacturer','trim|required|in_list['.implode(',',array_keys($this->manufacturer_list)).']', ['in_list'=>$this->lang->line('err_in_list')]);
		$this->form_validation->set_rules('product_category_id', 'lang:err_category','trim|required|in_list['.implode(',',array_keys($this->categories_list)).']', ['in_list'=>$this->lang->line('err_in_list')]);

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
        //$this->domain_list = $data['domain_list'] = Modules::run($this->master_app.'/settings/domains/utilityDomainsList');
        $this->manufacturer_list = $data['manufacturer_list'] = Modules::run($this->master_app.'/shop/manufacturers/utilityManufacturersList');
		$this->categories_list = $data['categories_list'] = Modules::run($this->master_app.'/shop/Categories/utilityProductsCategoriesList');
		
		$this->BuildContentEnv(['form', 'form_elements', 'editor']);
        if ($this->doFormValidation()) :

            $this->setUploadFileConfig();
            $default_items = ['product_name'=> '', 'product_description'=>'', 'product_weight'=>'', 'product_sku'=>'', 'product_model_no'=>'', 'product_manfacturer_id'=>'', 'product_category_id'=>'', 'product_quality_certification'=>'',	'published' => 1];
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
                    else :
                        if(isset($img_data['upload_data']) && $this->{$this->model}->data_item->product_image !='') {
                            $this->deletefile($this->product_dir_path . $this->{$this->model}->data_item->product_image);
                        }
                        $this->{$this->model}->setItem($this->item_ID);
                    endif;
                else : $data['error'] = $this->lang->line('err_product_save');
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
        if (!$this->{$this->model}->delete($this->item_ID)) : $this->unauthorized_access();
        endif;
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_product_delete'));
        $this->deleteCache($this->tbl . '*');
        redirect($this->setAppURL($this->list_link));
    }
    
    public function utilityProductList() {
        return $this->get_Items(['filterArr'=>['db_Select'=>['a.id', 'a.product_name'], 'db_Filters'=>['where'=>['a.published'=>1]]], 'hooks'=>['FormattedResultList(product_name)']]);
    }

}
