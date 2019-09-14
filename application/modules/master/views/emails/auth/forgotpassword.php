<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$html = $email_template->getEmailParagraph('Hi,');
$html .= $email_template->getEmailParagraph(sprintf($this->lang->line('forgot_pwd_email_msg_para'), $email_template->sitename));
$html .= $email_template->getEmailParagraph($this->lang->line('forgot_pwd_email_msg_token').$email_template->getEmailBoldText($token));
$html .= $email_template->getEmailParagraph($this->lang->line('thank_you_label'));
echo $html;
?>
