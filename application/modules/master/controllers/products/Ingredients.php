<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Ingredients extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'products';
    protected $list_link = 'products/ingredients';
    protected $tbl = 'bex_product_ingredients';
    
    protected $model_name = 'products/Ingredients_model';
    protected $model = 'ingredients_model';
    protected $is_model = 0;
	protected $categories_list =[];
    protected $uploadfile_config = [];
	protected $file_dir = 'ingredients/';

    protected $utility_cnfg = [
        'select'=> ['a.id', 'a.ingredient_name'],
        'filters'=> ['where'=>['a.published'=>1]],
        'hooks'=> ['FormattedResultList(ingredient_name)']
    ];

    public function __construct() {
        parent::__construct();
        $this->load->library('csvimport');
        $this->file_dir_path = media_path('asset_files_folder').$this->file_dir;
        $this->file_dir_url = media_url('asset_files_folder').$this->file_dir;
    }

    protected function setUploadFileConfig() {
        $this->load->helper('string');
        $this->uploadfile_config['upload_path']   = $this->file_dir_path;
        $this->uploadfile_config['allowed_types'] = 'csv';
        $this->uploadfile_config['max_size']      = $this->config->item('max_upload_limit_files'); // KB
        $this->uploadfile_config['file_name']     = (random_string('numeric')+time()).random_string();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/ingredients');
        $this->_loadModelEnv();
    }
    
    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->_initEnv();

        $data['items'] = $this->get_Items();
        if (count($data['items']) > 0) :
            $this->BuildContentEnv(['table']);
            $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/ingredients.js', 'page');
        endif;

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/ingredients';
        $data['view_class'] = 'ingredients';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {
        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if ($this->item_ID) :
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_ingredient';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_ingredient';
        endif;
        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/ingredient_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/ingredient_form';
        $data['view_class'] = 'ingredient_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $is_unique = ($this->item_ID > 0 && strtolower($this->input->post('ingredient_name')) == strtolower($this->{$this->model}->data_item->ingredient_name)) ? '' : '|is_unique[' . $this->tbl . '.ingredient_name]';
        $this->form_validation->set_rules('ingredient_name', 'lang:err_ingredient_name', 'trim|required|min_length[2]|max_length[250]'.$is_unique);
        $this->form_validation->set_rules('published', 'lang:err_published', 'trim|required|in_list[' . implode(',', array_keys($this->boolean_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        return $this->form_validation->run();
    }
	
    protected function FormProcess($data) {
        $this->_initEnv();
        $this->{$this->model}->setItem($this->item_ID);
        if ($this->item_ID > 0 && ($this->item_ID != $this->{$this->model}->data_item->id)) :
            $this->unauthorized_access();
        endif;
        $this->BuildContentEnv();
		$this->skin_type_list = $data['skin_type_list'] = Modules::run($this->master_app.'/products/category/utilityList', ['select'=>['a.id', 'a.backend_name'], 'filters'=> ['where'=>['a.category_type_id'=>1, 'a.published'=>1]], 'hooks'=> ['FormattedResultList(backend_name)']]);
		
		$this->skin_concern_list = $data['skin_concern_list'] = Modules::run($this->master_app.'/products/category/utilityList', ['select'=>['a.id', 'a.backend_name'], 'filters'=> ['where'=>['a.category_type_id'=>2, 'a.published'=>1]],'hooks'=> ['FormattedResultList(backend_name)']]);

        if ($this->doFormValidation()) :
		//d($this->input->post());
            $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), [ 'ingredient_name'=> '','published' =>1]));
            if ($item_id) :
                $data['success'] = $this->lang->line('success_ingredient_name_save');
                $this->deleteCache($this->tbl . '*');
                if (!$this->item_ID) :             
                    $this->session->set_flashdata('flashSuccess', $data['success']);
                    redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                endif;
            else : $data['error'] = $this->lang->line('err_ingredient_name_save');
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
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_ingredient_name_delete'));
        $this->deleteCache($this->tbl . '*');
        redirect($this->setAppURL($this->list_link));
    }

    public function importcsv() {
        $data['error'] = '';
        $data['success'] = '';
        $this->_FormImportCSV($data);
    }

    private function _initBasicFormCSVEnv($data) {
        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['form_action'] = $this->setAppURL($this->list_link . '/importcsv');
        $data['view_name'] = $this->parent_dir.'/importcsv';
        $data['view_class'] = 'importcsv_blk';
        return $data;
    }

    protected function _FormImportCSV($data) {
        $this->_initEnv();
        $this->BuildContentEnv(['form', 'form_elements']);
        $this->setUploadFileConfig();
        $file_data = $this->do_upload($this->uploadfile_config, 'ingredient_csv_file');
		$categories_list = Modules::run($this->master_app.'/products/category/utilityList', ['select'=>['a.id', 'a.backend_name'], 'filters'=> ['where_in'=> ['a.category_type_id'=>[1,2]], 'where'=>[ 'a.published'=>1]], 'hooks'=> []]);
		$ingredients_list = Modules::run($this->master_app.'/products/ingredients/utilityList', ['select'=>['a.id','a.ingredient_name'], 'filters'=> ['where'=>[ 'a.published'=>1]], 'hooks'=> []]);
		$ingredients = [];
		if(count($ingredients_list) > 0){
			foreach($ingredients_list as $v){
				$ingredients[$v->id] = $v->ingredient_name;
			}	
		}

        if(!isset($file_data['error'])) :
            if(isset($file_data['upload_data'])) :
                $file_path = $file_data['upload_data']['full_path'];
				if($this->csvimport->get_array($file_path)) {
                    $csv_array = $this->csvimport->get_array($file_path);
                    foreach ($csv_array as $row) {
						if(in_array(strtolower($row['Ingredient']), array_map("strtolower", $ingredients))) { continue; }
						$this->db->insert('bex_product_ingredients', [
							'ingredient_name'=>$row['Ingredient'] ,
							'published'=>1,
							'modified_by'=>388,
							'modified_on'=>date('Y-m-d H:i:s'),
						]);
						$item_id = $this->db->insert_id();	
						foreach ($categories_list as $row1) {
							$this->db->trans_start();
							$this->db->insert('bex_product_ingredients_rating', [
								'ingredient_id'=>$item_id ,
								'category_id'=>$row1->id,
								'rating'=>(isset($row[$row1->backend_name])) ? $row[$row1->backend_name] : '',
							]);
							$this->db->trans_complete();
						}	
					}
					$this->deleteCache($this->tbl . '*');
					$data['success'] = $this->lang->line('success_ingredient_import');
					$this->session->set_flashdata('flashSuccess', $data['success']);
					redirect($this->setAppURL($this->list_link));
                } 
            endif;
        else :
            $data['error'] = $this->lang->line('err_file'). $file_data['error'];
        endif;
		$lang= 'import_ingredient_csv';
		$this->lang->load($this->parent_dir.'/'.$lang);
        $data = $this->_initBasicFormCSVEnv($data);
        $this->displayView($data);
    } 
 
}
