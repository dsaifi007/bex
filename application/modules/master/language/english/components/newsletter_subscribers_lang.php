<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//------------------------------------------------------------------------------
// ! GENERAL SETTINGS
//------------------------------------------------------------------------------
$lang['page_title']			= 'Newsletter Subscribers';
$lang['page_keywords']		= 'Newsletter Subscribers, Email Newsletter Subscribers';
$lang['page_description']	= 'Newsletter Subscribers';
$lang['page_heading'] = 'Newsletter Subscribers';
$lang['page_heading_desc'] = 'Newsletter & Subscribers';

//------------------------------------------------------------------------------
// ! LIST SETTINGS
//------------------------------------------------------------------------------
$lang['table_head_label'] = 'Newsletter Subscribers';
$lang['tb_hd_newsletter_subscribers'] = 'Subscribers Email';

//------------------------------------------------------------------------------
// ! FORM LABELS
//------------------------------------------------------------------------------
$lang['subs_email_lbl'] = 'Subscriber Email';
$lang['subs_name_lbl'] = 'Subscriber Name';
$lang['subs_subscribe_lbl'] = 'Subscribed?';

//------------------------------------------------------------------------------
// ! ERROR LABELS
//------------------------------------------------------------------------------
$lang['err_subs_email'] = 'Subscriber Email';
$lang['err_subs_name'] = 'Subscriber Name';
$lang['err_newsletter_subscriber_save'] = 'Request of saving newsletter subscriber has been failed. Please try again.';
$lang['err_newsletter_subscriber_existed'] = 'You have already been subscribed to our newsletter.';

//------------------------------------------------------------------------------
// ! MESSAGE Labels
//------------------------------------------------------------------------------
$lang['success_newsletter_subscriber_delete'] = 'Requested newsletter subscriber has been deleted successfully.';
$lang['success_newsletter_subscriber_save'] = 'Newsletter subscriber has been saved successfully.';
$lang['success_newsletter_subscriber_enduser'] = 'Thank you for subscribing our newsletter.';

//------------------------------------------------------------------------------
// ! EMAIL LABELS
//------------------------------------------------------------------------------
$lang['subscription_user_email_subject'] = 'Newsletter Subscription';
$lang['subscription_admin_email_subject'] = 'New Newsletter Subscription of %s';
$lang['subscription_user_email_para'] = 'Thank you for subscribing our newsletter.';
$lang['subscription_user_email_para1'] = 'For questions about this subscription, please contact: hgupta029@gmail.com';
$lang['subscription_admin_email_para'] = 'A new user has subscribed to our newsletter. Details are given below.';
$lang['subscription_admin_email_info'] = 'Email: %s';
$lang['subscription_admin_name_info'] = 'Name: %s';
?>