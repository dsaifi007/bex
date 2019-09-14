<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//------------------------------------------------------------------------------
// ! GENERAL SETTINGS
//------------------------------------------------------------------------------
$lang['page_title']			= 'Vessel Inspection Orders';
$lang['page_keywords']		= 'Vessel Inspection Orders';
$lang['page_description']	= 'Vessel Inspection Orders';
$lang['page_heading'] = 'Vessel Inspection Orders';
$lang['page_heading_desc'] = 'Vessel Inspection Report & Orders';

//------------------------------------------------------------------------------
// ! LIST SETTINGS
//------------------------------------------------------------------------------
$lang['table_head_label'] = 'Orders List';
$lang['tb_hd_order_invoice_no'] = 'Invoice No';
$lang['tb_hd_order_user_fullname'] = 'Name';
$lang['tb_hd_order_user_email'] = 'Email';
$lang['tb_hd_order_payment_method'] = 'Payment Method';
$lang['tb_hd_order_payment_status'] = 'Payment Status';
$lang['tb_hd_order_order_status'] = 'Order Status';
$lang['tb_hd_order_currency_code'] = 'Currency';
$lang['tb_hd_order_total_price'] = 'Total Amount';
$lang['tb_hd_order_ordered_on'] = 'Ordered On';

//------------------------------------------------------------------------------
// ! ORDER VIEW LABELS
//------------------------------------------------------------------------------

$lang['order_label'] = 'Order';
$lang['order_detail_tab_label'] = 'Details';
$lang['order_invoice_tab_label'] = 'Invoices';
$lang['order_details_label'] = 'Order Details';
$lang['order_date_time']= 'Order Date & Time';
$lang['order_status']= 'Order Status';
$lang['order_grand_total']= 'Grand Total';
$lang['order_payment_info'] = 'Payement Information';
$lang['order_customer_info_label'] = 'Client Information';
$lang['order_customer_name'] = 'Client Name';
$lang['order_customer_email'] = 'Email';
$lang['order_customer_phone'] = 'Phone';
$lang['order_customer_address'] = 'Address';
$lang['order_customer_zip_code'] = 'Zip Code';
$lang['order_cart_description'] = 'Cart';
$lang['order_description_lable'] = 'Description';
$lang['order_qty_label'] = 'Quantity';
$lang['order_unit_price_label'] = 'Unit Price';
$lang['order_total_price_label'] = 'Total Price';
$lang['order_sub_total_label'] =  'Sub Total';
$lang['order_grand_total_label'] = 'Grand Total';
$lang['order_payment_price_label'] = 'Payments';
//------------------------------------------------------------------------------
// ! FORM LABELS
//------------------------------------------------------------------------------
$lang['order_order_status_lbl'] = 'Order Status';

//------------------------------------------------------------------------------
// ! ERROR LABELS
//------------------------------------------------------------------------------
$lang['err_order_status_id'] = 'Order Status';
$lang['err_in_list'] ='Item id does not exist';
$lang['err_order_save'] = 'Request of saving order has been failed. Please try again.';

//------------------------------------------------------------------------------
// ! MESSAGE Labels
//------------------------------------------------------------------------------
$lang['success_order_save'] = 'Order has been saved successfully.';
$lang['success_order_delete'] = 'Requested order has been deleted successfully.';
?>