<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//$date_frmt = $this->display_date_frmt;

$table_heads = [
    ['data' => $this->lang->line('tb_hd_db_cache_title'), 'class' => 'left', 'width' => '50%'],
    ['data' => $this->lang->line('tb_hd_filesize'), 'class' => 'text-center', 'width' => '30%'],
    ['data' => $this->lang->line('tb_hd_actions'), 'class' => 'text-center', 'width' => '20%']
];

$template_active = ['table_open' => '<table class="table table-striped table-bordered table-hover db_cache_list">'];
$delete_link_attr = ['class' => 'btn btn-xs text-danger', 'data-toggle' => 'confirmation', 'data-placement' => 'left', 'data-original-title' => $this->lang->line('remove_item_lbl'), 'data-popout' => 'true'];
?>

<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <?php echo display_message_info(['0' => $error, '1' => $success]); ?>
            <?php echo display_portlet_title($this->lang->line('table_head_label'), display_form_links($list_link . '/removecache/'.$cache_type, $this->lang->line('remove_all_db_lbl'), 'trash', 'sbold red')); ?>
            <div class="portlet-body">
                <?php
                if (count($items) > 0) :
                    $filesize_arr = ['TB'=>pow(1024, 4), 'GB'=>pow(1024, 3), 'MB'=>pow(1024, 2), 'KB'=>1024];
                    $this->table->set_template($template_active);
                    $this->table->set_heading($table_heads);
                    foreach ($items as $k => $v) :
                        $encypt_id = encodeData($k);
                        
                        $rowdata = [];
                        $rowdata[] = ['data' => $k];
                        $rowdata[] = ['data' => getFilesizeUnitValue($v, $filesize_arr), 'class' => 'text-center'];

                        $action_links = '<div class="actions">';
                        $action_links .= anchor(site_url($list_link . '/removecache/'.$cache_type.'/'. $encypt_id), '<i class="fa fa-trash"></i>', $delete_link_attr);
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