<!DOCTYPE HTML>
<html>
    <head>
        <title>Live Journey Stacker</title>
    </head>
    <body>
        <h1>Live Journey Stacker</h1>
        <ol class="leg-list">
            {foreach $legs as $leg}
            <li class="leg train-leg">
                <div class="boarding-stop">
                    <div class="stop-name">{$leg->status->destination_name|escape}</div>
                </div>
            </li>
            {/foreach}
        </ol>
    </body>
</html>