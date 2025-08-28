<?php

# Functions for validating and parsing user input.
# Each of these will return parsed value, or return 400 and exit execution on failure.

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

function parse_train_uid(string $uid) {
    validate_or_die($uid, FILTER_VALIDATE_REGEXP, ['options'=>['regexp'=>'/^[A-Z0-9]{6}$/']]);
    return $uid;
}

function parse_crs(string $crs) {
    validate_or_die($crs, FILTER_VALIDATE_REGEXP, ['options'=>['regexp'=>'/^[A-Z0-9]{3}$/']]);
    return $crs;
}

function parse_date(string $date_string) {
    $date = DateTimeImmutable::createFromFormat('Y-m-d', $date_string, TIMEZONE);
    if (!$date) {
        die_with_400();
    }
    return $date;
}