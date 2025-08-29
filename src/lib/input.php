<?php

# Functions for validating and parsing user input.
# Each of these will return parsed value, or return 400 and exit execution on failure.

require_once(__DIR__ . '/journey_model.php');

define('CRS_REGEX', '[A-Z0-9]{3}');
define('TRAIN_UID_REGEX', '[A-Z0-9]{6}');

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
    validate_regexp_or_die($crs, '/^' . CRS_REGEX . '$/');
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
    # TODO: make this parse more than one leg.
    validate_regexp_or_die($legs_string, '/^T-' . TRAIN_UID_REGEX . '-' . CRS_REGEX . '-' . CRS_REGEX . '$/');
    [$type, $uid, $board, $alight] = explode('-', $legs_string, 4);
    return array(
        new TrainLeg($uid, $board, $alight)
    );
}