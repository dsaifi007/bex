<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Teams extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'sports';
    protected $list_link = 'sports/teams';
    protected $tbl = 'sports_teams';
    
    protected $model_name = 'sports/Team_model';
    protected $model = 'team_model';
    protected $is_model = 0;

    protected $utility_cnfg = [
        'select'=> ['a.id', 'a.team_name', 'a.country_code'],
        'filters'=> ['where'=>['a.published'=>1, 'b.published'=>1]],
        'hooks'=> ['FormattedResultList(team_name.country_code)']
    ];
    protected $acl_btn_list = [
        'remove_btn'=>['mthd'=>'', 'func'=>'teams_delete_btn', 'actn'=>'view', 'rdrct'=>0]
    ];
    protected $league_types_list = [];
    protected $countries_list = [];

    protected $image_dir = 'sports/teams/';
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
        $this->uploadfile_config['file_name']  = (random_string('numeric')+time()).random_string();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/teams');
        $this->_loadModelEnv();
    }
    
    public function index() {
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->_initEnv();

        $data['items'] = $this->get_Items();
        if (count($data['items']) > 0) :
            $this->BuildContentEnv(['table']);
            $data['countries_list'] = $this->config->item('countries_list');
            $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/teams.js', 'page');
        endif;

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/teams';
        $data['view_class'] = 'teams';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        $data['preview_image_url'] = media_url('asset_images_folder').'/no-image.jpg';
        if ($this->item_ID) {
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_team';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
            if ($this->{$this->model}->data_item->item_image != '' && file_exists($this->image_dir_path . $this->{$this->model}->data_item->item_image)) {
                $data['preview_image_url'] = $this->image_dir_url . $this->{$this->model}->data_item->item_image;
            }
        }
        else {
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_team';
        }
       
        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        //$data = $this->BuildAdminEnv($data);
        $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/team_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/team_form';
        $data['view_class'] = 'team_form_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
        $multi_unique_param = [
            'id' => $this->item_ID,
            'fields' => ['team_name', 'league_type_id'],
            'table' => $this->tbl
        ];
        $this->form_validation->set_rules('team_name', 'lang:err_team_name', 'trim|required|min_length[2]|max_length[255]|multi_is_unique['.json_encode($multi_unique_param) . ']');
        $this->form_validation->set_rules('league_type_id', 'lang:err_team_league_type_id', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->league_types_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('country_code', 'lang:err_league_country_code', 'trim|required|in_list[' . implode(',', array_keys($this->countries_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);

        $required_dimension = ($this->input->post('image_resize') == 1)? '|required':'';
        $this->form_validation->set_rules('image_width', 'lang:err_image_width', 'trim|numeric|less_than_equal_to[5000]|greater_than_equal_to[0]'.$required_dimension);
        $this->form_validation->set_rules('image_height', 'lang:err_image_height', 'trim|numeric|less_than_equal_to[5000]|greater_than_equal_to[0]'.$required_dimension);
        $this->form_validation->set_rules('image_resize', 'lang:err_image_resize', 'trim|numeric|required|in_list[' . implode(',', array_keys($this->boolean_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        
        $this->form_validation->set_rules('published', 'lang:err_published', 'trim|required|in_list[' . implode(',', array_keys($this->boolean_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        return $this->form_validation->run();
    }

    protected function FormProcess($data) {
        $this->_initEnv();
        $this->{$this->model}->setItem($this->item_ID);
        if ($this->item_ID > 0 && ($this->item_ID != $this->{$this->model}->data_item->id)) :
            $this->unauthorized_access();
        endif;

        $this->countries_list = $data['countries_list'] = $this->config->item('countries_list');
        $this->league_types_list = $data['league_types_list'] = Modules::run($this->master_app.'/sports/league_types/utilityList');

        $this->BuildContentEnv();
        if ($this->doFormValidation()) {
          $this->setUploadFileConfig();
            $img_data = $this->do_upload($this->uploadfile_config, 'item_image');
            if(!isset($img_data['error']))
            {
                if (isset($img_data['upload_data'])) {
                    build_postvar(['item_image' => $img_data['upload_data']['file_name']]);
                    if ($this->input->post('image_resize') == 1) {
                        $this->do_image_resize($img_data['upload_data']['full_path'], ['width' => $this->input->post('image_width'), 'height' => $this->input->post('image_height')]);
                    }
                }
                $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(), ['team_name'=> '', 'league_type_id'=>0, 'country_code'=>'','item_image'=>'', 'published' =>1]));
                if ($item_id) {
                    $data['success'] = $this->lang->line('success_team_save');
                    $this->deleteCache($this->cache_list);
                    if (!$this->item_ID) {
                        $this->session->set_flashdata('flashSuccess', $data['success']);
                        redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                    }
                    else {
                        if (isset($img_data['upload_data']) && $this->{$this->model}->data_item->item_image != '') {
                            $this->deletefile($this->image_dir_path . $this->{$this->model}->data_item->item_image);
                        }
                        $this->{$this->model}->setItem($this->item_ID);
                    }
                }
                else { $data['error'] = $this->lang->line('err_team_save');}
            }
            else{
                $data['error'] = $this->lang->line('err_team_logo').$img_data['error'];
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
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_team_delete'));
        $this->deleteCache($this->cache_list);
        redirect($this->setAppURL($this->list_link));
    }

}
