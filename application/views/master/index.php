<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$app = $this->current_app;
$view_path = $this->config->item('global_module_path').$app.'/views/'.$view_name.'.php';

$view_class = (isset($view_class))? $view_class:str_replace('/', ' ', $view_name);

if(file_exists($view_path)) : 
	$this->load->view($app.'/header'); 
	?>
		<body class=" <?php echo $view_class; ?>">
			<?php $this->load->view($view_name); ?>
	<?php 
	$this->load->view($app.'/footer');
else : 
	redirect($this->current_app.'/errors/error404');
endif; 
?>