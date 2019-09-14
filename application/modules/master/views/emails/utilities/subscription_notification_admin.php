<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$html = $email_template->getEmailParagraph($this->lang->line('greeting_admin'));
$html .= $email_template->getEmailParagraph($this->lang->line('subscription_admin_email_para'));
$html .= $email_template->getEmailParagraph(sprintf($this->lang->line('subscription_admin_email_info'), $email_template->getEmailBoldText($user_email)));
if($user_fullname !='') {
    $html .= $email_template->getEmailParagraph(sprintf($this->lang->line('subscription_admin_name_info'), $email_template->getEmailBoldText($user_fullname)));
}
$html .= $email_template->getEmailParagraph($this->lang->line('thank_you_label'));
echo $html;
?>
