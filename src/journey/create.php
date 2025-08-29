<?php

require_once('../lib/common.php');
require_once('../config/config.php');
require_once('../lib/input.php');

# Starts a new journey URL and redirects to it.

$date = parse_date($_GET['date']);
$uid = parse_train_uid($_GET['uid']);
$board = parse_crs($_GET['board']);
$alight = parse_crs($_GET['alight']);

$journey_string = join('-', ['T', $uid, $board, $alight]);
$params = [
    'd' => $date->format('Y-m-d'),
    'j' => $journey_string
];
$query = http_build_query($params);
$url = '.?' . $query;

header('Location: ' . $url, true, 303);