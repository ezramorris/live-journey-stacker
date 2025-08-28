<?php

require_once('lib/common.php');
require_once('lib/input.php');
require_once('lib/status.php');


class Leg {
    public function __construct(public TrainLegStatus $status){}
}


# Validate GET params & set vars.
$date = parse_date($_GET['date']);
$uid = parse_train_uid($_GET['uid']);
$board = parse_crs($_GET['board']);
$alight = parse_crs($_GET['alight']);


$legs = array(
    new Leg(get_train_leg_status($uid, $date, $board, $alight))
);

$smarty->assign('legs', $legs);
$smarty->assign('get', $_GET);
$smarty->display('journey.tpl');