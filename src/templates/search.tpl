<!DOCTYPE HTML>
<html>
    <head>
        {include file='common_head.tpl'}
        <link rel="stylesheet" href="{$base_path}css/search.css">
    </head>
    <body>
        <main>
            <h1>Live Journey Stacker</h1>
            {* <h2>Search</h2> *}
            <h2>Add leg</h2>
            <form class="search-form" action="journey/add_leg.php" method="get">
                <input type="hidden" name="j" value="{$journey_string}">
                <input type="hidden" name="pos" value="{$position}">
                <div><label for="date">Date: </label><input type="date" name="date" id="date"></div>
                <div><label for="uid">UID: 
                </label><input type="text" name="uid" id="uid" placeholder="6 character code from RTT URL"></div>
                <div><label for="board">Boarding: 
                </label><input type="text" name="board" id="board" placeholder="3 letter code e.g. EUS"></div>
                <div><label for="alight">Alighting: 
                </label><input type="text" name="alight" id="alight" placeholder="3 letter code e.g. EUS"></div>
                <div class="buttons">
                    {* <button class="button" type="submit">
                        <span class="material-symbols-outlined">search</span>
                        <span>Search</span>
                    </button> *}
                    <button class="button" type="submit">
                        <span class="material-symbols-outlined">Add</span>
                        <span>Add leg</span>
                    </button>
                    <button class="button" type="reset">
                        <span class="material-symbols-outlined">variable_remove</span>
                        <span>Clear</span>
                    </button>
                </div>
            </form>
        </main>
        {include file='footer.tpl'}
    </body>
</html>