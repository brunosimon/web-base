<?php

    $i18ndata = $app("i18n")->data($app("i18n")->locale);
    $weekdays = isset($i18ndata["@meta"]["date"]["shortdays"]) ? $i18ndata["@meta"]["date"]["shortdays"] : ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

    $uid = uniqid('weekdays');
?>

<?php $app->start('header'); ?>

    <style type="text/css">

        .date-widget-weekdays span {
            margin-right: 5px;
        }
        .date-widget-weekdays span.active {
            color: #000;
            font-weight: bold;
        }
        .date-widget-clock {
            font-size: 30px;
            margin-top:20px;
            font-weight: bold;
        }
    </style>

    <script>
        jQuery(function($) {

            $("#<?php echo  $uid ; ?>").find('span[data-day="'+(new Date().getDay())+'"]').addClass('active');
        });
    </script>

<?php $app->end('header'); ?>


<div class="uk-grid">

    <div class="uk-width-medium-1-1">
        <div id="<?php echo  $uid ; ?>" class="uk-text-small uk-text-muted uk-margin uk-text-uppercase date-widget-weekdays">
            <span data-day="1"><?php echo  $weekdays[0] ; ?></span>
            <span data-day="2"><?php echo  $weekdays[1] ; ?></span>
            <span data-day="3"><?php echo  $weekdays[2] ; ?></span>
            <span data-day="4"><?php echo  $weekdays[3] ; ?></span>
            <span data-day="5"><?php echo  $weekdays[4] ; ?></span>
            <span data-day="6"><?php echo  $weekdays[5] ; ?></span>
            <span data-day="0"><?php echo  $weekdays[6] ; ?></span>
        </div>

        <div class="uk-text-small">
            <span app-clock="d. M Y">&nbsp;</span>
        </div>

        <div class="date-widget-clock">
            <i class="uk-icon-clock-o"></i> <span app-clock="h:i A">&nbsp;</span>
        </div>
    </div>
</div>