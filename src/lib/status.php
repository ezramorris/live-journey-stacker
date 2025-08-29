<?php

require_once(__DIR__.'/../config/config.php');
require_once(__DIR__.'/../config/creds.php');

enum TrainStopPlatformStatus: string {
    # These are possible statuses relating to a train near a platform.
    # RTT calls them "locations".
    # The backing string is used as the RTT Location string returned by the API.

    case Approaching = 'APPR_STAT';
    case Arriving = 'APPR_PLAT';
    case AtPlatform = 'AT_PLAT';
    case PreparingToDepart = 'DEP_PREP';
    case ReadyToDepart = 'DEP_READY';
    case Unknown = '';
}


function parse_RTT_time(DateTimeImmutable $base_datetime, string $rtt_time_str) {
    # Parse an RTT time string, which is the format 'HHMM'.
    # $base_datetime is used as a starting point, then the time elements are overridden.
    
    $hour = (int)substr($rtt_time_str, 0, 2);
    $min = (int)substr($rtt_time_str, 2, 2);
    return $base_datetime->setTime($hour, $min);
}


class TrainStopStatus {
    # Holds status of a stop on a train leg (either boarding or alighting stop).

    public string $stop_name;
    public ?DateTimeImmutable $scheduled_time;
    public ?DateTimeImmutable $realtime_time;
    public ?int $delay_mins;
    public bool $is_late;
    # After train has arrived/departed, the realtime time is no longer an estimate.
    public bool $is_realtime_time_actual;
    public bool $is_cancelled;
    public bool $has_departed;
    public bool $has_passed_with_no_report;
    public TrainStopPlatformStatus $platform_status;
    public string $platform;
    public bool $is_platform_confirmed;

    public static function parse(DateTimeImmutable $date, $stop_data, bool $use_arrival) {
        # Parse an RTT location object into a TrainStopStatus and return it.
        # $date is the date of the journey.
        # $stop_data is one item from RTT ['locations'][] array.
        # If $use_arrival is TRUE, then the arrival time will be used instead of departure time.

        # Stuffed into object keys to look up departure or arrival depending on what is required.
        $when = $use_arrival ? "Arrival" : "Departure";

        $status = new TrainStopStatus();
        $status->stop_name = $stop_data['description'];
        $status->scheduled_time = parse_RTT_time($date, $stop_data["gbttBooked$when"]) ?? NULL;
        $status->realtime_time = parse_RTT_time($date, $stop_data["realtime$when"]) ?? NULL;
        
        # We calculate our own delay instead of using RTT's bc RTT only reports a delay once train has arrived.
        $status->delay_mins = ($status->realtime_time->getTimestamp() - 
                               $status->scheduled_time->getTimestamp()) / 60 ?? NULL;

        # Can't compare DateIntervals, but can compare DateTimeImmutable.
        # So, we add our max delay to the scheduled time and see if the realtime time is past it.
        $status->is_late = $status->realtime_time >= $status->scheduled_time->add(LATE_DELAY);

        $status->is_realtime_time_actual = $stop_data["realtime{$when}Actual"] ?? FALSE;
        $status->is_cancelled = $stop_data['displayAs'] == 'CANCELLED_CALL' ?? FALSE;
        
        # If the stop shows "no report", it means it has been detected further down the line.
        # Therefore we can deduce it has already left our stop even though RTT doesn't report it.
        if ($stop_data["realtime{$when}NoReport"] ?? FALSE) {
            $status->has_departed = TRUE;
            $status->has_passed_with_no_report = TRUE;
        }
        else {
            $status->has_departed = $stop_data["realtime{$when}Actual"] ?? FALSE;
            $status->has_passed_with_no_report = FALSE;
        }
        
        $status->platform_status = TrainStopPlatformStatus::from($stop_data['serviceLocation'] ?? '');
        $status->platform = $stop_data['platform'] ?? '';
        $status->is_platform_confirmed = $stop_data['platformConfirmed'] ?? FALSE;

        return $status;
    }
}


class TrainLeg {
    # Holds info & status of a train leg.

    public string $toc;
    public string $destination_name;
    public TrainStopStatus $boarding_stop_status;
    public TrainStopStatus $alighting_stop_status;

    public static function parse(DateTimeImmutable $date, $service_data, $boarding_crs, $alighting_crs) {
        # Parse service data returned from RTT Pull API into a TrainLegStatus and return it.
        # $date is the date of the journey.
        # $service_data is the data returned by RTT API.
        # $boarding_crs and $alighting_crs are 3-letter (CRS) station codes.

        $status = new TrainLeg();

        # Parse TOC.
        $status->toc = $service_data['atocName'];

        # Parse destination name.
        # Normally this would just be one but sometimes can be more!
        $destination_names = array();
        foreach($service_data['destination'] as $destination) {
            $destination_names[] = $destination['description'];
        }
        $status->destination_name = implode(', ', $destination_names);

        # Pull out the info for boarding and alighting stations.
        $boarding_data = null;
        $alighting_data = null;
        foreach ($service_data['locations'] as $location) {
            if ($location['crs'] == $boarding_crs) {
                $boarding_data = $location;
            }
            elseif ($location['crs'] == $alighting_crs){
                $alighting_data = $location;
            }
            if ($boarding_data && $alighting_data) {
                break;
            }
        }

        # Parse boarding station info.
        $status->boarding_stop_status = TrainStopStatus::parse($date, $boarding_data, FALSE);
        $status->alighting_stop_status = TrainStopStatus::parse($date, $alighting_data, TRUE);

        return $status;
    }
}
   

function get_train_leg(string $serviceId, DateTimeImmutable $date,
        string $boarding_crs, string $alighting_crs) {
    # Update status from RealTimeTrains.
    # $serviceId is the RTT service ID (e.g. A12345).
    # $date is the date the service is running (time portion not used).

    $url = implode('/', [
        RTT_BASE_URL, 
        $serviceId,
        $date->format('Y'),
        $date->format('m'),
        $date->format('d')
    ]);

    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Authorization: Basic ' . base64_encode(RTT_USERNAME . ':' . RTT_PASSWORD),
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);

    $data = json_decode($response, true);

    return TrainLeg::parse($date, $data, $boarding_crs, $alighting_crs);
}