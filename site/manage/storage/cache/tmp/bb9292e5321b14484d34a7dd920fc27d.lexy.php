<?php $app->start('header'); ?>

    <style>

        .app-panel > div {
            text-align: center;
        }

        .app-panel > div  *[class*=uk-icon], .app-panel > div img {
            font-size: 40px;
            line-height: 60px;
        }

        .app-panel > div img {
            width: 40px;
            height: 40px;
        }

    </style>

<?php $app->end('header'); ?>

<h1><?php echo $app("i18n")->get('Settings'); ?></h1>

<div class="app-panel">

    <div class="uk-text-left">
        <span class="uk-badge app-badge"><?php echo $app("i18n")->get('System'); ?></span>
    </div>

    <hr>

    <div class="uk-grid uk-grid-width-medium-1-6" uk-grid-margin uk-grid-match>

        <?php if($app["user"]["group"]=="admin") { ?>
        <div class="uk-margin-bottom">
            <div>
                <i class="uk-icon-cogs"></i>
            </div>
            <div class="uk-text-truncate">
                <a href="<?php $app->route('/settings/general'); ?>"><?php echo $app("i18n")->get('General'); ?></a>
            </div>
        </div>
        <?php } ?>

        <div class="uk-margin-bottom">
            <div>
                <i class="uk-icon-group"></i>
            </div>
            <div class="uk-text-truncate">
                <a href="<?php $app->route('/accounts/index'); ?>"><?php echo $app("i18n")->get('Accounts'); ?></a>
            </div>
        </div>

        <?php if($app["user"]["group"]=="admin") { ?>
        <div class="uk-margin-bottom">
            <div>
                <i class="uk-icon-code-fork"></i>
            </div>
            <div class="uk-text-truncate">
                <a href="<?php $app->route('/settings/addons'); ?>"><?php echo $app("i18n")->get('Addons'); ?></a>
            </div>
        </div>
        <?php } ?>

        <?php if($app->module("auth")->hasaccess("Datastore", "manage.datastore")) { ?>
        <div class="uk-margin-bottom">
            <div>
                <i class="uk-icon-database"></i>
            </div>
            <div class="uk-text-truncate">
                <a href="<?php $app->route('/datastore'); ?>"><?php echo $app("i18n")->get('Datastore'); ?></a>
            </div>
        </div>
        <?php } ?>

        <?php if($app->module("auth")->hasaccess("Cockpit", "manage.backups")) { ?>
        <div class="uk-margin-bottom">
            <div>
                <i class="uk-icon-archive"></i>
            </div>
            <div class="uk-text-truncate">
                <a href="<?php $app->route('/backups'); ?>"><?php echo $app("i18n")->get('Backups'); ?></a>
            </div>
        </div>
        <?php } ?>

        <?php if($app["user"]["group"]=="admin") { ?>
        <div class="uk-margin-bottom">
            <div>
                <i class="uk-icon-fire"></i>
            </div>
            <div class="uk-text-truncate">
                <a href="<?php $app->route('/updater/index'); ?>"><?php echo $app("i18n")->get('Update'); ?></a>
            </div>
        </div>
        <?php } ?>

        <?php if($app["user"]["group"]=="admin") { ?>
        <div class="uk-margin-bottom">
            <div>
                <i class="uk-icon-info-circle"></i>
            </div>
            <div class="uk-text-truncate">
                <a href="<?php $app->route('/settings/info'); ?>"><?php echo $app("i18n")->get('Info'); ?></a>
            </div>
        </div>
        <?php } ?>
    </div>
</div>

<?php $app->trigger('cockpit.settings.index'); ?>