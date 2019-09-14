<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//------------------------------------------------------------------------------
// ! GENERAL SETTINGS
//------------------------------------------------------------------------------
$lang['page_title'] = 'Domains';
$lang['page_keywords'] = 'Domains, Manage Domains';
$lang['page_description'] = 'Domains';
$lang['page_heading'] = 'Domains';
$lang['page_heading_desc'] = 'settings & domains';

//------------------------------------------------------------------------------
// ! LIST SETTINGS
//------------------------------------------------------------------------------
$lang['table_head_label'] = 'Domains List';
$lang['tb_hd_usergroups'] = 'User Groups';
$lang['tb_hd_title'] = 'Domains';
$lang['tb_hd_slug'] = 'Slug';
$lang['tb_hd_is_down'] = 'Offline';

//------------------------------------------------------------------------------
// ! FORM LABELS
//------------------------------------------------------------------------------
$lang['domain_title_lbl'] = 'Domain';
$lang['domain_slug_lbl'] = 'Slug';
$lang['domain_url_lbl'] = 'URL';
$lang['domain_usergroups_lbl'] = 'Usergroups';
$lang['domain_is_down_lbl'] = 'Domain Offline';
$lang['domain_down_message_lbl'] = 'Offline Message';
$lang['domain_display_notice_lbl'] = 'Display Notice';
$lang['domain_notice_message_lbl'] = 'Notice Message';

//------------------------------------------------------------------------------
// ! ERROR LABELS
//------------------------------------------------------------------------------
$lang['err_domain_title'] = 'Domain';
$lang['err_domain_slug'] = 'Slug';
$lang['err_domain_url'] = 'URL';
$lang['err_domain_usergroups'] = 'Usergroups';
$lang['err_domain_is_down'] = 'Domain Offline';
$lang['err_domain_down_message'] = 'Offline Message';
$lang['err_domain_down_message_required'] = 'The Offline Message field is required if domain offline is set to Yes.';
$lang['err_domain_display_notice'] = 'Display Notice';
$lang['err_domain_notice_message'] = 'Notice Message';
$lang['err_domain_notice_message_required'] = 'The Notice Message field is required if display notice is set to Yes.';
$lang['err_domain_save'] = 'Request of saving domain has been failed. Please try again.';

//------------------------------------------------------------------------------
// ! MESSAGE Labels
//------------------------------------------------------------------------------
$lang['success_domain_delete'] = 'Requested domain has been deleted successfully.';
$lang['success_domain_save'] = 'Domain has been saved successfully.';
?>