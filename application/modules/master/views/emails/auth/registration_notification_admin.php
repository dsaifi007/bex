<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$html = $email_template->getEmailParagraph($this->lang->line('greeting_admin'));
$html .= $email_template->getEmailParagraph($this->lang->line('registration_admin_email_para'));
$html .= $email_template->getEmailParagraph(sprintf($this->lang->line('registration_name_info'), $email_template->getEmailBoldText($user_fullname)));
$html .= $email_template->getEmailParagraph(sprintf($this->lang->line('registration_email_info'), $email_template->getEmailBoldText($user_email)));
$html .= $email_template->getEmailParagraph($this->lang->line('thank_you_label'));
echo $html;
?>
