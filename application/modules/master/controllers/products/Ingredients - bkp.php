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
	
    protected $utility_cnfg = [
        'select'=> ['a.id', 'a.ingredient_name'],
        'filters'=> ['where'=>['a.published'=>1]],
        'hooks'=> ['FormattedResultList(ingredient_name)']
    ];

    public function __construct() {
        parent::__construct();
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
        $this->form_validation->set_rules('ingredient_name', 'lang:err_ingredient_name', 'trim|required|min_length[2]|max_length[50]'.$is_unique);
        $this->form_validation->set_rules('published', 'lang:err_published', 'trim|required|in_list[' . implode(',', array_keys($this->boolean_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        return $this->form_validation->run();
    }

	private function arrangeRatingCategoryData($data) {
        $data['rating_category_list'] =  Modules::run($this->master_app.'/products/ingredients/utilityList', ['select'=>['b.id', 'b.category_id', 'b.rating'], 'filters'=> ['where'=>['b.ingredient_id'=>$this->item_ID]], 'hooks'=>[]]);

        if(count($data['rating_category_list']) < 1) : 
            $cnt = (is_array($this->input->post('option_values')))? count($this->input->post('option_values')):1;
            for($i=0;$i<$cnt;$i++) : 
                $data['rating_category_list'][$i] = new stdClass();
                $data['rating_category_list'][$i]->id = 0;
                $data['rating_category_list'][$i]->category_id = $data['rating_category_list'][$i]->rating = 0;
            endfor;
        endif;  
        sort($data['rating_category_list']);
        return $data;    
    }
	
    protected function FormProcess($data) {
        $this->_initEnv();
        $this->{$this->model}->setItem($this->item_ID);
        if ($this->item_ID > 0 && ($this->item_ID != $this->{$this->model}->data_item->id)) :
            $this->unauthorized_access();
        endif;
        $this->BuildContentEnv();
		$data = $this->arrangeRatingCategoryData($data);
		$this->categories_list = $data['categories_list'] = Modules::run($this->master_app.'/products/category/utilityList');
        if ($this->doFormValidation()) :
		//d($this->input->post());
            $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), [ 'ingredient_name'=> '','published' =>1]));
            if ($item_id) :
                $data['success'] = $this->lang->line('success_ingredient_name_save');
                $this->deleteCache($this->tbl . '*');
                if (!$this->item_ID) :             
                    $this->session->set_flashdata('flashSuccess', $data['success']);
                    redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
				else:
					 $data = $this->arrangeRatingCategoryData($data);
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
}
