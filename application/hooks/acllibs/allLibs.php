<?php

namespace aclLibs;

defined('BASEPATH') OR exit('No direct script access allowed');

$prePath = '';
$includeLibs = ['domain', 'aclcontent', 'authentication'];
foreach ($includeLibs as $val) : require_once($prePath . $val. '.php'); endforeach;

trait AllLibs {

    use Domain, Aclcontent, Authentication;
}

?>