<?php

# Setup code for all modules.

require_once(__DIR__.'/smarty/libs/Smarty.class.php');

use Smarty\Smarty;

# $smarty is made available to all pages for rendering.
$smarty = new Smarty();
$smarty->setTemplateDir(__DIR__.'/../templates');
$smarty->setConfigDir(__DIR__.'/../configs');
$smarty->setCompileDir(__DIR__.'/../templates_c');
$smarty->setCacheDir(__DIR__.'/../cache');
$smarty->setEscapeHtml(true);
if (getenv('DEBUG')) {
    $smarty->setDebugging(true);
    $smarty->assign('request', $_REQUEST);
}