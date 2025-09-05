<!DOCTYPE HTML>
<html>
    <head>
        {include file='common_head.tpl'}
        <link rel="stylesheet" href="{$base_path}css/journey.css">
    </head>
    <body>
        <main>
            <h1>Live Journey Stacker</h1>
            <table class="leg-table">
                <thead>
                    <tr class="top-level-header">
                        <th class="actions" colspan="3">Actions</th>
                        <th class="boarding" colspan="5">Boarding</th>
                        <th class="alighting" colspan="5">Alighting</th>
                        <th class="service-info" rowspan="2">Service</th>
                    </tr>
                    <tr class="detail-header">
                        <th class="actions add">Add</th>
                        <th class="actions delete">Delete</th>
                        <th class="actions view">View</th>
                        {foreach ['boarding', 'alighting'] as $class}
                        <th class="{$class} stop">Station</th>
                        <th class="{$class} time"><abbr title="Scheduled time">Sched</abbr></th>
                        <th class="{$class} time"><abbr title="Real-time">RT</abbr></th>
                        <th class="{$class} delay"><abbr title="Delay">Dly</abbr></th>
                        <th class="{$class} platform"><abbr title="Platform">P</abbr></th>
                        {/foreach}
                    </tr>
                </thead>
                <tbody>
                    {foreach $legs as $leg}
                    <tr class="leg">
                        <td class="actions add">
                            <a class="add-button" href="../search.php?j={$journey_string}&pos={$leg@index}" 
                                    title="Add leg before">
                                <span>Add leg before</span>
                            </a>
                        </td>
                        <td class="actions delete">
                            <a class="delete-button" href="delete_leg.php?j={$journey_string}&pos={$leg@index}"
                                    title="Delete leg">
                                <span>Delete</span>
                            </a>
                        </td>
                        <td class="actions view">
                            <a class="view-button" href="{$leg->url}" title="View on Realtime Trains">
                                <span>View<</span>
                            </a>
                        </td>
                        {foreach ['boarding'=>$leg->boarding_stop_status ?? null, 
                            'alighting'=>$leg->alighting_stop_status ?? null] as $class=>$status}
                        <td class="{$class} stop">{$status->stop_name ?? "Unknown"}</td>
                        <td class="{$class} time">{$status->scheduled_time->format('Hi') ?? ""}</td>
                        <td class="{$class} time">{$status->realtime_time->format('Hi') ?? ""}</td>
                        <td class="{$class} delay">{$status->delay_mins|string_format:"%+d" ?? ""}</td>
                        <td class="{$class} platform">{$status->platform ?? ""}</td>
                        {/foreach}
                        {if $leg}
                        <td class="service-info">{$leg->toc} service to {$leg->destination_name}</td>
                        {else}
                        <td class="service-info">Failed to get status</td>
                        {/if}
                    </tr>
                    {/foreach}
                    <tr class="end-actions">
                        <td class="actions add" colspan="14">
                            <a class="add-button" href="../search.php?j={$journey_string}&pos={$legs|count}" 
                                    title="Add leg at end">
                                <span>Add leg at end</span>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </main>
        {include file='footer.tpl'}
    </body>
</html>