<?php

require_once('../lib/common.php');
require_once('../config/config.php');
require_once('../lib/input.php');

# Add a leg to journey string, then redirect to it.

$journey_string = $_GET['j'];
$leg_strings = split_journey($journey_string);
$position = parse_int(value: $_GET['pos'], min:0, max: count($leg_strings));

$date = parse_date($_GET['date']);
$uid = parse_train_uid($_GET['uid']);
$board = parse_crs($_GET['board']);
$alight = parse_crs($_GET['alight']);

# We use a 2-digit year, but only care about 2000s (so not using 'y').
$date_string = substr($date->format('Ymd'), 2);
$new_leg = join('', ['T', $date_string, $uid, $board, $alight]);
array_splice($leg_strings, $position, 0, [$new_leg]);
$journey_string = join('-', $leg_strings);

$params = [
    'j' => $journey_string
];
$query = http_build_query($params);
$url = '.?'.$query;

header('Location: '.$url, true, 303);