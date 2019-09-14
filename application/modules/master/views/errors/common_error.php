<?php defined('BASEPATH') OR exit('No direct script access allowed'); 

?>
<div class="row">
	<div class="col-md-12 page-500">
		<div class=" number font-red"><?php echo $this->lang->line('para_desc'); ?></div>
		<div class=" details">
			<?php echo heading($heading1_desc, 3); ?>
			<p><?php echo $heading2_desc; ?><br/> </p>
			<p><?php echo anchor($return_url, $this->lang->line('home_url_label'), ['class'=>'btn red btn-outline']); ?><br></p>
		</div>
	</div>
</div>