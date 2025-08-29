<?php

# Setup code for all modules.

require_once(__DIR__.'/smarty/libs/Smarty.class.php');

use Smarty\Smarty;

# $smarty is made available to all pages for rendering.
$smarty = new Smarty();
$smarty->setEscapeHtml(true);
if (getenv('DEBUG')) {
    $smarty->setDebugging(true);
    $smarty->assign('request', $_REQUEST);
}