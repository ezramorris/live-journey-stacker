<?php

require_once('smarty/libs/Smarty.class.php');
require_once('status.php');

use Smarty\Smarty;

class Leg {
    public function __construct(public TrainLegStatus $status){}
}

$smarty = new Smarty();
$smarty->setCaching(Smarty::CACHING_OFF);

$date = new DateTimeImmutable('now', new DateTimeZone('Europe/London'));
$legs = array(
    new Leg(get_train_leg_status('Y07095', $date, 'DBY', 'STP')),
    new Leg(get_train_leg_status('Y02202', $date, 'RET', 'SHF'))
);

$smarty->assign('legs', $legs);
$smarty->display('index.tpl');