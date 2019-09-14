<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//------------------------------------------------------------------------------
// ! GENERAL SETTINGS
//------------------------------------------------------------------------------
$lang['page_title']			= 'Ticket Priorities';
$lang['page_keywords']		= 'Ticket Priorities, All Ticket Priorities';
$lang['page_description']	= 'Ticket Priorities';
$lang['page_heading'] = 'Ticket Priorities';
$lang['page_heading_desc'] = 'Tickets & Priorities';

//------------------------------------------------------------------------------
// ! LIST SETTINGS
//------------------------------------------------------------------------------
$lang['table_head_label'] = 'Ticket Priorities List';
$lang['tb_hd_ticket_priority'] = 'Ticket Priorities';

//------------------------------------------------------------------------------
// ! FORM LABELS
//------------------------------------------------------------------------------
$lang['ticket_priority_lbl'] = 'Ticket Priority';
$lang['ticket_priority_item_order_lbl'] = 'Item Ordering';

//------------------------------------------------------------------------------
// ! ERROR LABELS
//------------------------------------------------------------------------------
$lang['err_ticket_priority'] = 'Ticket Priority';
$lang['err_ticket_priority_item_order'] = 'Item Ordering';
$lang['err_ticket_priority_save'] = 'Request of saving ticket priority has been failed. Please try again.';
$lang['error_ticket_priority_delete'] = 'Requested ticket priority could not be deleted. Ticket priority should not contain any sub items. It has %d sub items.';
$lang['err_ticket_priority_self_parent'] = 'Ticket priority parent can not be same as ticket priority. Please choose other ticket priority as a parent.';

//------------------------------------------------------------------------------
// ! MESSAGE Labels
//------------------------------------------------------------------------------
$lang['success_ticket_priority_delete'] = 'Requested ticket priority has been deleted successfully.';
$lang['success_ticket_priority_save'] = 'Ticket priority has been saved successfully.';

?>