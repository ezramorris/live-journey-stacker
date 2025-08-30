<?php

require_once('../lib/common.php');
require_once('../lib/input.php');
require_once('../lib/status.php');
require_once('../lib/journey_model.php');

# Validate GET params & set vars.
$legs = parse_legs($_GET['j']);
$journey = new Journey($legs);

$leg_statuses = array_map(
    fn(Leg $leg) => get_train_leg_status($leg),
    $legs
);

$smarty->assign('legs', $leg_statuses);
$smarty->display('journey.tpl');