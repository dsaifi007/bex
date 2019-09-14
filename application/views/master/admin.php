<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$app = $this->current_app;
$view_path = $this->config->item('global_module_path').$app.'/views/'.$view_name;

$view_class = (isset($view_class))? $view_class:str_replace('/', ' ', $view_name);

if(file_exists($view_path.'.php')) : 
	$this->load->view($app.'/header'); ?>
	<body class="page-header-fixed page-sidebar-closed-hide-logo page-container-bg-solid page-content-white <?php echo $view_class; ?>">
	<?php $this->load->view($app.'/admin_body_header'); ?>
		<!-- BEGIN CONTAINER -->
		<div class="page-container">
			<?php $this->load->view($app.'/admin_body_sidebar'); ?>
			<!-- BEGIN CONTENT -->
            <div class="page-content-wrapper">
				<!-- BEGIN CONTENT BODY -->
				<div class="page-content">
					<?php $this->load->view($app.'/admin_body_page_header');
						$this->load->view($view_name); 
					?>
				</div>
				<!-- END CONTENT BODY -->
			</div>
			<!-- END CONTENT -->
		</div>
		<!-- END CONTAINER -->
	<?php $this->load->view($app.'/footer');
else : 
	redirect($this->current_app.'/errors/error404');
endif; 
?>