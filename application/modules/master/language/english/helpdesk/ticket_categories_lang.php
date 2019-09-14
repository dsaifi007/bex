<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//------------------------------------------------------------------------------
// ! GENERAL SETTINGS
//------------------------------------------------------------------------------
$lang['page_title'] = 'Manage Ticket Categories';
$lang['page_keywords'] = 'Categories, Ticket Categories';
$lang['page_description'] = 'Manage Ticket Categories';
$lang['page_heading'] = 'Ticket Categories';
$lang['page_heading_desc'] = 'Tickets & Categories';

//------------------------------------------------------------------------------
// ! LIST SETTINGS
//------------------------------------------------------------------------------
$lang['table_head_label'] = 'Ticket Categories List';
$lang['tb_hd_title'] = 'Categories';

//------------------------------------------------------------------------------
// ! FORM LABELS
//------------------------------------------------------------------------------
$lang['categories_name_lbl'] = 'Name';
$lang['categories_parent_lbl'] = 'Parent Category';
$lang['categories_item_order_lbl'] = 'Item Ordering';
$lang['categories_desc_lbl'] = 'Description';

//------------------------------------------------------------------------------
// ! ERROR LABELS
//------------------------------------------------------------------------------
$lang['err_category_name'] = 'Category Name';
$lang['err_category_description'] = 'Category Description';
$lang['err_category_parent'] = 'Parent Category';
$lang['err_category_item_order'] = 'Item Ordering';
$lang['err_category_save'] = 'Request of saving ticket category has been failed. Please try again.';
$lang['error_category_delete'] = 'Requested ticket category could not be deleted. Ticket category should not contain any sub menu items. It has %d sub menu items.';
$lang['err_category_self_parent'] = 'Ticket category parent can not be same as ticket category. Please choose other ticket category as a parent.';

//------------------------------------------------------------------------------
// ! MESSAGE Labels
//------------------------------------------------------------------------------
$lang['success_category_delete'] = 'Requested ticket category has been deleted successfully.';
$lang['success_category_save'] = 'Ticket category has been saved successfully.';
?>