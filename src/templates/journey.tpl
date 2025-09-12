{function stop_status}
    <td class="{$class} stop">{$stop_status->stop_name ?? 'Unknown'}</td>
    <td class="{$class} time">{$stop_status->scheduled_time->format('Hi') ?? ''}</td>
    <td class="{$class} time">{$stop_status->realtime_time->format('Hi') ?? ''}</td>
    <td class="{$class} delay">{$stop_status->delay_mins|string_format:"%+d" ?? ''}</td>
    <td class="{$class} platform">{$stop_status->platform ?? ''}</td>
{/function}
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
                        <th class="boarding" colspan="5">Boarding</th>
                        <th class="alighting" colspan="5">Alighting</th>
                        <th class="service-info" rowspan="2">Service</th>
                        <th class="actions" rowspan="2">Actions</th>
                    </tr>
                    <tr class="detail-header">
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
                        {stop_status class="boarding" stop_status=$leg->boarding_stop_status ?? null}
                        {stop_status class="alighting" stop_status=$leg->alighting_stop_status ?? null}
                        {if $leg}
                        <td class="service-info">{$leg->toc} service to {$leg->destination_name}</td>
                        {else}
                        <td class="service-info">Failed to get status</td>
                        {/if}
                        <td class="actions"><span class="material-symbols-outlined">menu</span><menu>
                            <li><a class="add-before" 
                                    href="../search.php?j={$journey_string}&pos={$leg@index}" 
                                    title="Add leg before">
                                <span class="material-symbols-outlined">add_row_above</span>
                                <span>Add leg before</span>
                            </a></li>
                            <li><a class="add-after" 
                                    href="../search.php?j={$journey_string}&pos={$leg@index+1}" 
                                    title="Add leg after">
                                <span class="material-symbols-outlined">add_row_below</span>
                                <span>Add leg after</span>
                            </a></li>
                            <li><a class="delete-button" 
                                    href="delete_leg.php?j={$journey_string}&pos={$leg@index}"
                                    title="Delete leg">
                                <span class="material-symbols-outlined">delete</span>
                                <span>Delete</span>
                            </a></li>
                            <li><a class="view-button" href="{$leg->url}" title="View on Realtime Trains">
                                <span class="material-symbols-outlined">open_in_new</span>
                                <span>View</span>
                            </a></li>
                        </menu></td>
                    </tr>
                    {/foreach}
                </tbody>
            </table>
            <div class="end-actions"><menu>
                <li><a class="add-button" 
                        href="../search.php?j={$journey_string}&pos={$legs|count}" 
                        title="Add leg at end">
                    <span class="material-symbols-outlined">add</span>
                    <span>Add leg at end</span>
                </li></a>
            </menu></div>
        </main>
        {include file='footer.tpl'}
    </body>
</html>