<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$date_frmt = $this->display_date_frmt;
$table_heads = [
    ['data' => $this->lang->line('tb_hd_menu_item_title'), 'class' => 'left', 'width' => '35%'],
    ['data' => $this->lang->line('tb_hd_menu_item_type'), 'class' => 'left hidden-xs', 'width' => '15%'],
    ['data' => $this->lang->line('tb_hd_menu_item_acl_level'), 'class' => 'left hidden-xs', 'width' => '10%'],
    ['data' => $this->lang->line('tb_hd_status'), 'class' => 'text-center', 'width' => '10%'],
    ['data' => $this->lang->line('tb_hd_modified_on'), 'class' => 'text-center hidden-xs', 'width' => '20%'],
    ['data' => $this->lang->line('tb_hd_actions'), 'class' => 'text-center', 'width' => '10%']
];

$template_active = ['table_open' => '<table class="table table-striped table-bordered table-hover menu_items_list">'];
$edit_link_attr = ['class' => 'btn btn-xs tooltips', 'data-placement' => 'top', 'data-original-title' => $this->lang->line('edit_item_lbl')];
$delete_link_attr = ['class' => 'btn btn-xs text-danger', 'data-toggle' => 'confirmation', 'data-placement' => 'left', 'data-original-title' => $this->lang->line('remove_item_lbl'), 'data-popout' => 'true'];

$add_btn = ($add_btn)? display_form_links($list_link . '/add', $this->lang->line('add_new_item_lbl')):'';
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <?php echo display_message_info(['0' => $error, '1' => $success]); ?>
            <?php echo display_portlet_title($this->lang->line('table_head_label'), $add_btn); ?>
            <div class="portlet-body">
                <?php
                if (count($items) > 0) : 
                    $this->table->set_template($template_active);
                    $this->table->set_heading($table_heads);
                    foreach ($items as $k => $v) : 
                        if(!authenticate_acl_action_item_level($this->hook_data->access_levels_arr, $v->acl_level_id, $v->user_groups, $this->userdata->user_groups)) : continue; endif; 
                        $encypt_id = encodeData($k);
                        
                        $rowdata = []; $seprator = ($v->sep_cnt > 0)? repeater('&nbsp;', 3*$v->sep_cnt).repeater('---', 1).' ':'';
                        $rowdata[] = ['data' => $seprator.$v->menu_title];
                        $rowdata[] = ['data' => $v->menu_type_title, 'class'=> 'hidden-xs'];
                        $rowdata[] = ['data' => $v->acl_level, 'class'=> 'hidden-xs'];
                        $rowdata[] = ['data' => display_status_btn($v->published), 'class' => 'text-center'];
                        
                        $rowdata[] = ['data' => formatDateTime($v->modified_on, $this->display_date_full_frmt), 'class' => 'text-center hidden-xs'];
                        $action_links = '<div class="actions">';
                            $action_links .= anchor(site_url($list_link.'/edit/' . $encypt_id), '<i class="fa fa-edit"></i>', $edit_link_attr);
                            $action_links .= nbs();
                            $action_links .= ($remove_btn)? anchor(site_url($list_link.'/delete/' . $encypt_id), '<i class="fa fa-trash"></i>', $delete_link_attr):'';
                        $action_links .= '</div>';
                        $rowdata[] = ['data' => $action_links, 'class' => 'text-center'];
                        $this->table->add_row($rowdata);
                    endforeach;
                    echo $this->table->generate();
                else : echo display_message_info([0 => $this->lang->line('no_items_label')]);
                endif;
                ?>
            </div>
        </div>
    </div>
</div>