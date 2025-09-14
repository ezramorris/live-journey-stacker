<?php

# Functions for validating and parsing user input.
# Each of these will return parsed value, or return 400 and exit execution on failure.

require_once(__DIR__.'/../config/config.php');
require_once(__DIR__.'/journey_model.php');

define('TRAIN_CRS_REGEX', '[A-Z]{3}');
define('TRAIN_UID_REGEX', '[A-Z0-9]{6}');

function die_with_400(string $description) {
    # Sends a 400 to the user then dies.
    # TODO: render a proper error page.

    http_response_code(400);
    exit('Bad input: ' . $description);
}

function validate_or_die(string $value, int $filter, array $options, string $error_description) {
    # Validates an input against a specified filter (see filter_var doc).
    # If successful, returns the result.
    # If filter fails, then send a 400.

    # Set failure result to null to differentiate with valid false-y results.
    $options['flags'] = ($options['flags'] ?? 0) | FILTER_NULL_ON_FAILURE;
    $result = filter_var($value, $filter, $options);
    if (is_null($result)) {
        die_with_400($error_description);
    }
    return $result;
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
    if (!$date) {
        die_with_400('invalid date');
    }
    $year = (int)($date->format('Y'));
    if ($year < 2000 or $year > 2099) {
        die_with_400('year out of range');
    }
    return $date;
}

function parse_date_time(string $date_string, string $time_string) {
    $date = DateTimeImmutable::createFromFormat(
        'Y-m-dH:i', 
        $date_string . $time_string, 
        TIMEZONE
    );
    if (!$date) {
        die_with_400('invalid date');
    }
    $year = (int)($date->format('Y'));
    if ($year < 2000 or $year > 2099) {
        die_with_400('year out of range');
    }
    return $date;
}

function parse_int(string $value, ?int $min=null, ?int $max=null) {
    $options = [];
    if (!is_null($min)) {
        $options['min_range'] = $min;
    }
    if (!is_null($max)) {
        $options['max_range'] = $max;
    }
    return validate_or_die($value, FILTER_VALIDATE_INT, ['options'=>$options], 'invalid int');
}

function split_journey(string $journey_string) {
    # Splits a journey string into legs.

    # If empty string, explode() returns an array with an empty string in.
    if (!$journey_string) {
        return [];
    }

    return explode('-', $journey_string);
}

function parse_leg(string $leg_string) {
        if (strlen($leg_string) != 19) {
            die_with_400('leg string wrong length');
        }

        $type = substr($leg_string, 0, 1);
        if ($type != 'T') {
            die_with_400('unknown leg type');
        }

        $date_str = substr($leg_string, 1, 6);
        $date = DateTimeImmutable::createFromFormat(
            'Ymd', '20'.$date_str, TIMEZONE
        ) or die_with_400('invalid date');

        $uid = parse_train_uid(substr($leg_string, 7, 6));
        $board_crs = parse_crs(substr($leg_string, 13, 3));
        $alight_crs = parse_crs(substr($leg_string, 16, 3));


        return new TrainLeg($date, $uid, $board_crs, $alight_crs);
}

function parse_legs(array $leg_strings) {
    # Parses a legs parameter into an array of Legs.
    return array_map('parse_leg', $leg_strings);
}