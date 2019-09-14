<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//------------------------------------------------------------------------------
// ! GENERAL SETTINGS
//------------------------------------------------------------------------------
$lang['page_title']			= 'Menu Types';
$lang['page_keywords']		= 'Menu Types';
$lang['page_description']	= 'Menu Types, Menus';
$lang['page_heading'] = 'Menu Types';
$lang['page_heading_desc'] = 'settings & menu types';

//------------------------------------------------------------------------------
// ! LIST SETTINGS
//------------------------------------------------------------------------------
$lang['table_head_label'] = 'Menu Types List';
$lang['tb_hd_menu_type_title'] = 'Title';
$lang['tb_hd_menu_type_slug'] = 'Slug';
$lang['tb_hd_menu_type_domain'] = 'Domain';

//------------------------------------------------------------------------------
// ! FORM LABELS
//------------------------------------------------------------------------------
$lang['menu_type_title_lbl'] = 'Title';
$lang['menu_type_slug_lbl'] = 'Slug';
$lang['menu_type_domain_lbl'] = 'Domain';
$lang['menu_type_description_lbl'] = 'Description';

//------------------------------------------------------------------------------
// ! ERROR LABELS
//------------------------------------------------------------------------------
$lang['err_menu_type_title'] = 'Title';
$lang['err_menu_type_slug'] = 'Slug';
$lang['err_domain'] = 'Domain';
$lang['err_menu_type_description'] = 'Description';
$lang['error_menu_type_delete'] = 'Requested menu type could not be deleted. Menu type should not contain any menu items. It has %d menu items.';
$lang['err_menu_type_save'] = 'Request of saving menu type has been failed. Please try again.';

//------------------------------------------------------------------------------
// ! MESSAGE Labels
//------------------------------------------------------------------------------
$lang['success_menu_type_delete'] = 'Requested menu type has been deleted successfully.';
$lang['success_menu_type_save'] = 'Menu type has been saved successfully.';
?>