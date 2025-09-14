<?php

# Functions for working with Real Time Trains API.

function parse_rtt_response($info, $response) {
    # Parses response from RTT API.
    # If successful returns the decoded response.
    # If unsuccessful returns null.

    if ($info === false) {
        error_log('failed to get response from RTT');
        return null;
    }
    if ($info['http_code'] != 200) {
        error_log('received error code '.$info['http_code'].' from RTT');
        return null;
    }

    $data = json_decode($response, true);
    if (is_null($data)) {
        error_log('failed to decode RTT response');
        return null;
    }
    if (array_key_exists('error', $data)) {
        error_log('received error from RTT: '.$data['error']);
        return null;
    }

    return $data;
}