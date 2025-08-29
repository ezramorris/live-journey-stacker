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
                </div>
            </li>
            {/foreach}
        </ol>
        <h2>GET params</h2>
        <pre>{$get|debug_print_var}</pre>
        <h2>Status data</h2>
        <pre>{$legs|debug_print_var}</pre>
    </body>
</html>