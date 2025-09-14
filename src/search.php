<?php

require_once('lib/common.php');
require_once('lib/input.php');
require_once('lib/search_trains.php');
require_once('config/config.php');

$date_str = $_GET['date'] ?? '';
$time_str = $_GET['time'] ?? '';
$from_str = $_GET['from'] ?? '';
$to_str = $_GET['to'] ?? '';

if ($date_str && $time_str && ($from_str || $to_str)) {
    $date_time = parse_date_time($date_str, $time_str);
    $from = $from_str ? parse_crs($from_str) : null;
    $to = $to_str ? parse_crs($to_str) : null;

    $res = search_trains($date_time, $from, $to);
    $smarty->assign('res', $res);
} else {
    $date_time = new DateTimeImmutable('now', TIMEZONE);
}

$smarty->assign('journey_string', $_GET['j'] ?? '');
$smarty->assign('position', $_GET['pos'] ?? '0');
$smarty->assign('datetime', $date_time);
$smarty->assign('from', $from_str);
$smarty->assign('to', $to_str);
$smarty->display('search.tpl');