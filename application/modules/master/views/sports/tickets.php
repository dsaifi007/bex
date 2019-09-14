<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$date_frmt = $this->display_date_frmt;

$table_heads = [
    ['data' => $this->lang->line('tb_hd_ticket_number'), 'class' => 'left', 'width' => '20%'],
    ['data' => $this->lang->line('tb_hd_ticket_price'),  'width' => '20%'],
    ['data' => $this->lang->line('tb_hd_ticket_status'),  'width' => '20%'],
    ['data' => $this->lang->line('tb_hd_status'), 'class' => 'text-center', 'width' => '10%'],
    ['data' => $this->lang->line('tb_hd_modified_on'), 'class' => 'text-center hidden-xs', 'width' => '20%'],
    ['data' => $this->lang->line('tb_hd_actions'), 'class' => 'text-center', 'width' => '10%']
];

$template_active = ['table_open' => '<table class="table table-striped table-bordered table-hover tickets_list">'];

$edit_link_attr = ['class' => 'btn btn-xs tooltips', 'data-placement' => 'top', 'data-original-title' => $this->lang->line('edit_item_lbl')];
$delete_link_attr = ['class' => 'btn btn-xs text-danger', 'data-toggle' => 'confirmation', 'data-placement' => 'left', 'data-original-title' => $this->lang->line('remove_item_lbl'), 'data-popout' => 'true'];
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <?php echo display_message_info(['0' => $error, '1' => $success]); ?>
            <?php echo display_portlet_title($this->lang->line('table_head_label'), display_form_links($list_link . '/add', $this->lang->line('add_new_item_lbl'))); ?>
            <div class="portlet-body">
                <?php
                if (count($items) > 0) :
                    $this->table->set_template($template_active);
                    $this->table->set_heading($table_heads);
                    foreach ($items as $k => $v) :
                        $encypt_id = encodeData($k);
                        $rowdata = [];
						
                        $rowdata[] = ['data' => $v->ticket_number];
                        $rowdata[] = ['data' => (isset($ticket_pricing_id[$v->ticket_pricing_id]))? $ticket_pricing_id[$v->ticket_pricing_id]:''];
$rowdata[] =['data'=>($v->is_sold_out==1)?"<span class='label text-center label-sm label-success'>Available</span>":"<span class='label text-center label-sm label-danger'>Out of Stock</span>"];
					    $rowdata[] = ['data' => display_status_btn($v->published), 'class' => 'text-center'];
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