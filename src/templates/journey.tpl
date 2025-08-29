<!DOCTYPE HTML>
<html>
    <head>
        <title>Live Journey Stacker</title>
    </head>
    <body>
        <h1>Live Journey Stacker</h1>
        <table class="leg-table">
            <tr class="top-level-header">
                <th colspan="5">Boarding</th>
                <th colspan="5">Alighting</th>
                <th class="service-info" rowspan="2">Service</th>
                <th class="link" rowspan="2">Link</th>
            </tr>
            <tr class="detail-header">
                {for $i=0 to 1}
                <th class="stop">Station</th>
                <th class="time"><abbr title="Scheduled time">Sched</abbr></th>
                <th class="time"><abbr title="Real-time">RT</abbr></th>
                <th class="delay">Delay</th>
                <th class="platform"><abbr title="Platform">Plat</abbr></th>
                {/for}
            </tr>
            {foreach $legs as $leg}
            <tr class="leg train-leg">
                {foreach [$leg->boarding_stop_status, $leg->alighting_stop_status] as $status}
                <td class="stop">{$status->stop_name}</td>
                <td class="time">{$status->scheduled_time|date_format:"%H%M" ?? ""}</td>
                <td class="time">{$status->realtime_time|date_format:"%H%M" ?? ""}</td>
                <td class="delay">{$status->delay_mins|string_format:"%+d" ?? ""}</td>
                <td class="platform">{$status->platform}</td>
                {/foreach}
                <td class="service-info" rowspan="2">{$leg->toc} to {$leg->destination_name}</td>
                <td class="link" rowspan="2"><a href="{$leg->url}">View</a></td>
            </tr>
            {/foreach}
        </table>
        {include file='footer.tpl'}
    </body>
</html>