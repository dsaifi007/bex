<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/*
  |--------------------------------------------------------------------------
  | ACL Configurations
  |--------------------------------------------------------------------------
  |
 */

$config['acl_powerful_grps'] = [1];
$config['acl_public_access_level_id'] = [1];
$config['acl_content_types'] = ['Controller' => 1, 'Method' => 2];
$config['default_register_groups'] = [6];
$config['allow_user_registration'] = FALSE;

/*
  |--------------------------------------------------------------------------
  | Meadia Paths
  |--------------------------------------------------------------------------
  |
 */
$config['themes_grp'] = ['metronic', 'bex'];
$config['upload_folder'] = 'uploads/';
$config['theme_folder'] = 'themes/';
$config['default_theme'] = 'metronic';
$config['theme_path'] = $config['theme_folder'] . $config['default_theme'] . '/';
$config['global_module_path'] = APPPATH . 'modules/';

$config['asset_folder'] = 'assets/';
$config['asset_images_folder'] = $config['asset_folder'].'images/';
$config['asset_files_folder'] = $config['asset_folder'].'files/';
$config['asset_videos_folder'] = $config['asset_folder'].'videos/';

/*
  |--------------------------------------------------------------------------
  | Shared Config Initialiazation
  |--------------------------------------------------------------------------
  |
 */

$config['current_app'] = 'master';
$config['master_app'] = 'master';
$config['front_app'] = 'bex';

$config['timestamp_date_format'] = 'Y-m-d H:i:s';
$config['display_date_format'] = 'M j, Y';
$config['display_date_format_full'] = 'M j, Y h:i A';
$config['global_list_limit_box'] = 1;
$config['global_list_limit'] = 10;
$config['boolean_arr'] = [1 => 'Yes', 0 => 'No'];
$config['reset_pwd_token_length'] = 16;
$config['reset_pwd_token_expire'] = 30; // minutes

$config['caching_prefix'] = 'Fl9#76uTa9X42Lr_';
$config['cache_key_separator'] = '___';
$config['cache_micro_ttl'] = 600;
$config['cache_vshort_ttl'] = 1800;
$config['cache_short_ttl'] = 3600;
$config['cache_mid_ttl'] = 7200;
$config['cache_long_ttl'] = 18000;
$config['cache_vlong_ttl'] = 36000;
$config['cache_day_ttl'] = 86400;
$config['cache_2days_ttl'] = 172800;
$config['cache_week_ttl'] = 604800;
$config['apc_cache'] = FALSE;
$config['cache_positions'] = TRUE;
$config['cache_positions_prefix'] = 'positions_';

$config['cache_dependecny_list'] = [
    'acl_access_actions'=>['acl_access_actions_functions_map*'],
    'acl_access_levels'=>['menu_items*', 'acl_content*', 'acl_access_actions_functions_map*'],
    //'banner_categories'=>['banners*'],
    'domains'=>['acl_content*', 'acl_access_actions_functions_map*', 'banners*', 'banner_categories*', 'extensions*', 'extensions_positions*', 'posts_categories*', 'posts*', 'testimonials_categories*', 'testimonials*', 'text_widgets*', 'menu_items*', 'menu_types*'],
    'extensions_positions'=>['extensions*'],
    'menu_types'=>['menu_items*'],
    'posts_categories'=>['posts*'],
    //'testimonials_categories'=>['testimonials*'],
    'users_groups'=>['users*']
];

$config['overwrite_theme'] = ['theme'];
$config['read_more_char_length'] = 300;

$config['max_upload_limit_files'] = '2100'; // KB
$config['max_image_width'] = '3500'; // px
$config['max_image_height'] = '3500'; // px

/*
  |--------------------------------------------------------------------------
  | API Settings
  |--------------------------------------------------------------------------
  |
 */

$config['google_map_api_key'] = 'AIzaSyCSfvJsKW4PKmfp0EHmM0E2KHO9Bn5aZyM';

/*
  |--------------------------------------------------------------------------
  | Basic Settings
  |--------------------------------------------------------------------------
  |
 */

