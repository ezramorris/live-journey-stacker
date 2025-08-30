<!DOCTYPE HTML>
<html>
    <head>
        <title>Live Journey Stacker</title>
    </head>
    <body>
        <form action="journey/add_leg.php" method="get">
            <input type="hidden" name="j" value="{$journey_string}">
            <input type="hidden" name="pos" value="{$position}">
            <h2>Enter first leg:</h2>
            <div><label for="date">Date: </label><input type="date" name="date" id="date"></div>
            <div><label for="uid">UID: 
            </label><input type="text" name="uid" id="uid" placeholder="6 character code from RTT URL"></div>
            <div><label for="board">Boarding: 
            </label><input type="text" name="board" id="board" placeholder="3 letter code e.g. EUS"></div>
            <div><label for="alight">Alighting: 
            </label><input type="text" name="alight" id="alight" placeholder="3 letter code e.g. EUS"></div>
            <div><input type="submit" value="Submit"></div>
            <div><input type="reset" value="Reset"></div>
        </form>
        {include file='footer.tpl'}
    </body>
</html>