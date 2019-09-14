<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

?>
<div class="page-inner"><?php echo img(media_url().'assets/pages/media/pages/earth.jpg', FALSE, ['class'=> 'img-responsive', 'alt'=>$this->lang->line('heading1_desc')]); ?></div>
<div class="container error-404">
	<?php echo heading($this->lang->line('heading1_desc'), 1); ?>
	<?php echo heading($this->lang->line('heading2_desc'), 2); ?>
	<p><?php echo $this->lang->line('para_desc'); ?></p>
	<p><?php echo anchor($return_url, $this->lang->line('home_url_label'), ['class'=>'btn red btn-outline']); ?><br></p>
</div>