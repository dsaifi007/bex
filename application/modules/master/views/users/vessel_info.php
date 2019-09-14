<?php
defined('BASEPATH') OR exit('No direct script access allowed');

    $table_heads = [
        ['data' => $this->lang->line('tb_hd_vessel_name'), 'class' => 'left', 'width' => '30%'],
        ['data' => $this->lang->line('tb_hd_vessel_style'), 'class' => 'left', 'width' => '15%'],
        ['data' => $this->lang->line('tb_hd_vessel_manufacturer'), 'class' => 'left', 'width' => '15%'],
        ['data' => $this->lang->line('tb_hd_vessel_drive_type'), 'class' => 'left hidden-xs', 'width' => '10%'],
        ['data' => $this->lang->line('tb_hd_status'), 'class' => 'text-center', 'width' => '10%'],
        ['data' => $this->lang->line('tb_hd_actions'), 'class' => 'text-center', 'width' => '20%']
    ];

    $template_active = ['table_open' => '<table class="table table-condensed table-striped table-bordered table-hover vessels_list">'];
    $view_link_attr = ['class' => 'btn btn-xs tooltips', 'data-placement' => 'top', 'data-original-title' => $this->lang->line('view_item_lbl')];
    $edit_link_attr = ['class' => 'btn btn-xs tooltips', 'data-placement' => 'top', 'data-original-title' => $this->lang->line('edit_item_lbl')];
    $delete_link_attr = ['class' => 'btn btn-xs text-danger', 'data-toggle' => 'confirmation', 'data-placement' => 'left', 'data-original-title' => $this->lang->line('remove_item_lbl'), 'data-popout' => 'true'];
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <?php echo display_message_info(['0' => $error, '1' => $success]); ?>
            <?php echo display_portlet_title($this->lang->line('table_vessel_head_label'), display_form_links($vessels_list_link . '/add/user/'.encodeData($item->id), $this->lang->line('add_new_vessel_item_lbl'))); ?>
                <div class="portlet-body">
                    <?php
                        if (count($vessels_items) > 0) :
                            $this->table->set_template($template_active);
                            $this->table->set_heading($table_heads);
                            foreach ($vessels_items as $k => $v) :
                                $encypt_id = encodeData($k);
                                
                                $rowdata = [];
                                $rowdata[] = ['data' => $v->vessel_name];
                                $rowdata[] = ['data' => $v->style_name];
                                $rowdata[] = ['data' => $v->manufacturer_name];
                                $rowdata[] = ['data' => $v->drive_type_name];
                                $rowdata[] = ['data' => display_status_btn($v->published), 'class' => 'text-center'];
                                $action_links = '<div class="actions">';
                                $action_links .= anchor(site_url($vessels_list_link . '/view/' . $encypt_id), '<i class="fa fa-search"></i>', $view_link_attr);
                                $action_links .= nbs();
                                $action_links .= anchor(site_url($vessels_list_link . '/edit/' . $encypt_id), '<i class="fa fa-edit"></i>', $edit_link_attr);
                                $action_links .= nbs();
                                $action_links .= anchor(site_url($vessels_list_link . '/delete/' . $encypt_id), '<i class="fa fa-trash"></i>', $delete_link_attr);
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