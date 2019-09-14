<?php

defined('BASEPATH') OR exit('No direct script access allowed');

//------------------------------------------------------------------------------
// ! GENERAL SETTINGS
//------------------------------------------------------------------------------
$lang['page_title'] = 'Reset Password';
$lang['page_keywords'] = 'Reset Password';
$lang['page_description'] = 'Reset Password Panel';
$lang['forgot_pwd_heading'] = 'Reset Password';
$lang['suggestion_reset_label'] = 'An email has been sent to your email address. The email contains a verification code, please paste the verification code with your registered email address in the fields below to prove that you are the owner of this account and proceed to reset your password.';

//------------------------------------------------------------------------------
// ! FORM LABELS
//------------------------------------------------------------------------------
$lang['resetpwd_token_label'] = 'Token No.';
$lang['reset_password_lbl'] = 'Password';
$lang['reset_vpassword_lbl'] = 'Verify Password';

//------------------------------------------------------------------------------
// ! FORM ERROR LABELS
//------------------------------------------------------------------------------
$lang['err_reset_password'] = 'Password';
$lang['err_reset_vpassword'] = 'Verify Password';
$lang['err_reset_token'] = 'Token No.';
$lang['err_reset_token_mismatch'] = 'Entered token does not match with the token we have sent you. Please enter correct token number.';
$lang['err_reset_token_expired'] = 'Entered token has been expired. Please go to forgot password page to regenerate the token.';

//------------------------------------------------------------------------------
// ! FORM SUCCESS LABELS
//------------------------------------------------------------------------------
$lang['reset_password_success'] = 'You have successfully reset your password. Please login.';
?>