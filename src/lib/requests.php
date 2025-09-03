<?php

# High level interface for issuing network requests.

function run_multiple_requests (array $handles) {
    # Issue multiple curl requests and blocks until complete.
    # $handles is an array of curl handles to process.
    # Returns an array of arrays with keys 'info' and 'response'.
    # 'info' is the result of curl_getinfo() and 'response' is the body.

    $mh = curl_multi_init();
    curl_multi_setopt($mh, CURLMOPT_MAX_HOST_CONNECTIONS, 10);

    foreach ($handles as $handle) {
        curl_multi_add_handle($mh, $handle);
    }

    # Run until completion.
    $active = null;
    do {
        $res = curl_multi_exec($mh, $active);
        if ($res != CURLM_OK) {
            throw new \Exception(curl_multi_strerror(curl_multi_errno($mh)));
        }
        $res = curl_multi_select($mh);
        # Select failed, so just sleep for a bit.
        if ($res == -1) {
            usleep(1000);
        }
    } while ($active > 0);

    $results = array_map(
        function ($ch) use ($mh) {
            $info = curl_getinfo($ch);
            $response = curl_multi_getcontent($ch);
            curl_multi_remove_handle($mh, $ch);
            curl_close($ch);
            return ['info' => $info, 'response' => $response];
        }, 
        $handles
    );

    curl_multi_close($mh);
    return $results;
}