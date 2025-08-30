<?php

require_once('lib/common.php');

$smarty->assign('journey_string', $_GET['j']);
$smarty->assign('position', $_GET['pos']);
$smarty->display('search.tpl');