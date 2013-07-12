<!DOCTYPE HTML>
<?php
    $status_json = file_get_contents("status.json");

    $status = json_decode($status_json);

    $overall_percent = ($status->successes || $status->failures) ? round(($status->successes / ($status->successes + $status->failures)) * 100) : 100;
    $overall_status = "UP";
    $message = "UP";

    if($overall_percent < 98) {
        $overall_status = "ISSUE";
        $message = "Having Issues";
    }

    if($overall_percent < 50) {
        $overall_status = "DOWN";
        $message = "Down";
    }

    date_default_timezone_set('America/Chicago');
    $date = date("F jS, Y \a\\t g:i A", strtotime($status->time));
?>
<html lang="en">
<head>
    <meta charset="utf-8">
    <title>Cultivate Studios Status</title>
    <link href='https://fonts.googleapis.com/css?family=Bitter:400,700,400italic' rel='stylesheet' type='text/css'>
    <link rel="styles40et" href="/static/css/bootstrap-2.0.css">
    <link rel="stylesheet" href="/static/css/base.css">
</head>
<body>

<div class="stc summary <?=$overall_status;?>">

    <h1><a href="https://www.cultivatestudios.com"><img src="/static/images/status/logo.png" id="status-logo" alt="Cultivate Studios"></a>
    <h2><span class="as-of">Status as of <?=$date;?></span></h2>
    <h3>We Are <?=$message;?></h3>
    <h4><?=$overall_percent;?>% Uptime</h4>
</div>
<div class="services">
    <ul>
        <?php foreach($status->vhosts as $vhost => $values) { ?>
            <?php $vhost_slug = strtoupper($vhost); ?>
            <?php 
                $percent = ($values->successes || $values->failures) ? round(($values->successes / ($values->successes + $values->failures)) * 100) : 100;
                $status = "UP";

                if($percent < 98) {
                    $status = "ISSUE";
                }

                if($percent < 50) {
                    $status = "DOWN";
                }
            ?>
            <li class="<?=$vhost_slug;?>">
                <div class='identifier'>
                    <div class="uptime-image <?=$status;?>">
                        UP
                    </div></div>
                    <h4><?=$percent;?>% Uptime</h4>
                    <h3><?=$vhost_slug;?></h3>
            </li>
        <? } ?>
    </ul>
</div>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
<script type="text/javascript" src="/static/js/status.min.js"></script>
<script type="text/javascript">
    //  kick everything off when jquery is ready
    $(function () {
        Balanced.init({});
    });
</script>

</body>
</html>
