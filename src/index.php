<?php 
require_once('status.php'); 
$date = new DateTimeImmutable('now', new DateTimeZone('Europe/London'));
?>
<!DOCTYPE HTML>
<html>
    <head>
        <title>Live Journey Stacker</title>
    </head>
    <body>
        <h1>Live Journey Stacker</h1>
        <pre><?php var_dump(get_train_leg_status('Y07095', $date, 'DBY', 'STP'))?></pre>
    </body>
</html>