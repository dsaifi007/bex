<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//------------------------------------------------------------------------------
// ! GENERAL SETTINGS
//------------------------------------------------------------------------------
$lang['page_title'] = 'Manage Post  Categories';
$lang['page_keywords'] = 'Post Categories, Post Categories';
$lang['page_description'] = 'Manage Post Categories';
$lang['page_heading'] = 'Post Categories';
$lang['page_heading_desc'] = 'Post Categories & Components';

//------------------------------------------------------------------------------
// ! LIST SETTINGS
//------------------------------------------------------------------------------
$lang['table_head_label'] = 'List of Post Categories';
$lang['tb_hd_title'] = 'Categories';

//------------------------------------------------------------------------------
// ! FORM LABELS
//------------------------------------------------------------------------------
$lang['post_category_name_lbl'] = 'Category Name';
$lang['post_category_slug_lbl'] = 'Category Slug';
$lang['post_category_domain_lbl'] = 'Domain';
$lang['post_category_parent_lbl'] = 'Parent Category';
$lang['post_category_item_order_lbl'] = 'Item Ordering';
$lang['post_category_description_lbl'] = 'Description';
$lang['post_category_meta_title_lbl'] = 'Meta Title';
$lang['post_category_meta_keywords_lbl'] = 'Meta Keywords';
$lang['post_category_meta_description_lbl'] = 'Meta Description';
$lang['post_category_image_lbl'] = 'Post Category Image';

//------------------------------------------------------------------------------
// ! ERROR LABELS
//------------------------------------------------------------------------------
$lang['err_post_category_name'] = 'Post Category Name';
$lang['err_post_category_slug'] = 'Post Category Slug';
$lang['err_post_category_domain'] = 'Domain';
$lang['err_post_category_parent'] = 'Parent Category';
$lang['err_post_category_item_order'] = 'Item Ordering';
$lang['err_post_category_description'] = 'Description';
$lang['err_post_category_meta_title'] = 'Meta Title';
$lang['err_post_category_meta_keywords'] = 'Meta Keywords';
$lang['err_post_category_meta_description'] = 'Meta Description';
$lang['err_post_category_image'] = 'Post Category Image';
$lang['err_post_category_save'] = 'Request of saving post category has been failed. Please try again.';
$lang['error_post_category_delete'] = 'Requested post category could not be deleted. Post category should not contain any sub items. It has %d sub items.';
$lang['err_post_category_self_parent'] = 'Post Category parent can not be same as post category. Please choose other post category as a parent.';

//------------------------------------------------------------------------------
// ! MESSAGE Labels
//------------------------------------------------------------------------------
$lang['success_post_category_delete'] = 'Requested post category has been deleted successfully.';
$lang['success_post_category_save'] = 'Post category has been saved successfully.';
?>