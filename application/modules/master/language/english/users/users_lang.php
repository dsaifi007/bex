<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//------------------------------------------------------------------------------
// ! GENERAL SETTINGS
//------------------------------------------------------------------------------
$lang['page_title']			= 'Users';
$lang['page_keywords']		= 'Users, User';
$lang['page_description']	= 'Users, Users Management';
$lang['page_heading'] = 'Users';
$lang['page_heading_desc'] = 'users & management';

//------------------------------------------------------------------------------
// ! LIST SETTINGS
//------------------------------------------------------------------------------
$lang['table_head_label'] = 'Users List';
$lang['tb_hd_user_email'] = 'Email';
$lang['tb_hd_user_block'] = 'Block';
$lang['tb_hd_user_usergroups'] = 'User Groups';
$lang['tb_hd_user_last_login'] = 'Last Login On';

//------------------------------------------------------------------------------
// ! FORM LABELS
//------------------------------------------------------------------------------
$lang['user_fname_lbl'] = 'First Name';
$lang['user_mname_lbl'] = 'Middle Name';
$lang['user_lname_lbl'] = 'Last Name';
$lang['user_email_lbl'] = 'Email';
$lang['user_block_lbl'] = 'Block';
$lang['user_receive_emails_lbl'] = 'Receive System Emails';
$lang['user_usergroups_lbl'] = 'User Groups';
$lang['user_password_lbl'] = 'Password';
$lang['user_vpassword_lbl'] = 'Verify Password';
$lang['user_display_name_lbl'] = 'Display Name';

//------------------------------------------------------------------------------
// ! ERROR LABELS
//------------------------------------------------------------------------------
$lang['err_user_fname'] = 'First Name';
$lang['err_user_mname'] = 'Middle Name';
$lang['err_user_lname'] = 'Last Name';
$lang['err_user_email'] = 'Email';
$lang['err_user_active'] = 'Active';
$lang['err_user_block'] = 'Block';
$lang['err_user_receive_emails'] = 'Receive System Emails';
$lang['err_user_usergroups'] = 'User Groups';
$lang['err_user_password'] = 'Password';
$lang['err_user_vpassword'] = 'Verify Password';
$lang['err_user_display_name'] = 'Display Name';

$lang['err_user_save'] = 'Request of saving user has been failed. Please try again.';

//------------------------------------------------------------------------------
// ! MESSAGE LABELS
//------------------------------------------------------------------------------
$lang['success_user_delete'] = 'Requested user has been deleted successfully.';
$lang['success_user_save'] = 'User has been saved successfully.';

//------------------------------------------------------------------------------
// ! EMAIL LABELS
//------------------------------------------------------------------------------

$lang['user_email_subject'] = 'New Registration';
$lang['registration_user_email_para'] = 'Thank you for Registration.';
$lang['registration_admin_email_subject'] = 'New Registration of %s';
$lang['registration_admin_email_para'] = 'A new user has been registred with us. Details are given below.';
$lang['registration_email_info'] = 'Email: %s';
$lang['registration_name_info'] = 'Name: %s';
?>