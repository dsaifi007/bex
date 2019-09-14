<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Questionnaire extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'products';
    protected $list_link = 'products/questionnaire';
    protected $tbl = 'bex_questions';
    
    protected $model_name = 'products/Questionnaire_model';
    protected $model = 'questionnaire_model';
    protected $is_model = 0;
	protected $field_types_list= [];
    protected $options_list =[];
    protected $uploadfile_config = [];
	protected $item_ordering_list = [];
    protected $categories_list = [];
    protected $ques_type_arr = [];
    protected $image_dir = 'options/';

	protected $utility_cnfg = [];
	
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
        $this->uploadfile_config['file_name']     = (random_string('numeric')+time()).random_string();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/questionnaire');
        $this->_loadModelEnv();
		$this->load->helper('string');
    }
    
    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->_initEnv();
        $data['boolean_arr'] = $this->boolean_arr;
        $data['field_types_list'] = $this->config->item('field_types');
		$this->field_types_list = $data['field_types'] = $this->config->item('field_types');
        $data['items'] = $this->get_Items(['hooks'=>['treeOrder']]);
        if (count($data['items']) > 0) :
            $this->BuildContentEnv(['table']);
            $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/questionnaire.js', 'page');
        endif;

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/questionnaire';
        $data['view_class'] = 'questionnaire';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {
        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        $data['preview_image_url'] = media_url('asset_images_folder').'/no-image.jpg';
        $data['image_dir_path'] = media_url('asset_images_folder').$this->image_dir;
        if ($this->item_ID){
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_questionnaire';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');   
        }else {
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_questionnaire';
        }
        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/questionnaire_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/questionnaire_form';
        $data['view_class'] = 'questionnaire_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $is_unique = ($this->item_ID > 0 && strtolower($this->input->post('question')) == strtolower($this->{$this->model}->data_item->question)) ? '' : '|is_unique[' . $this->tbl . '.question]';
        $this->form_validation->set_rules('question', 'lang:err_question', 'trim|required|min_length[2]|max_length[225]'.$is_unique);
        $this->form_validation->set_rules('field_type', 'lang:err_type_name', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->config->item('field_types'))) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('published', 'lang:err_published', 'trim|required|in_list[' . implode(',', array_keys($this->boolean_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        return $this->form_validation->run();
    }

    private function arrangeOptionsData($data) {
        $data['options_list'] =  Modules::run($this->master_app.'/products/questionnaire/utilityList', ['select'=>['b.id', 'b.category_id', 'b.frontend_option','b.backend_option'], 'filters'=> ['where'=>['b.question_id'=>$this->item_ID]], 'hooks'=>[]]);

        if(count($data['options_list']) < 1) : 
            $cnt = (is_array($this->input->post('frontend_option')))? count($this->input->post('frontend_option')):1;
            for($i=0;$i<$cnt;$i++) : 
                $data['options_list'][$i] = new stdClass();
                $data['options_list'][$i]->id = 0;
                $data['options_list'][$i]->category_id = 0;
                $data['options_list'][$i]->frontend_option =  $data['options_list'][$i]->backend_option =  '';
            endfor;
        endif;  
        sort($data['options_list']);
        return $data;    
    }

    protected function FormProcess($data) {
        $this->_initEnv();
        $this->{$this->model}->setItem($this->item_ID);
        if ($this->item_ID > 0 && ($this->item_ID != $this->{$this->model}->data_item->id)) :
            $this->unauthorized_access();
        endif;
		
		$items = $this->get_Items(['hooks'=>['treeOrder']]);
        $this->parent_list = $data['parent_list'] = getParentsList($items, 'question', 1);
        ($this->item_ID)? $this->{$this->model}->setVal('managed_all_items', $items):'';
		
		$this->item_ordering_list = $data['item_ordering_list'] = ($this->item_ID > 0)? $this->get_Items(['filterArr'=>['db_Select'=>['a.id', 'a.item_ordering', 'a.question'], 'db_Joins'=>[], 'db_Order'=>['a.item_ordering'=> 'asc'], 'db_Filters'=>['where'=>['a.parent_id'=>$this->{$this->model}->data_item->parent_id]]], 'hooks'=>['FormattedResultListOnIndexHierarchical(question,. ,item_ordering)']]):[];
		
        $categories_list = Modules::run($this->master_app.'/products/category/utilityList', ['select'=>['a.id', 'a.backend_name','b.category_type_name'], 'hooks'=>[]]);

        $this->ques_type_arr = $data['ques_type_arr'] = [0=>$this->lang->line('ques_text_type_lbl'), 1=>$this->lang->line('ques_category_type_lbl')];

        $category_arr = [];
        if(count($categories_list)>0){
            foreach($categories_list as $k=>$v){
                $category_arr[$v->category_type_name][$v->id] = $v->backend_name;
            }
        }    
        $this->categories_list = $data['categories_list'] = $category_arr;

        $this->BuildContentEnv(['form', 'form_elements', 'editor']);
		$this->field_types_list = $data['field_types_list'] = $this->config->item('field_types');
        $data = $this->arrangeOptionsData($data);
        if ($this->doFormValidation()) :
            //d($this->input->post());
			if($this->item_ID > 0 && ($this->item_ID == $this->input->post('parent_id'))) :
                $data['error'] = $this->lang->line('err_menu_item_self_parent');
            else :
				$this->setUploadFileConfig();
				$img_data = [];
				$files = $_FILES;
				$cpt = count($_FILES['option_image_file']['name']);
				for($i=0; $i<$cpt; $i++){           
					$_FILES['option_image_file']['name']= $files['option_image_file']['name'][$i];
					$_FILES['option_image_file']['type']= $files['option_image_file']['type'][$i];
					$_FILES['option_image_file']['tmp_name']= $files['option_image_file']['tmp_name'][$i];
					$_FILES['option_image_file']['error']= $files['option_image_file']['error'][$i];
					$_FILES['option_image_file']['size']= $files['option_image_file']['size'][$i];    
					$img_data[] = $this->do_upload($this->uploadfile_config, 'option_image_file'); 
				}
				$img_data= array_filter($img_data);
				$post_fields = ['question'=> '', 'field_type'=>0, 'ques_type'=>0, 'required'=>1, 'tips'=>'', 'parent_id'=>0, 'item_ordering'=>0, 'published' =>1];
                build_postvar(['img_data'=>$img_data]);

				$item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), $post_fields));

				if ($item_id) :
					/*if(!empty($img_data)){
						$this->db->delete('bex_question_options', array('question_id' => $item_id)); 
						if ($item_id) :     
							foreach($img_data as $img){
								$this->db->trans_start();
								$this->db->insert('bex_question_options', [
									'question_id'=>$item_id,
									'category_id'=>$this->input->post('category_id'),
									'frontend_option'=>$img['upload_data']['file_name'],
									'backend_option'=>'',
								]);
								 $this->db->trans_complete();
							}
						endif;    
					}*/
					$data['success'] = $this->lang->line('success_questionnaire_save');
					$this->deleteCache($this->tbl . '*');
					if (!$this->item_ID) :          
						$this->session->set_flashdata('flashSuccess', $data['success']);
						redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
					else:
						$data = $this->arrangeOptionsData($data);   
						$this->{$this->model}->setItem($this->item_ID);
						$this->item_ordering_list = $data['item_ordering_list'] = $this->get_Items(['filterArr'=>['db_Select'=>['a.id', 'a.item_ordering', 'a.question'], 'db_Joins'=>[], 'db_Order'=>['a.item_ordering'=> 'asc'], 'db_Filters'=>['where'=>['a.parent_id'=>$this->{$this->model}->data_item->parent_id]]], 'hooks'=>['FormattedResultListOnIndexHierarchical(question,. ,item_ordering)']]);
					endif;
				else : $data['error'] = $this->lang->line('err_questionnaire_save');
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
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_questionnaire_delete'));
        $this->deleteCache($this->tbl . '*');
        redirect($this->setAppURL($this->list_link));
    }
}
