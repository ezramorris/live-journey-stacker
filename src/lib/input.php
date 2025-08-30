<?php

# Functions for validating and parsing user input.
# Each of these will return parsed value, or return 400 and exit execution on failure.

require_once(__DIR__ . '/journey_model.php');

define('TRAIN_CRS_REGEX', '[A-Z]{3}');
define('TRAIN_UID_REGEX', '[A-Z0-9]{6}');

function die_with_400(string $description) {
    # Sends a 400 to the user then dies.
    # TODO: render a proper error page.

    http_response_code(400);
    exit('Bad input: ' . $description);
}

function validate_or_die(string $value, int $filter, array|int $options, string $error_description) {
    # Validates an input against a specified filter (see filter_var doc).
    # If filter fails, then send a 400.
    if (!filter_var($value, $filter, $options)) {
        die_with_400($error_description);
    }
}

function validate_regexp_or_die(string $value, string $regexp, string $error_description) {
    validate_or_die($value, FILTER_VALIDATE_REGEXP, ['options'=>['regexp'=>$regexp]],
                    $error_description);
}

function parse_train_uid(string $uid) {
    validate_regexp_or_die($uid, '/^' . TRAIN_UID_REGEX . '$/', 'invalid UID');
    return $uid;
}

function parse_crs(string $crs) {
    validate_regexp_or_die($crs, '/^' . TRAIN_CRS_REGEX . '$/', 'invalid CRS');
    return $crs;
}

function parse_date(string $date_string) {
    $date = DateTimeImmutable::createFromFormat('Y-m-d', $date_string, TIMEZONE);
    $year = (int)($date->format('Y'));
    if (!$date or $year < 2000 or $year > 2099) {
        die_with_400('invalid date');
    }
    return $date;
}

function parse_legs(string $legs_string) {
    # Parses a legs parameter into an array of Legs.
    $leg_strings = explode('-', $legs_string);
    $legs = array();
    foreach ($leg_strings as $string) {
        if (strlen($string) != 19) {
            die_with_400('leg string wrong length');
        }

        $type = substr($string, 0, 1);
        if ($type != 'T') {
            die_with_400('unknown leg type');
        }

        $date_str = substr($string, 1, 6);
        $date = DateTimeImmutable::createFromFormat('Ymd', '20' . $date_str) or die_with_400('invalid date');

        $uid = parse_train_uid(substr($string, 7, 6));
        $board_crs = parse_crs(substr($string, 13, 3));
        $alight_crs = parse_crs(substr($string, 16, 3));


        $legs[] = new TrainLeg($date, $uid, $board_crs, $alight_crs);
    }
    return $legs;
}