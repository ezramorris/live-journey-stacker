<?php

require_once('lib/common.php');
require_once('lib/input.php');
require_once('lib/status.php');

# Validate GET params & set vars.
$date = parse_date($_GET['date']);
$uid = parse_train_uid($_GET['uid']);
$board = parse_crs($_GET['board']);
$alight = parse_crs($_GET['alight']);


$legs = array(
    get_train_leg($uid, $date, $board, $alight)
);

$smarty->assign('legs', $legs);
$smarty->assign('get', $_GET);
$smarty->display('journey.tpl');