<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//------------------------------------------------------------------------------
// ! GENERAL SETTINGS
//------------------------------------------------------------------------------
$lang['page_title'] = 'User Groups';
$lang['page_keywords'] = 'User Groups';
$lang['page_description'] = 'User Groups Management';
$lang['page_heading'] = 'User Groups';
$lang['page_heading_desc'] = 'groups & hierarchies';

//------------------------------------------------------------------------------
// ! LIST SETTINGS
//------------------------------------------------------------------------------
$lang['table_head_label'] = 'User Groups List';
$lang['tb_hd_usergroup'] = 'User Group';

//------------------------------------------------------------------------------
// ! FORM LABELS
//------------------------------------------------------------------------------
$lang['usergroup_title'] = 'Group Title';
$lang['usergroup_parent'] = 'Group Parent';

//------------------------------------------------------------------------------
// ! ERROR LABELS
//------------------------------------------------------------------------------
$lang['err_usergroup_title'] = 'Group Title';
$lang['err_usergroup_save'] = 'Request of saving user group has been failed. Please try again.';
$lang['error_usergroup_delete'] = 'Requested usergroup could not be deleted. Usergroup should not contain any child user groups. It has %d sub groups.';
$lang['err_usergroup_parent_list'] = 'Group Parent';
$lang['err_usergroup_self_parent'] = 'Group parent can not be same as group. Please choose other group as a parent.';

//------------------------------------------------------------------------------
// ! MESSAGE Labels
//------------------------------------------------------------------------------
$lang['success_usergroup_delete'] = 'Requested user group has been deleted successfully.';
$lang['success_usergroup_save'] = 'User group has been saved successfully.';
?>