$config['app_timezone_diff_from_GMT'] = '+01:00';
$config['countries_list'] = ["AF"=>"Afghanistan","AX"=>"Åland Islands","AL"=>"Albania","DZ"=>"Algeria","AS"=>"American Samoa","AD"=>"Andorra","AO"=>"Angola","AI"=>"Anguilla","AQ"=>"Antarctica","AG"=>"Antigua and Barbuda","AR"=>"Argentina","AM"=>"Armenia","AW"=>"Aruba","AU"=>"Australia","AT"=>"Austria","AZ"=>"Azerbaijan","BS"=>"Bahamas","BH"=>"Bahrain","BD"=>"Bangladesh","BB"=>"Barbados","BY"=>"Belarus","BE"=>"Belgium","BZ"=>"Belize","BJ"=>"Benin","BM"=>"Bermuda","BT"=>"Bhutan","BO"=>"Bolivia, Plurinational State of","BQ"=>"Bonaire, Sint Eustatius and Saba","BA"=>"Bosnia and Herzegovina","BW"=>"Botswana","BV"=>"Bouvet Island","BR"=>"Brazil","IO"=>"British Indian Ocean Territory","BN"=>"Brunei Darussalam","BG"=>"Bulgaria","BF"=>"Burkina Faso","BI"=>"Burundi","KH"=>"Cambodia","CM"=>"Cameroon","CA"=>"Canada","CV"=>"Cape Verde","KY"=>"Cayman Islands","CF"=>"Central African Republic","TD"=>"Chad","CL"=>"Chile","CN"=>"China","CX"=>"Christmas Island","CC"=>"Cocos (Keeling) Islands","CO"=>"Colombia","KM"=>"Comoros","CG"=>"Congo","CD"=>"Congo, the Democratic Republic of the","CK"=>"Cook Islands","CR"=>"Costa Rica","CI"=>"Côte d'Ivoire","HR"=>"Croatia","CU"=>"Cuba","CW"=>"Curaçao","CY"=>"Cyprus","CZ"=>"Czech Republic","DK"=>"Denmark","DJ"=>"Djibouti","DM"=>"Dominica","DO"=>"Dominican Republic","EC"=>"Ecuador","EG"=>"Egypt","SV"=>"El Salvador","GQ"=>"Equatorial Guinea","ER"=>"Eritrea","EE"=>"Estonia","ET"=>"Ethiopia","FK"=>"Falkland Islands (Malvinas)","FO"=>"Faroe Islands","FJ"=>"Fiji","FI"=>"Finland","FR"=>"France","GF"=>"French Guiana","PF"=>"French Polynesia","TF"=>"French Southern Territories","GA"=>"Gabon","GM"=>"Gambia","GE"=>"Georgia","DE"=>"Germany","GH"=>"Ghana","GI"=>"Gibraltar","GR"=>"Greece","GL"=>"Greenland","GD"=>"Grenada","GP"=>"Guadeloupe","GU"=>"Guam","GT"=>"Guatemala","GG"=>"Guernsey","GN"=>"Guinea","GW"=>"Guinea-Bissau","GY"=>"Guyana","HT"=>"Haiti","HM"=>"Heard Island and McDonald Islands","VA"=>"Holy See (Vatican City State)","HN"=>"Honduras","HK"=>"Hong Kong","HU"=>"Hungary","IS"=>"Iceland","IN"=>"India","ID"=>"Indonesia","IR"=>"Iran, Islamic Republic of","IQ"=>"Iraq","IE"=>"Ireland","IM"=>"Isle of Man","IL"=>"Israel","IT"=>"Italy","JM"=>"Jamaica","JP"=>"Japan","JE"=>"Jersey","JO"=>"Jordan","KZ"=>"Kazakhstan","KE"=>"Kenya","KI"=>"Kiribati","KP"=>"Korea, Democratic People's Republic of","KR"=>"Korea, Republic of","KW"=>"Kuwait","KG"=>"Kyrgyzstan","LA"=>"Lao People's Democratic Republic","LV"=>"Latvia","LB"=>"Lebanon","LS"=>"Lesotho","LR"=>"Liberia","LY"=>"Libya","LI"=>"Liechtenstein","LT"=>"Lithuania","LU"=>"Luxembourg","MO"=>"Macao","MK"=>"Macedonia, the former Yugoslav Republic of","MG"=>"Madagascar","MW"=>"Malawi","MY"=>"Malaysia","MV"=>"Maldives","ML"=>"Mali","MT"=>"Malta","MH"=>"Marshall Islands","MQ"=>"Martinique","MR"=>"Mauritania","MU"=>"Mauritius","YT"=>"Mayotte","MX"=>"Mexico","FM"=>"Micronesia, Federated States of","MD"=>"Moldova, Republic of","MC"=>"Monaco","MN"=>"Mongolia","ME"=>"Montenegro","MS"=>"Montserrat","MA"=>"Morocco","MZ"=>"Mozambique","MM"=>"Myanmar","NA"=>"Namibia","NR"=>"Nauru","NP"=>"Nepal","NL"=>"Netherlands","NC"=>"New Caledonia","NZ"=>"New Zealand","NI"=>"Nicaragua","NE"=>"Niger","NG"=>"Nigeria","NU"=>"Niue","NF"=>"Norfolk Island","MP"=>"Northern Mariana Islands","NO"=>"Norway","OM"=>"Oman","PK"=>"Pakistan","PW"=>"Palau","PS"=>"Palestinian Territory, Occupied","PA"=>"Panama","PG"=>"Papua New Guinea","PY"=>"Paraguay","PE"=>"Peru","PH"=>"Philippines","PN"=>"Pitcairn","PL"=>"Poland","PT"=>"Portugal","PR"=>"Puerto Rico","QA"=>"Qatar","RE"=>"Réunion","RO"=>"Romania","RU"=>"Russian Federation","RW"=>"Rwanda","BL"=>"Saint Barthélemy","SH"=>"Saint Helena, Ascension and Tristan da Cunha","KN"=>"Saint Kitts and Nevis","LC"=>"Saint Lucia","MF"=>"Saint Martin (French part)","PM"=>"Saint Pierre and Miquelon","VC"=>"Saint Vincent and the Grenadines","WS"=>"Samoa","SM"=>"San Marino","ST"=>"Sao Tome and Principe","SA"=>"Saudi Arabia","SN"=>"Senegal","RS"=>"Serbia","SC"=>"Seychelles","SL"=>"Sierra Leone","SG"=>"Singapore","SX"=>"Sint Maarten (Dutch part)","SK"=>"Slovakia","SI"=>"Slovenia","SB"=>"Solomon Islands","SO"=>"Somalia","ZA"=>"South Africa","GS"=>"South Georgia and the South Sandwich Islands","SS"=>"South Sudan","ES"=>"Spain","LK"=>"Sri Lanka","SD"=>"Sudan","SR"=>"Suriname","SJ"=>"Svalbard and Jan Mayen","SZ"=>"Swaziland","SE"=>"Sweden","CH"=>"Switzerland","SY"=>"Syrian Arab Republic","TW"=>"Taiwan, Province of China","TJ"=>"Tajikistan","TZ"=>"Tanzania, United Republic of","TH"=>"Thailand","TL"=>"Timor-Leste","TG"=>"Togo","TK"=>"Tokelau","TO"=>"Tonga","TT"=>"Trinidad and Tobago","TN"=>"Tunisia","TR"=>"Turkey","TM"=>"Turkmenistan","TC"=>"Turks and Caicos Islands","TV"=>"Tuvalu","UG"=>"Uganda","UA"=>"Ukraine","AE"=>"United Arab Emirates","GB"=>"United Kingdom","US"=>"United States","UM"=>"United States Minor Outlying Islands","UY"=>"Uruguay","UZ"=>"Uzbekistan","VU"=>"Vanuatu","VE"=>"Venezuela, Bolivarian Republic of","VN"=>"Viet Nam","VG"=>"Virgin Islands, British","VI"=>"Virgin Islands, U.S.","WF"=>"Wallis and Futuna","EH"=>"Western Sahara","YE"=>"Yemen","ZM"=>"Zambia","ZW"=>"Zimbabwe"];
$config['time_frequency'] = [1=>'Monthly', 3=>'Quarterly', 6=>'Half Yearly', 12=>'Yearly'];
//$config['extension_types'] = [1=>'Text Widgets', 2=>'Menus', 3=>'Banners', 4=>'Testimonials', 5=>'Posts Category', 6=>'Custom Block', 7=>'Single Post'];
$config['extension_types'] = [1=>'Text Widgets', 2=>'Menus', 3=>'Posts Category', 4=>'Custom Block', 5=>'Single Post'];
$config['currency_codes_list']= ['EUR'=>['name'=>'Euro', 'code'=>'&euro;'], 'USD'=>['name'=>'US Dollar', 'code'=>'&#36;']];
//$config['field_types']= [1=>'Dropdown', 2=>'Text', 3=>'Radio', 4=>'Image', 5=>'Checkbox'];
$config['field_types']= [ 3=>'Radio', 4=>'Image', 5=>'Checkbox'];
$config['toxic_backend'] = ['0'=> 'NON-TOXIC', '1'=> 'TOXIC', '2'=> 'TOXIC & NON-TOXIC'];

/*
  |--------------------------------------------------------------------------
  | Email Config Initialiazation
  |--------------------------------------------------------------------------
  |
 */
$config['from_email'] = 'harshal@rnftechnologies.com';
$config['from_name'] = 'BEX';

$config['email_setup'] = array(
    'protocol' => 'smtp', // smtp/sendmail
    //'mailpath' => '/usr/sbin/sendmail',
    'smtp_host' => 'smtp.mailgun.org', //change this
    'smtp_port' => '587',
    'smtp_user' => 'postmaster@agilistechlabs.com', //change this
    'smtp_pass' => '9cac57272960be872a0f01022b19ce52', //change this
    'mailtype' => 'html',
    'charset' => 'iso-8859-1',
    'wordwrap' => TRUE,
    'newline' => "\r\n"
);

$config['new_account_email_notification_to_user'] = TRUE;
$config['new_account_email_notification_to_admins'] = TRUE;
