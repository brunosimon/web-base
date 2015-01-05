<!doctype html>
<html lang="<?php echo  $app("i18n")->locale ; ?>" data-base="<?php $app->base('/'); ?>" data-route="<?php $app->route('/'); ?>" data-version="<?php echo  $app['cockpit/version'] ; ?>" data-locale="<?php echo  $app("i18n")->locale ; ?>">
<head>
    <meta charset="UTF-8">
    <title><?php echo  $app['app.name'] ; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />
    <link rel="icon" href="<?php $app->base("/assets/images/favicon.ico"); ?>" type="image/x-icon">

    <?php $app("assets")->style_and_script($app['app.assets.base'], 'app.base'.$app['cockpit/version'], 'cache:assets', 360, $app['cockpit/version']); ?>
    <?php $app("assets")->style_and_script($app['app.assets.backend'], 'app.backend'.$app['cockpit/version'], 'cache:assets', 360, $app['cockpit/version']); ?>

    <?php echo  $app->assets(["assets:js/angular/cockpit.js"], $app['cockpit/version']) ; ?>

    <?php $app->trigger('app.layout.header'); ?>

    <?php $app->block('header'); ?>
</head>
<body>

    <nav class="uk-navbar app-top-navbar">

        <div class="app-wrapper">

            <ul class="uk-navbar-nav">
                <li class="uk-parent" data-uk-dropdown>
                    <a href="<?php $app->route('/dashboard'); ?>"><i class="uk-icon-bars"></i><strong class="uk-hidden-small"> &nbsp;<?php echo  $app['app.name'] ; ?></strong></a>
                    <div class="uk-dropdown uk-dropdown-navbar">
                        <ul class="uk-nav uk-nav-navbar uk-nav-parent-icon">
                            <li>
                                <a href="<?php $app->route('/accounts/account'); ?>" class="uk-clearfix">
                                    <img class="uk-rounded uk-float-left uk-margin-right" src="http://www.gravatar.com/avatar/<?php echo  md5($app['user']['email']) ; ?>?d=mm&s=40" width="40" height="40" alt="avatar">
                                    <div class="uk-text-truncate"><strong><?php echo  $app["user"]["name"] ? $app["user"]["name"] : $app["user"]["user"] ; ?></strong></div>
                                    <div class="uk-text-small uk-text-truncate"><?php echo  (isset($app["user"]["email"]) ? $app["user"]["email"] : 'no email') ; ?></div>
                                </a>
                            </li>
                            <li class="uk-nav-divider"></li>
                            <li><a href="<?php $app->route('/dashboard'); ?>"><i class="uk-icon-dashboard icon-spacer"></i> <?php echo $app("i18n")->get('Dashboard'); ?></a></li>

                            <li class="uk-nav-header uk-text-truncate"><?php echo $app("i18n")->get('General'); ?></li>

                            <li><a href="<?php $app->route('/settingspage'); ?>"><i class="uk-icon-cog icon-spacer"></i> <?php echo $app("i18n")->get('Settings'); ?></a></li>
                            <?php if($app["user"]["group"]=="admin") { ?>
                            <li><a href="<?php $app->route('/settings/addons'); ?>"><i class="uk-icon-code-fork icon-spacer"></i> <?php echo $app("i18n")->get('Addons'); ?></a></li>
                            <?php } ?>
                            <?php $app->trigger("navbar"); ?>
                            <li class="uk-nav-divider"></li>
                            <li><a href="<?php $app->route('/auth/logout'); ?>"><i class="uk-icon-power-off icon-spacer"></i> <?php echo $app("i18n")->get('Logout'); ?></a></li>
                        </ul>
                    </div>
                </li>
            </ul>

            <div class="uk-navbar-content uk-hidden-small">
                <form id="frmCockpitSearch" class="uk-search" data-uk-search="{source:'<?php $app->route('/cockpit-globalsearch'); ?>', msgMoreResults:false, msgResultsHeader: '<?php echo $app("i18n")->get('Search Results'); ?>', msgNoResults: '<?php echo $app("i18n")->get('No results found'); ?>'}" onsubmit="return false;">
                    <input class="uk-search-field" type="search" placeholder="<?php echo $app("i18n")->get('Search...'); ?>" autocomplete="off">
                </form>
            </div>

            <div class="uk-navbar-flip">

                <ul class="uk-navbar-nav app-top-navbar-links">
                    <?php foreach($app("admin")->menu('top') as $item) { ?>
                    <li class="<?php echo  (isset($item["active"]) && $item["active"]) ? 'uk-active':'' ; ?>">
                        <a href="<?php echo  $item["url"] ; ?>" title="<?php echo  $item["title"] ; ?>" data-uk-tooltip><?php echo  $item["label"] ; ?></a>
                    </li>
                    <?php } ?>

                    <?php $app->trigger("navbar-primary"); ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="app-main">
        <div class="app-wrapper">
            <?php echo  $content_for_layout ; ?>
        </div>
    </div>

    <script charset="utf-8" src="<?php $app->route('/i18n-js'); ?>"></script>

    <?php $app->trigger("app.layout.footer"); ?>
    <?php $app->block('footer'); ?>

</body>
</html>
