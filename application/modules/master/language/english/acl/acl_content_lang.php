<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//------------------------------------------------------------------------------
// ! GENERAL SETTINGS
//------------------------------------------------------------------------------
$lang['page_title']			= 'ACL Content';
$lang['page_keywords']		= 'ACL content';
$lang['page_description']	= 'ACL Content';
$lang['page_heading'] = 'ACL Content';
$lang['page_heading_desc'] = 'acl & content';

//------------------------------------------------------------------------------
// ! LIST SETTINGS
//------------------------------------------------------------------------------
$lang['table_head_label'] = 'ACL Content List';
$lang['tb_hd_acl_content_title'] = 'Title';
$lang['tb_hd_acl_content_type'] = 'Type';
$lang['tb_hd_acl_access_level'] = 'ACL Level';
$lang['tb_hd_acl_content_domain'] = 'Domain';
$lang['tb_hd_acl_content_usergroups'] = 'Usergroups';

//------------------------------------------------------------------------------
// ! FORM LABELS
//------------------------------------------------------------------------------
$lang['acl_content_title_lbl'] = 'Content Title';
$lang['acl_content_type_lbl'] = 'Content Type';
$lang['acl_parent_id_lbl'] = 'Content Parent';
$lang['access_level_lbl'] = 'ACL Level';
$lang['content_domain_lbl'] = 'Domain';
$lang['usergroups_lbl'] = 'Usergroups';
$lang['content_description_lbl'] = 'Description';

//------------------------------------------------------------------------------
// ! ERROR LABELS
//------------------------------------------------------------------------------
$lang['err_content_title'] = 'Content Title';
$lang['err_content_type'] = 'Content Type';
$lang['err_parent_id'] = 'Content Parent';
$lang['err_acl_level'] = 'ACL Level';
$lang['err_domain'] = 'Domain';
$lang['err_usergroups'] = 'Usergroups';
$lang['err_content_description'] = 'Description';
$lang['error_acl_content_delete'] = 'Requested ACL content could not be deleted. ACL content should not contain any sub ACL content. It has %d sub ACL content.';
$lang['err_acl_content_save'] = 'Request of saving ACL content has been failed. Please try again.';
$lang['err_acl_content_self_parent'] = 'Item can not be a parent of self. Please choose other item as a parent.';

//------------------------------------------------------------------------------
// ! MESSAGE Labels
//------------------------------------------------------------------------------
$lang['success_acl_content_delete'] = 'Requested ACL content has been deleted successfully.';
$lang['success_acl_content_save'] = 'ACL content has been saved successfully.';
?>