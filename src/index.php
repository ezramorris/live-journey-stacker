<?php

require_once('lib/common.php');
require_once('config/config.php');

$smarty->assign('journey_string', '');
$smarty->assign('position', '0');
$smarty->assign('datetime', new DateTimeImmutable('now', TIMEZONE));
$smarty->display('search.tpl');