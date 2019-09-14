<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$date_full_frmt = $this->display_date_full_frmt;
?>
<div class="row">
    <div class="col-md-12">
        <div class="portlet light portlet-fit portlet-datatable bordered">
            <div class="portlet-title">
                <div class="caption">
                    <i class="icon-settings font-dark"></i>
                    <span class="caption-subject font-dark sbold uppercase"> 
                    <?php echo $this->lang->line('order_label').'#';?> 
                    <?php echo $item->id;?>
                        <span class="hidden-xs">|<?php echo formatDateTime($item->created_on, $this->display_date_full_frmt)?> </span>
                    </span>
                </div>
            </div>
            <div class="portlet-body">
                <div class="tabbable-line">
                    <ul class="nav nav-tabs nav-tabs-lg">
                        <li class="active">
                            <a href="#tab_1" data-toggle="tab"> <?php echo $this->lang->line('order_detail_tab_label');?>  </a>
                        </li>
                        <li>
                            <a href="#tab_2" data-toggle="tab"> <?php echo $this->lang->line('order_invoice_tab_label');?> 
                                <span class="badge badge-success">0</span>
                            </a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="tab_1">
                            <div class="row">
                                <div class="col-md-6 col-sm-12">
                                    <div class="portlet yellow-crusta box">
                                        <div class="portlet-title">
                                            <div class="caption">
                                                <i class="fa fa-cogs"></i><?php echo $this->lang->line('order_details_label');?>  </div>
                                        </div>
                                        <div class="portlet-body">
                                            <div class="row static-info">
                                                <div class="col-md-5 name"> <?php echo $this->lang->line('order_label'). '#';?>: </div>
                                                <div class="col-md-7 value"> <?php echo $item->id;?>
                                                    <!--<span class="label label-info label-sm"> Email confirmation was sent </span>-->
                                                </div>
                                            </div>
                                            <div class="row static-info">
                                                <div class="col-md-5 name"><?php echo $this->lang->line('order_date_time');?>:</div>
                                                <div class="col-md-7 value"> <?php echo formatDateTime($item->created_on, $this->display_date_full_frmt)?> </div>
                                            </div>
                                            <div class="row static-info">
                                                <div class="col-md-5 name"><?php echo $this->lang->line('order_status');?>: </div>
                                                <div class="col-md-7 value">
                                                    <span class="label label-success"> <?php echo $item->order_status;?> </span>
                                                </div>
                                            </div>
                                            <div class="row static-info">
                                                <div class="col-md-5 name"> <?php echo $this->lang->line('order_grand_total');?>: </div>
                                                <div class="col-md-7 value"> <?php echo $currency_codes_list[$item->currency_code]['code'].nbs().$item->total_price;?> </div>
                                            </div>
                                            <div class="row static-info">
                                                <div class="col-md-5 name"><?php echo $this->lang->line('order_payment_info');?>: </div>
                                                <div class="col-md-7 value"> Credit Card </div>
                                            </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6 col-sm-12">
                                        <div class="portlet blue-hoki box">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-cogs"></i><?php echo $this->lang->line('order_customer_info_label');?> </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="row static-info">
                                                    <div class="col-md-5 name"> <?php echo $this->lang->line('order_customer_name');?>: </div>
                                                    <div class="col-md-7 value"> <?php echo $item->user_fullname;?> </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name"> <?php echo $this->lang->line('order_customer_email');?>: </div>
                                                    <div class="col-md-7 value"> <?php echo $item->user_email;?>  </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name"> <?php echo $this->lang->line('order_customer_phone');?>: </div>
                                                    <div class="col-md-7 value"> <?php echo $item->phone;?>  </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name"> <?php echo $this->lang->line('order_customer_address');?>: </div>
                                                    <div class="col-md-7 value"> 
                                                     <?php  $address = $item->address; 
                                                            $address .= ($item->address1)? nbs().$item->address1:''; 
                                                            $address .= nbs().$item->address2;
                                                            echo $address .nbs().$item->city.nbs().$item->state_code.nbs().$item->country_code  ?>  </div>
                                                </div>
                                                <div class="row static-info">
                                                    <div class="col-md-5 name"> <?php echo $this->lang->line('order_customer_zip_code');?>: </div>
                                                    <div class="col-md-7 value">  <?php echo $item->zipcode;?> </div>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="portlet grey-cascade box">
                                            <div class="portlet-title">
                                                <div class="caption">
                                                    <i class="fa fa-cogs"></i><?php echo $this->lang->line('order_cart_description');?> 
                                                </div>
                                            </div>
                                            <div class="portlet-body">
                                                <div class="table-responsive">
                                                    <table class="table table-hover table-bordered table-striped">
                                                        <thead>
                                                            <tr>
                                                                <th> <?php echo $this->lang->line('order_description_lable');?> </th>
                                                                <th> <?php echo $this->lang->line('order_qty_label');?> </th>
                                                                <th> <?php echo $this->lang->line('order_unit_price_label');?> </th>
                                                                <th> <?php echo $this->lang->line('order_total_price_label');?> </th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <tr>
                                                                <td>
                                                                    <a href="javascript:;"> Hull Cleaning & Inspection (base rate)</a>
                                                                </td>
                                                                <td> 42 </td>
                                                                <td> $2.50 </td>
                                                                <td> $105.00 </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <a href="javascript:;"> Anode Installation (1-1/4" Shaft zinc anode)</a>
                                                                </td>
                                                                <td> 1 </td>
                                                                <td> $15.00 </td>
                                                                <td> $15.00 </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <a href="javascript:;"> Thruster Cleaning & Inspection</a>
                                                                </td>
                                                                <td> 1 </td>
                                                                <td> $25.00 </td>
                                                                <td> $25.00 </td>
                                                            </tr>
                                                            <tr>
                                                                <td>
                                                                    <a href="javascript:;"> Private Slip</a>
                                                                </td>
                                                                <td> 1 </td>
                                                                <td> $50.00 </td>
                                                                <td> $50.00 </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6"> </div>
                                    <div class="col-md-6">
                                        <div class="well">
                                            <div class="row static-info align-reverse">
                                                <div class="col-md-8 name"><?php echo $this->lang->line('order_sub_total_label');?> : </div>
                                                <div class="col-md-3 value"> $195.00 </div>
                                            </div>
                                            <div class="row static-info align-reverse">
                                                <div class="col-md-8 name"> <?php echo $this->lang->line('order_grand_total_label');?> : </div>
                                                <div class="col-md-3 value"> $195.00 </div>
                                            </div>
                                            <div class="row static-info align-reverse">
                                                <div class="col-md-8 name"> <?php echo $this->lang->line('order_payment_price_label');?> : </div>
                                                <div class="col-md-3 value"> $195.00</div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane" id="tab_2">
                                Invoices
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End: life time stats -->
        </div>
    </div>