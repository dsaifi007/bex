<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$html = $email_template->getEmailParagraph(sprintf($this->lang->line('greeting_dear'), ($user_fullname)? $user_fullname:'User'));
$html .= $email_template->getEmailParagraph($this->lang->line('subscription_user_email_para'));
$html .= $email_template->getEmailParagraph($this->lang->line('subscription_user_email_para1'));
$html .= $email_template->getEmailParagraph($this->lang->line('thank_you_label'));
echo $html;
?>
