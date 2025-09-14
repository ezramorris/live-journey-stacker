<?php

require_once(__DIR__.'/rtt.php');
require_once(__DIR__.'/../config/config.php');
require_once(__DIR__.'/../config/creds.php');

function search_trains(DateTimeImmutable $date_time, ?string $from=null, ?string $to=null) {
    if (is_null($to) && is_null($from)) {
        throw new Exception('At least one of $to or $from must not be null.');
    }

    $url_parts = [RTT_BASE_URL, 'search'];

    # If only provided $to, then use this as the search station.
    if (is_null($from)) {
        $url_parts[] = $to;
    }
    # Otherwise, use $from as the search station.
    else {
        $url_parts[] = $from;
        # If both stations were provided, then add the $to station.
        if (!is_null($to)) {
            $url_parts[] = 'to';
            $url_parts[] = $to;
        }
    }

    $url_parts[] = $date_time->format('Y/m/d/Hi');

    # If only $to provided, then get arrivals to that station.
    if (is_null($from)) {
        $url_parts[] = 'arrivals';
    }

    $url = implode('/', $url_parts);
 
    # TODO: factor out.
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Basic ' . base64_encode(RTT_USERNAME . ':' . RTT_PASSWORD),
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 1);
    $response = curl_exec($ch);
    $info = curl_getinfo($ch);

    return parse_rtt_response($info, $response);
}