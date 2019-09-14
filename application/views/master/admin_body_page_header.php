<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$pg_head_date = new DateTime();
?>
<!-- BEGIN PAGE HEADER-->

<!-- BEGIN PAGE BAR -->
<div class="page-bar">
	<ul class="page-breadcrumb">
		<li><?php echo anchor($home_url, $this->lang->line('home_label')); ?><i class="fa fa-circle"></i></li>
		<li><span><?php echo $this->lang->line('page_heading'); ?></span></li>
	</ul>
	<div class="page-toolbar">
		<div id="dashboard-report-range" class="pull-right tooltips btn btn-sm" data-container="body" data-placement="bottom">
			<i class="icon-calendar"></i>&nbsp;
			<span class="thin uppercase hidden-xs"><?php echo $pg_head_date->format('l jS, F Y | H:i:s A'); ?></span>&nbsp;
			<!--<i class="fa fa-angle-down"></i>-->
		</div>
	</div>
</div>
<!-- END PAGE BAR -->

<!-- BEGIN PAGE TITLE-->
<h3 class="page-title"> <?php echo $this->lang->line('page_heading'); ?> <small><?php echo $this->lang->line('page_heading_desc'); ?></small></h3>
<!-- END PAGE TITLE-->

<!-- END PAGE HEADER-->