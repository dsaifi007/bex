<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//------------------------------------------------------------------------------
// ! GENERAL SETTINGS
//------------------------------------------------------------------------------
$lang['page_title'] = 'Forgot Password';
$lang['page_keywords'] = 'Forgot Password';
$lang['page_description'] = 'Forgot Password Panel';
$lang['forgot_pwd_heading'] = 'Forgot Password';
$lang['login_instruction'] = 'Please enter your email to reset your password.';
$lang['login_link_label'] = 'Sign In';

//------------------------------------------------------------------------------
// ! FORM LABELS
//------------------------------------------------------------------------------
$lang['email_label'] = 'Email';
$lang['suggestion_label'] = 'Please enter the email address for your account. A verification code will be sent to you. Once you have received the verification code, you will be able to choose a new password for your account.';
$lang['forgot_pwd_btn'] = 'Submit';

//------------------------------------------------------------------------------
// ! FORM ERROR LABELS
//------------------------------------------------------------------------------
$lang['err_forgot_pwd_email'] = 'Email';
$lang['err_forgot_pwd_password'] = 'Password';

$lang['err_email_not_registered'] = 'Entered email address is not registered with us.';
$lang['err_email_domain_mismatch'] = 'You are not authorized to reset your password in this domain.';
$lang['err_account_block'] = 'Your account has been terminated. Please contact to administrator.';
$lang['err_account_inactive'] = 'Your account is not active. Please contact to administrator.';

//------------------------------------------------------------------------------
// ! FORM SUCCESS LABELS
//------------------------------------------------------------------------------
$lang['forgot_pwd_set_token_successful'] = 'A system generated token has been sent to your registered email address.';

//------------------------------------------------------------------------------
// ! EMAIL LABELS
//------------------------------------------------------------------------------
$lang['forgot_pwd_email_subject'] = 'System Token - Forgot Password';
$lang['forgot_pwd_email_msg_heading'] = 'System generated token to reset password';
$lang['forgot_pwd_email_msg_para'] = 'A request has been made to reset your %s account password. To reset your password, you will need to submit this verification code in order to verify that the request was legitimate.';
$lang['forgot_pwd_email_msg_token'] = 'The verification code is ';
?>