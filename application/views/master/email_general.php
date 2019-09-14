<?php

defined('BASEPATH') OR exit('No direct script access allowed');

if (!class_exists('EmailGeneral')) :

    class EmailGeneral {

        protected $cmnStyle = "font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0; ";
        protected $cmnStyle1 = "font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 14px; margin: 0 auto; ";
        protected $cmnStyle2 = "font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; box-sizing: border-box; font-size: 16px; margin: 0; ";
        protected $Eml_wdth = 700;
        protected $linkStyle;
        protected $v_algn_tp = 'vertical-align: top; ';
        public $sitename;
        public $siteurl;
        protected $heading;

        public function __construct() {
            $this->linkStyle = $this->cmnStyle . 'color: #FFF; text-decoration: none; line-height: 2; font-weight: bold; text-align: center; cursor: pointer; display: inline-block; border-radius: 5px; text-transform: capitalize; background: #348eda; border-color: #348eda; border-style: solid; border-width: 10px 20px; ';
        }

        public function setVal($property, $value = '') {
            if (property_exists($this, $property)) :
                $this->$property = $value;
            endif;
        }

        public function getEmailParagraph($text) {
            $html = '<tr style="' . $this->cmnStyle . '">';
            $html .= '<td class="content-block" style="' . $this->cmnStyle . $this->v_algn_tp . 'padding: 0 0 20px;" valign="top">';
            $html .= $text;
            $html .= '</td>';
            $html .= '</tr>';
            return $html;
        }

        public function getEmailLinkBtn($link, $label) {
            $html = '<a href="' . $link . '" class="btn-primary" style="' . $this->linkStyle . '">' . $label . '</a>';
            return $html;
        }

        public function getEmailBoldText($text) {
            $html = '<strong style="' . $this->cmnStyle . '">' . $text . '</strong>';
            return $html;
        }

        public function getEmailBodyHeader() {
            $html = '<table class="body-wrap" style="' . $this->cmnStyle . 'width: 100%; background: #f6f6f6;">';
            $html .= '<tr style="' . $this->cmnStyle . '">';
            $html .= '<td style="' . $this->cmnStyle . $this->v_algn_tp . '" valign="top"></td>';
            $html .= '<td class="container" width="' . $this->Eml_wdth . '" style="' . $this->cmnStyle1 . $this->v_algn_tp . 'max-width: ' . $this->Eml_wdth . 'px !important; clear: both !important;" valign="top">';
            $html .= '<div class="content" style="' . $this->cmnStyle1 . 'max-width: ' . $this->Eml_wdth . 'px; display: block;padding: 20px;">';
            $html .= '<table class="main" width="100%" cellpadding="0" cellspacing="0" style="' . $this->cmnStyle . 'border-radius: 3px; background: #fff; border: 1px solid #e9e9e9;">';
            $html .= '<tr style="' . $this->cmnStyle . '">';
            $html .= '<td class="alert alert-warning" style="' . $this->cmnStyle2 . $this->v_algn_tp . 'color: #fff; font-weight: 500; text-align: center; border-radius: 3px 3px 0 0; background: #FF9F00; padding: 20px;" align="center" valign="top">' . $this->heading . '</td>';
            $html .= '</tr>';
            $html .= '<tr style="' . $this->cmnStyle . '">';
            $html .= '<td class="content-wrap" style="' . $this->cmnStyle . $this->v_algn_tp . 'padding: 20px;" valign="top">';
            $html .= '<table width="100%" cellpadding="0" cellspacing="0" style="' . $this->cmnStyle . 'line-height:1.5;">';
            return $html;
        }

        public function getEmailBodyFooter($regards_link = 0) {

            $thank_msg = ($regards_link)? '<p>Best Regards,</p><p>' . $this->getEmailLinkBtn($this->siteurl, $this->sitename).'</p>':'Best Regards,<br />' . $this->getEmailBoldText($this->sitename);
            $html = $this->getEmailParagraph($thank_msg);

            $html .= '</table></td></tr></table></div></td>';
            $html .= '<td style="' . $this->cmnStyle . $this->v_algn_tp . '" valign="top"></td>';
            $html .= '</tr></table>';

            return $html;
        }

    }

    endif;

$siteURL = base_url();
$siteURL .= ($this->config->item('index_page')) ? $this->config->item('index_page') . '/' : '';
$siteURL .= $this->current_app.'/';

$site_name = $this->lang->line('site_name');

$email_template = new EmailGeneral();
$email_template->setVal('sitename', $site_name);
$email_template->setVal('siteurl', $siteURL);
$email_template->setVal('heading', $email_header);

$data['email_template'] = $email_template;
$view = $this->current_app.'/views/emails/'.$email_view_name.'.php';

echo $email_template->getEmailBodyHeader();
if (file_exists($this->config->item('global_module_path').$view)) : 
    $this->load->view('emails/' . $email_view_name, $data);
endif;

$regards_link = (isset($regards_link))? $regards_link:0;
echo $email_template->getEmailBodyFooter($regards_link);
unset($email_template);
?>


