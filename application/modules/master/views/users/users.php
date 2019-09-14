<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$date_frmt = $this->display_date_frmt;

$table_heads = [
    ['data' => $this->lang->line('tb_hd_name'), 'class' => 'left', 'width' => '15%'],
    ['data' => $this->lang->line('tb_hd_email'), 'class' => 'left hidden-xs', 'width' => '20%'],
    ['data' => $this->lang->line('tb_hd_user_usergroups'), 'class' => 'left hidden-xs', 'width' => '15%'],
    ['data' => $this->lang->line('tb_hd_user_block'), 'class' => 'left hidden-xs', 'width' => '5%'],
    ['data' => $this->lang->line('tb_hd_status'), 'class' => 'text-center', 'width' => '5%'],
    ['data' => $this->lang->line('tb_hd_user_last_login'), 'class' => 'hidden-xs', 'width' => '10%'],
    ['data' => $this->lang->line('tb_hd_created_on'), 'class' => 'text-center hidden-xs', 'width' => '10%'],
    ['data' => $this->lang->line('tb_hd_actions'), 'class' => 'text-center', 'width' => '10%']
];

$template_active = ['table_open' => '<table class="table table-striped table-bordered table-hover users_list">'];
$add_btn = ($add_btn)? display_form_links($list_link . '/add', $this->lang->line('add_new_item_lbl')):'';
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <?php echo display_message_info(['0' => $error, '1' => $success]); ?>
            <?php echo display_portlet_title($this->lang->line('table_head_label'), $add_btn); ?>
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