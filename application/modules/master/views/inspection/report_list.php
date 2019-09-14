<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$table_heads = [
    ['data' => $this->lang->line('tb_hd_report_vessel_name'), 'class' => 'left', 'width' => '15%'],
    ['data' => $this->lang->line('tb_hd_report_diver_name'), 'class' => 'left', 'width' => '15%'],
    ['data' => $this->lang->line('tb_hd_report_cleaning_date'), 'class' => 'left', 'width' => '15%'],
    ['data' => $this->lang->line('tb_hd_report_created_on'), 'class' => 'left', 'width' => '15%'],
    ['data' => $this->lang->line('tb_hd_report_status'), 'class' => 'text-center', 'width' => '10%'],
    ['data' => $this->lang->line('tb_hd_modified_on'), 'class' => 'text-center hidden-xs', 'width' => '20%'],
    ['data' => $this->lang->line('tb_hd_actions'), 'class' => 'text-center', 'width' => '10%']
];

$template_active = ['table_open' => '<table class="table table-striped table-bordered table-hover report_list_tbl">'];
$edit_link_attr = ['class' => 'btn btn-xs tooltips', 'data-placement' => 'top', 'data-original-title' => $this->lang->line('edit_item_lbl')];
$delete_link_attr = ['class' => 'btn btn-xs text-danger', 'data-toggle' => 'confirmation', 'data-placement' => 'left', 'data-original-title' => $this->lang->line('remove_item_lbl'), 'data-popout' => 'true'];
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <?php echo display_message_info(['0' => $error, '1' => $success]); ?>
            <?php echo display_portlet_title($this->lang->line('table_head_label')); ?>
            <div class="portlet-body">
                <?php
                if (count($items) > 0) :
                    $this->table->set_template($template_active);
                    $this->table->set_heading($table_heads);
                    foreach ($items as $k => $v) :
                        $encypt_id = encodeData($k);
                        
                        $cleaning_data = json_decode($v->cleaning_data);

                        $rowdata = [];
                        $rowdata[] = ['data' => $cleaning_data->vessel_name];
                        $rowdata[] = ['data' => $cleaning_data->diver_name];
                        $rowdata[] = ['data' => formatDateTime($cleaning_data->cleaning_date, $this->display_date_frmt) ];
                        $rowdata[] = ['data' => formatDateTime($v->created_on, $this->display_date_frmt), 'class' => 'text-center hidden-xs'];
                        $rowdata[] = ['data' => display_status_btn($v->report_status_id, $report_status_list), 'class' => 'text-center'];
                        $rowdata[] = ['data' => formatDateTime($v->modified_on, $this->display_date_full_frmt), 'class' => 'text-center hidden-xs'];
                        $action_links = '<div class="actions">';
                        $action_links .= anchor(site_url($list_link . '/edit/' . $encypt_id), '<i class="fa fa-edit"></i>', $edit_link_attr);
                        $action_links .= nbs();
                        $action_links .= anchor(site_url($list_link . '/delete/' . $encypt_id), '<i class="fa fa-trash"></i>', $delete_link_attr);
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