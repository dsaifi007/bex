<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//------------------------------------------------------------------------------
// ! GENERAL SETTINGS
//------------------------------------------------------------------------------
$lang['page_title']			= 'Ticket Status';
$lang['page_keywords']		= 'Ticket Status, All Ticket Status';
$lang['page_description']	= 'Ticket Status';
$lang['page_heading'] = 'Ticket Status';
$lang['page_heading_desc'] = 'Tickets & Status';

//------------------------------------------------------------------------------
// ! LIST SETTINGS
//------------------------------------------------------------------------------
$lang['table_head_label'] = 'Ticket Status List';
$lang['tb_hd_ticket_status'] = 'Ticket Status';

//------------------------------------------------------------------------------
// ! FORM LABELS
//------------------------------------------------------------------------------
$lang['ticket_status_lbl'] = 'Ticket Status';
$lang['ticket_status_item_order_lbl'] = 'Item Ordering';

//------------------------------------------------------------------------------
// ! ERROR LABELS
//------------------------------------------------------------------------------
$lang['err_ticket_status'] = 'Ticket Status';
$lang['err_ticket_status_item_order'] = 'Item Ordering';
$lang['err_ticket_status_save'] = 'Request of saving ticket status has been failed. Please try again.';
$lang['error_ticket_status_delete'] = 'Requested ticket status could not be deleted. Ticket status should not contain any sub items. It has %d sub items.';
$lang['err_ticket_status_self_parent'] = 'Ticket status parent can not be same as ticket status. Please choose other ticket status as a parent.';

//------------------------------------------------------------------------------
// ! MESSAGE Labels
//------------------------------------------------------------------------------
$lang['success_ticket_status_delete'] = 'Requested ticket status has been deleted successfully.';
$lang['success_ticket_status_save'] = 'Ticket status has been saved successfully.';

?>