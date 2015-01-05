<div class="app-dashboard-widget">

    <div class="app-panel-secondary">

        <div class="uk-margin-bottom uk-text-right">
            <span class="uk-badge app-badge"><?php echo $app("i18n")->get('Activity'); ?></span>
        </div>

        <?php if(count($history)) { ?>

        <ul class="uk-list">
            <?php foreach($history as $log) { ?>
            <li class="uk-margin-bottom">
                <div class="uk-grid uk-grid-divider">
                    <div class="uk-width-medium-1-5 uk-text-center">
                        <img class="uk-rounded" src="http://www.gravatar.com/avatar/<?php echo  md5($log['uid']['email']) ; ?>?d=mm&s=40" width="40" height="40" alt="avatar">
                    </div>
                    <div class="uk-width-medium-4-5">
                        <time class="uk-text-small uk-text-muted"><?php echo  date("d.m.y H:i", $log["time"]) ; ?></time>
                        <div class="uk-margin-small-top">
                            <?php echo  vsprintf($app("i18n")->get($log["msg"]), $log["args"]) ; ?>
                        </div>
                    </div>
                </div>
            </li>
            <?php } ?>
        </ul>

        <?php }else{ ?>

            <div class="uk-text-center uk-text-muted">
                <div class="uk-text-large uk-margin-small-bottom">
                    <i class="uk-icon-flag"></i>
                </div>
                <?php echo $app("i18n")->get('No events logged.'); ?>
            </div>

        <?php } ?>

    </div>
</div>