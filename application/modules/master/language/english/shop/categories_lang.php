<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//------------------------------------------------------------------------------
// ! GENERAL SETTINGS
//------------------------------------------------------------------------------
$lang['page_title'] = 'Manage Categories';
$lang['page_keywords'] = 'Categories, Product Categories';
$lang['page_description'] = 'Manage Categories';
$lang['page_heading'] = 'Categories';
$lang['page_heading_desc'] = 'Categories & Shop';

//------------------------------------------------------------------------------
// ! LIST SETTINGS
//------------------------------------------------------------------------------
$lang['table_head_label'] = 'List of Categories';
$lang['tb_hd_title'] = 'Categories';

//------------------------------------------------------------------------------
// ! FORM LABELS
//------------------------------------------------------------------------------
$lang['categories_name_lbl'] = 'Name';
$lang['categories_parent_lbl'] = 'Parent Category';
$lang['categories_item_order_lbl'] = 'Item Ordering';
$lang['categories_desc_lbl'] = 'Description';
$lang['categories_stores_lbl'] = 'Stores';

//------------------------------------------------------------------------------
// ! ERROR LABELS
//------------------------------------------------------------------------------
$lang['err_category_name'] = 'Category Name';
$lang['err_category_description'] = 'Category Description';
$lang['err_category_parent'] = 'Parent Category';
$lang['err_category_item_order'] = 'Item Ordering';
$lang['err_category_stores'] = 'Stores';
$lang['err_category_save'] = 'Request of saving category has been failed. Please try again.';
$lang['error_category_delete'] = 'Requested category could not be deleted. Category should not contain any sub items. It has %d sub items.';
$lang['err_category_self_parent'] = 'Category parent can not be same as category. Please choose other category as a parent.';

//------------------------------------------------------------------------------
// ! MESSAGE Labels
//------------------------------------------------------------------------------
$lang['success_category_delete'] = 'Requested category has been deleted successfully.';
$lang['success_category_save'] = 'Category has been saved successfully.';
?>