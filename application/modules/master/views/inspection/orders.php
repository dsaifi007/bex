<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$date_frmt = $this->display_date_frmt;

$table_heads = [
    ['data' => $this->lang->line('tb_hd_order_invoice_no'), 'class' => 'left', 'width' => '10%'],
    ['data' => $this->lang->line('tb_hd_order_user_fullname'), 'class' => 'left', 'width' => '15%'],
    ['data' => $this->lang->line('tb_hd_order_user_email'), 'class' => 'left', 'width' => '15%'],
    ['data' => $this->lang->line('tb_hd_order_payment_status'), 'class' => 'left', 'width' => '10%'],
    ['data' => $this->lang->line('tb_hd_order_order_status'), 'class' => 'left', 'width' => '10%'],
    //['data' => $this->lang->line('tb_hd_order_currency_code'), 'class' => 'left', 'width' => '10%'],
    ['data' => $this->lang->line('tb_hd_order_total_price'), 'class' => 'left', 'width' => '10%'],
    //['data' => $this->lang->line('tb_hd_status'), 'class' => 'text-center', 'width' => '10%'],
    ['data' => $this->lang->line('tb_hd_order_ordered_on'), 'class' => 'text-center hidden-xs', 'width' => '10%'],
    ['data' => $this->lang->line('tb_hd_actions'), 'class' => 'text-center', 'width' => '10%']
];

$template_active = ['table_open' => '<table class="table table-striped table-condensed table-bordered table-hover orders_list">'];

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
                    $this->table->set_template($template_active);
                    $this->table->set_heading($table_heads);
                    echo $this->table->generate();
                ?>
            </div>
        </div>
    </div>
</div>