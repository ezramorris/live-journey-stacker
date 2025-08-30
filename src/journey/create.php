<?php

require_once('../lib/common.php');
require_once('../config/config.php');
require_once('../lib/input.php');

# Starts a new journey URL and redirects to it.

$date = parse_date($_GET['date']);
$uid = parse_train_uid($_GET['uid']);
$board = parse_crs($_GET['board']);
$alight = parse_crs($_GET['alight']);

# We use a 2-digit year, but only care about 2000s (so not using 'y').
$date_string = substr($date->format('Ymd'), 2);
$journey_string = join('', ['T', $date_string, $uid, $board, $alight]);
$params = [
    'j' => $journey_string
];
$query = http_build_query($params);
$url = '.?' . $query;

header('Location: ' . $url, true, 303);