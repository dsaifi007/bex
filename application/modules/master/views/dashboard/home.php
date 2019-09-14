<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

//echo $this->router->fetch_class().'<br />'; echo $this->router->fetch_method();
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light bordered">
            <?php if($this->hook_data->curr_domain->display_notice) : 
                    echo display_message_notes($this->hook_data->curr_domain->notice_message);
                endif; 
            ?>
        </div>
    </div>
</div>