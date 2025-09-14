<!DOCTYPE HTML>
<html>
    <head>
        {include file='common_head.tpl'}
        <link rel="stylesheet" href="{$base_path}css/search.css">
    </head>
    <body>
        <main>
            <h1>Live Journey Stacker</h1>
            <h2>Search</h2>
            <form class="search-form" action="search.php" method="get">
                <input type="hidden" name="j" value="{$journey_string}">
                <input type="hidden" name="pos" value="{$position}">

                <div><label for="date">Date: 
                </label><input type="date" name="date" id="date" value="{$datetime->format('Y-m-d')}"></div>

                <div><label for="date">Time: 
                </label><input type="time" name="time" id="time" value="{$datetime->format('H:i')}"></div>

                <div><label for="from">From: 
                </label><input type="text" name="from" id="from" value="{$from}" placeholder="3 letter code e.g. EUS"></div>

                <div><label for="to">To: 
                </label><input type="text" name="to" id="to" value="{$to}" placeholder="3 letter code e.g. EUS"></div>

                <div class="buttons">
                    <button class="button" type="submit">
                        <span class="material-symbols-outlined">search</span>
                        <span>Search</span>
                    </button>
                    <button class="button" type="reset">
                        <span class="material-symbols-outlined">variable_remove</span>
                        <span>Clear</span>
                    </button>
                </div>
            </form>
            {if $res|isset}
            <div class="results">
                <pre>
                    {$res|debug_print_var|raw}
                </pre>
            </div>
            {/if}
        </main>
        {include file='footer.tpl'}
    </body>
</html>