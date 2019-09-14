<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Text_widgets extends AdminMaster {

    protected $item_ID;
    protected $parent_dir = 'components';
    protected $list_link = 'components/text_widgets';
    protected $tbl = 'text_widgets';
    
    protected $model_name = 'components/Text_widget_model';
    protected $model = 'text_widget_model';
    protected $is_model = 0;

    protected $utility_cnfg = [
        'select'=> ['a.id', 'a.widget_name'],
        'filters'=> ['where'=>['a.published'=>1, 'b.published'=>1]],
        'hooks'=> ['FormattedResultList(widget_name)']
    ];
    protected $acl_btn_list = [
        'remove_btn'=>['mthd'=>'', 'func'=>'text_widgets_delete_btn', 'actn'=>'view', 'rdrct'=>0],
        'add_btn'=>['mthd'=>0, 'func'=>'text_widgets_add_btn', 'actn'=>'common', 'rdrct'=>0]
    ];
    protected $domain_list = [];

    public function __construct() {
        parent::__construct();
    }

    private function _initEnv() {
        $this->lang->load($this->parent_dir.'/text_widgets');
        $this->_loadModelEnv();
    }
    
    public function index($id=0) {
        //d($id);
        $data['error'] = ($this->session->flashdata('flashError')) ? $this->session->flashdata('flashError') : '';
        $data['success'] = ($this->session->flashdata('flashSuccess')) ? $this->session->flashdata('flashSuccess') : '';
        $this->_initEnv();
		
        $data['items'] = $this->get_Items();
        if (count($data['items']) > 0) :
            $this->BuildContentEnv(['table']);
            $this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/text_widgets.js', 'page');
        endif;

        $data['list_link'] = $this->setAppURL($this->list_link);
        $data['view_name'] = $this->parent_dir.'/text_widgets';
        $data['view_class'] = 'text_widgets';
        $this->displayView($data);
    }

    private function _initBasicFormEnv($data) {

        $data['item'] = $this->{$this->model}->data_item;
        $data['boolean_arr'] = $this->boolean_arr;
        $data['add_link'] = '';
        if ($this->item_ID) :
            $data['form_action'] = $this->list_link . '/edit/' . $this->encodeData($this->item_ID);
            $lang = 'edit_text_widget';
            $data['add_link'] = $this->setAppURL($this->list_link.'/add');
        else :
            $data['form_action'] = $this->list_link . '/add';
            $lang = 'add_text_widget';
        endif;
        $this->lang->load($this->parent_dir.'/'.$lang);
        $data['form_action'] = $this->setAppURL($data['form_action']);
        $data['list_link'] = $this->setAppURL($this->list_link);
        //$data = $this->BuildAdminEnv($data);
		$this->minify->add_js($this->th_custom_js_path.$this->parent_dir.'/text_widget_form.js', 'page');
        $data['view_name'] = $this->parent_dir.'/text_widget_form';
        $data['view_class'] = 'text_widget_form_blk';
        return $data;
    }

    protected function doFormValidation() {
        $this->BuildFormValidationEnv();
		$is_unique = ($this->item_ID > 0 && strtolower($this->input->post('widget_name')) == strtolower($this->{$this->model}->data_item->widget_name)) ? '' : '|is_unique[' . $this->tbl . '.widget_name]';
        $this->form_validation->set_rules('widget_name', 'lang:err_widget_name', 'trim|required|min_length[2]|max_length[100]'.$is_unique);
        $this->form_validation->set_rules('widget_text', 'lang:err_widget_text', 'trim|required|min_length[5]');
        $this->form_validation->set_rules('domain_id', 'lang:err_widget_domain', 'trim|required|numeric|in_list[' . implode(',', array_keys($this->domain_list)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        $this->form_validation->set_rules('published', 'lang:err_published', 'trim|required|in_list[' . implode(',', array_keys($this->boolean_arr)) . ']', ['in_list' => $this->lang->line('err_in_list')]);
        return $this->form_validation->run();
    }

    protected function FormProcess($data) {
        $this->_initEnv();
        $this->{$this->model}->setItem($this->item_ID);
        if ($this->item_ID > 0 && ($this->item_ID != $this->{$this->model}->data_item->id)) :
            $this->unauthorized_access();
        endif;
        $this->domain_list = $data['domain_list'] = Modules::run($this->master_app.'/settings/domains/utilityList');
        $this->BuildContentEnv(['form', 'form_elements', 'editor']);

        if ($this->doFormValidation()) :
            $item_id = $this->{$this->model}->save(arrange_post_data($this->input->post(['widget_name','domain_id','published'])+['widget_text'=>$this->input->post('widget_text', FALSE)], ['widget_name' => '', 'widget_text'=>'', 'domain_id'=>0, 'published' => 1]));
            if ($item_id) :
                $data['success'] = $this->lang->line('success_widget_text_save');
                $this->deleteCache($this->cache_list);
                if (!$this->item_ID) :
                    $this->session->set_flashdata('flashSuccess', $data['success']);
                    redirect($this->setAppURL($this->list_link . '/edit/' . $this->encodeData($item_id)));
                endif;
            else : $data['error'] = $this->lang->line('err_text_widget_save');
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
        $this->session->set_flashdata('flashSuccess', $this->lang->line('success_widget_text_delete'));
        $this->deleteCache($this->cache_list);
        redirect($this->setAppURL($this->list_link));
    }

}
