<?php

# Setup code for all modules.

require_once(__DIR__.'/../vendor/autoload.php');

use Smarty\Smarty;

# $smarty is made available to all pages for rendering.
$smarty = new Smarty();
$smarty->setTemplateDir(__DIR__.'/../templates');
$smarty->setConfigDir(__DIR__.'/../configs');
$smarty->setCompileDir(__DIR__.'/../templates_c');
$smarty->setCacheDir(__DIR__.'/../cache');
$smarty->setEscapeHtml(true);

# URL path where application is installed.
$smarty->assign('base_path', $_ENV['BASE_PATH'] ?? '/ljs/');

if (getenv('DEBUG')) {
    $smarty->setDebugging(true);
    $smarty->assign('request', $_REQUEST);
}