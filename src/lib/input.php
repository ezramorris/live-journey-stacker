<?php

# Functions for validating and parsing user input.
# Each of these will return parsed value, or return 400 and exit execution on failure.

require_once(__DIR__ . '/journey_model.php');

define('TRAIN_CRS_REGEX', '[A-Z]{3}');
define('TRAIN_UID_REGEX', '[A-Z0-9]{6}');
define('TRAIN_LEG_REGEX', 'T-' . TRAIN_UID_REGEX . '-' . TRAIN_CRS_REGEX . '-' . TRAIN_CRS_REGEX);

function die_with_400() {
    # Sends a 400 to the user then dies.
    # TODO: render a proper error page.

    http_response_code(400);
    exit('Bad input');
}

function validate_or_die(string $value, int $filter, array|int $options=0) {
    # Validates an input against a specified filter (see filter_var doc).
    # If filter fails, then send a 400.
    if (!filter_var($value, $filter, $options)) {
        die_with_400();
    }
}

function validate_regexp_or_die(string $value, string $regexp) {
    validate_or_die($value, FILTER_VALIDATE_REGEXP, ['options'=>['regexp'=>$regexp]]);
}

function parse_train_uid(string $uid) {
    validate_regexp_or_die($uid, '/^' . TRAIN_UID_REGEX . '$/');
    return $uid;
}

function parse_crs(string $crs) {
    validate_regexp_or_die($crs, '/^' . TRAIN_CRS_REGEX . '$/');
    return $crs;
}

function parse_date(string $date_string) {
    $date = DateTimeImmutable::createFromFormat('Y-m-d', $date_string, TIMEZONE);
    if (!$date) {
        die_with_400();
    }
    return $date;
}

function parse_legs(string $legs_string) {
    # Parses a legs parameter into an array of Legs.
    $re = '/^' . TRAIN_LEG_REGEX . '(?:_' . TRAIN_LEG_REGEX .  ')*$/';
    validate_regexp_or_die($legs_string, $re);
    $leg_strings = explode('_', $legs_string);
    $legs = array();
    foreach ($leg_strings as $string) {
        [$type, $uid, $board, $alight] = explode('-', $string, 4);
        $legs[] = new TrainLeg($uid, $board, $alight);
    }
    return $legs;
}