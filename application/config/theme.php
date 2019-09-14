<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
|--------------------------------------------------------------------------
| Theme Configurations
|--------------------------------------------------------------------------
|
*/

$config['th_media_folder'] = 'assets/';
$config['th_media_global'] = $config['th_media_folder'].'global/';
$config['th_media_global_plg'] = $config['th_media_global'].'plugins/';
$config['th_media_global_css'] = $config['th_media_global'].'css/';
$config['th_media_global_scripts'] = $config['th_media_global'].'scripts/';

$config['th_media_layts'] = $config['th_media_folder'].'layouts/';
$config['th_media_layts_lyt'] = $config['th_media_layts'].'layout/';
$config['th_media_layts_lyt_css'] = $config['th_media_layts_lyt'].'css/';
$config['th_media_layts_lyt_img'] = $config['th_media_layts_lyt'].'img/';

$config['th_media_custom_css'] = $config['th_media_folder'].'custom_css/';
$config['th_media_custom_js'] = $config['th_media_folder'].'custom_js/';

$config['th_media_pages'] = $config['th_media_folder'].'pages/';

/*
|--------------------------------------------------------------------------
| Theme CSS Configurations
|--------------------------------------------------------------------------
|
*/

$config['global_imp_css'] = [
	$config['th_media_global_plg'].'font-awesome/css/font-awesome.min.css',
	$config['th_media_global_plg'].'simple-line-icons/simple-line-icons.min.css',
	$config['th_media_global_plg'].'bootstrap/css/bootstrap.min.css',
	$config['th_media_global_plg'].'uniform/css/uniform.default.css',
	$config['th_media_global_plg'].'bootstrap-switch/css/bootstrap-switch.min.css',
	//custom css added by Harshit
	$config['th_media_global_plg'].'bootstrap-ladda/ladda-themeless.min.css'
];

$config['global_th_css'] = [
	$config['th_media_global_css'].'components.min.css',
	$config['th_media_global_css'].'plugins.min.css'
];

$config['global_admin_th_css'] = [
	$config['th_media_layts_lyt_css'].'layout.min.css',
	$config['th_media_layts_lyt_css'].'themes/darkblue.min.css',
	$config['th_media_layts_lyt_css'].'custom.min.css'
];

$config['global_final_css'] = [
	$config['th_media_custom_css'].'final.css'
];

$config['css_datatables'] = [
	$config['th_media_global_plg'].'datatables/plugins/bootstrap/datatables.bootstrap.css'
];

$config['css_datepicker'] = [
    $config['th_media_global_plg'] . 'bootstrap-datepicker/css/bootstrap-datepicker.min.css'
];

$config['css_daterangepicker'] = [
    $config['th_media_global_plg'] . 'daterangepicker/daterangepicker.css'
];

$config['css_editor_summernote'] = [
    $config['th_media_global_plg'] . 'bootstrap-summernote/summernote.css'
];

$config['css_form_fileinput'] = [
    $config['th_media_global_plg'] . 'bootstrap-fileinput/bootstrap-fileinput.css'
];

$config['css_form_elements'] = [
    $config['th_media_global_plg'].'select2/css/select2.min.css',
    $config['th_media_global_plg'].'select2/css/select2-bootstrap.min.css'
];

$config['css_bootstrap_modal'] = [
    $config['th_media_global_plg'].'bootstrap-modal/css/bootstrap-modal-bs3patch.css',
    $config['th_media_global_plg'].'bootstrap-modal/css/bootstrap-modal.css'
];

/*
|--------------------------------------------------------------------------
| Theme JS Configurations
|--------------------------------------------------------------------------
|
*/

$config['IE9_LT'] = [
	$config['th_media_global_plg'].'respond.min.js',
	$config['th_media_global_plg'].'excanvas.min.js'
];

$config['IE9_LT_outside'] = [];

$config['global_plg_js'] = [
	$config['th_media_global_plg'].'jquery.min.js',
	$config['th_media_global_plg'].'bootstrap/js/bootstrap.min.js',
	$config['th_media_global_plg'].'js.cookie.min.js',
	$config['th_media_global_plg'].'bootstrap-hover-dropdown/bootstrap-hover-dropdown.min.js',
	$config['th_media_global_plg'].'jquery-slimscroll/jquery.slimscroll.min.js',
	$config['th_media_global_plg'].'jquery.blockui.min.js',
	$config['th_media_global_plg'].'uniform/jquery.uniform.min.js',
	$config['th_media_global_plg'].'bootstrap-switch/js/bootstrap-switch.min.js',
	//custom js added by Harshit
	$config['th_media_global_plg'].'bootbox/bootbox.min.js',
	$config['th_media_global_plg'].'bootstrap-ladda/spin.min.js',
	$config['th_media_global_plg'].'bootstrap-ladda/ladda.min.js',
    $config['th_media_global_plg'].'bootstrap-confirmation/bootstrap-confirmation.min.js'
	
];

$config['global_th_js'] = [
	$config['th_media_global_scripts'].'app.min.js'
];

$config['js_jquery_validation'] = [
	$config['th_media_global_plg'].'jquery-validation/js/jquery.validate.min.js',
	$config['th_media_global_plg'].'jquery-validation/js/additional-methods.min.js'
];

$config['js_form_fileinput'] = [
    $config['th_media_global_plg'] . 'bootstrap-fileinput/bootstrap-fileinput.js'
];

$config['js_form_elements'] = [
    $config['th_media_global_plg'].'select2/js/select2.full.min.js',
];

$config['js_datatables'] = [
	$config['th_media_global_plg'].'datatables/datatables.min.js',
	$config['th_media_global_plg'].'datatables/plugins/bootstrap/datatables.bootstrap.js'
];

$config['js_datepicker'] = [
    $config['th_media_global_plg'] . 'bootstrap-datepicker/js/bootstrap-datepicker.min.js'
];

$config['js_daterangepicker'] = [
    $config['th_media_global_plg'] . 'daterangepicker/moment.js',
    $config['th_media_global_plg'] . 'daterangepicker/daterangepicker.js'
];

$config['js_editor_summernote'] = [
    $config['th_media_global_plg'] . 'bootstrap-summernote/summernote.min.js',
    $config['th_media_custom_js'].'editor_summernote.js'
];

$config['global_th_admin_lyt_js'] = [
	$config['th_media_layts_lyt'].'scripts/layout.min.js',
	$config['th_media_layts_lyt'].'scripts/demo.min.js',
	$config['th_media_layts'].'global/scripts/quick-sidebar.min.js'
];

$config['global_utility_js'] = [
	$config['th_media_custom_js'].'utility.js'
];

$config['js_bootstrap_modal'] = [
	$config['th_media_global_plg'].'bootstrap-modal/js/bootstrap-modalmanager.js',
	$config['th_media_global_plg'].'bootstrap-modal/js/bootstrap-modal.js'
]; 