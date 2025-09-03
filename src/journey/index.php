<?php

require_once('../lib/common.php');
require_once('../lib/input.php');
require_once('../lib/status.php');
require_once('../lib/journey_model.php');

# Validate GET params & set vars.
$journey_string = $_GET['j'];
$leg_strings = split_journey($journey_string);
$legs = parse_legs($leg_strings);
$journey = new Journey($legs);

$leg_statuses = get_train_leg_statuses($legs);

$smarty->assign('legs', $leg_statuses);
$smarty->assign('journey_string', $journey_string);
$smarty->display('journey.tpl');