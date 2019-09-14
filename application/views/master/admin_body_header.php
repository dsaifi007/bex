<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>

<!-- BEGIN HEADER -->
<div class="page-header navbar navbar-fixed-top">
	<!-- BEGIN HEADER INNER -->
	<div class="page-header-inner ">
		
		<!-- BEGIN LOGO -->
		<div class="page-logo">
			<?php echo anchor($home_url, img(media_url().'assets/layouts/layout/img/logo.png', FALSE, ['class'=> 'logo-default', 'alt'=>$this->lang->line('site_name')]), ['title'=>$this->lang->line('site_name')]); ?>
			
			<div class="menu-toggler sidebar-toggler"><span></span></div>
		</div>
		<!-- END LOGO -->
		
		<!-- BEGIN RESPONSIVE MENU TOGGLER -->
		<a href="javascript:;" class="menu-toggler responsive-toggler" data-toggle="collapse" data-target=".navbar-collapse"><span></span></a>
		<!-- END RESPONSIVE MENU TOGGLER -->
		
		<!-- BEGIN TOP NAVIGATION MENU -->
		<div class="top-menu">
			<ul class="nav navbar-nav pull-right">
				<!-- BEGIN USER LOGIN DROPDOWN -->
				<!-- DOC: Apply "dropdown-dark" class after below "dropdown-extended" to change the dropdown styte -->
				<li class="dropdown dropdown-user">
					
					<a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
						<?php echo img(media_url().'assets/layouts/layout/img/avatar.png', FALSE, ['class'=> 'img-circle', 'alt'=>'']); ?>
						<span class="username username-hide-on-mobile"><?php echo $display_name; ?></span>
					    <i class="fa fa-angle-down"></i>
					</a>
					
					<ul class="dropdown-menu dropdown-menu-default">
						<li><?php echo anchor($profile_url, '<i class="icon-user"></i> '.$this->lang->line('profile_label')); ?></li>
						<!-- <li><a href="app_calendar.html"><i class="icon-calendar"></i> My Calendar </a></li>
						<li><a href="app_inbox.html"><i class="icon-envelope-open"></i> My Inbox <span class="badge badge-danger"> 3 </span></a></li>
						<li><a href="app_todo.html"><i class="icon-rocket"></i> My Tasks <span class="badge badge-success"> 7 </span></a></li>-->
						<li class="divider"> </li>
						<!--<li><a href="page_user_lock_1.html"><i class="icon-lock"></i> Lock Screen </a></li>-->
						<li><?php echo anchor($logout_url, '<i class="icon-key"></i> '.$this->lang->line('logout_label')); ?></li>
					</ul>
				</li>
				<!-- END USER LOGIN DROPDOWN -->
			</ul>
		</div>
		<!-- END TOP NAVIGATION MENU -->
	</div>
	<!-- END HEADER INNER -->
</div>
<!-- END HEADER -->

<!-- BEGIN HEADER & CONTENT DIVIDER -->
<div class="clearfix"> </div>
<!-- END HEADER & CONTENT DIVIDER -->