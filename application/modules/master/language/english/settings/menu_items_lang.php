<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//------------------------------------------------------------------------------
// ! GENERAL SETTINGS
//------------------------------------------------------------------------------
$lang['page_title']			= 'Menu Items';
$lang['page_keywords']		= 'Menu Items';
$lang['page_description']	= 'Menu Items, Menus';
$lang['page_heading'] = 'Menu Items';
$lang['page_heading_desc'] = 'settings & menu items';

//------------------------------------------------------------------------------
// ! LIST SETTINGS
//------------------------------------------------------------------------------
$lang['table_head_label'] = 'Menu Items List';
$lang['tb_hd_menu_item_title'] = 'Title';
$lang['tb_hd_menu_item_type'] = 'Menu Type';
$lang['tb_hd_menu_item_acl_level'] = 'ACL Level';

//------------------------------------------------------------------------------
// ! FORM LABELS
//------------------------------------------------------------------------------
$lang['menu_item_title_lbl'] = 'Title';
$lang['menu_item_type_lbl'] = 'Item Type';
$lang['menu_item_type_link_lbl'] = 'Link';
$lang['menu_item_type_sep_lbl'] = 'Seprator';
$lang['menu_item_type_oth_module_link_lbl'] = 'Other Module Link';
$lang['menu_item_type_external_link_lbl'] = 'External Link';
$lang['menu_item_url_lbl'] = 'URL';
$lang['menu_item_menu_type_lbl'] = 'Menu Type';
$lang['menu_item_parent_lbl'] = 'Parent Item';
$lang['menu_item_acl_level_lbl'] = 'ACL Level';
$lang['menu_item_usergroups_lbl'] = 'User Groups';
$lang['menu_item_order_lbl'] = 'Ordering';
$lang['menu_item_icon_lbl'] = 'Menu Icon';
$lang['menu_item_browser_nav_lbl'] = 'Browser Navigation';
$lang['menu_browser_nav_self_lbl'] = 'Self';
$lang['menu_browser_nav_new_tab_lbl'] = 'New Tab';

//------------------------------------------------------------------------------
// ! ERROR LABELS
//------------------------------------------------------------------------------
$lang['err_menu_item_title'] = 'Title';
$lang['err_menu_item_type'] = 'Item Type';
$lang['err_menu_item_url'] = 'URL';
$lang['err_menu_item_menu_type'] = 'Menu Type';
$lang['err_menu_item_parent'] = 'Parent Item';
$lang['err_menu_item_acl_level'] = 'ACL Level';
$lang['err_menu_item_usergroups'] = 'User Groups';
$lang['err_menu_item_order'] = 'Ordering';
$lang['err_menu_item_icon'] = 'Menu Icon';
$lang['err_menu_item_browser_nav'] = 'Browser Navigation';
$lang['error_menu_item_delete'] = 'Requested menu item could not be deleted. Menu item should not contain any sub menu items. It has %d sub menu items.';
$lang['err_menu_item_save'] = 'Request of saving menu item has been failed. Please try again.';
$lang['err_menu_item_self_parent'] = 'Item can not be a parent of self. Please choose other item as a parent.';

//------------------------------------------------------------------------------
// ! MESSAGE Labels
//------------------------------------------------------------------------------
$lang['success_menu_item_delete'] = 'Requested menu item has been deleted successfully.';
$lang['success_menu_item_save'] = 'Menu item has been saved successfully.';
?>