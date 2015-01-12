<?php echo  $app->assets(['updater:assets/js/index.js'], $app['cockpit/version']) ; ?>

<h1><a href="<?php $app->route('/settingspage'); ?>"><?php echo $app("i18n")->get('Settings'); ?></a> / <?php echo $app("i18n")->get('Update'); ?></h1>

<div class="uk-margin-large-top" data-ng-controller="updater" ng-cloak>

    <div class="uk-text-center uk-width-medium-1-2 uk-container-center" ng-show="loading">

        <h2><i class="uk-icon-spinner uk-icon-spin"></i></h2>
        <p class="uk-text-large">
            <?php echo $app("i18n")->get('Getting information...'); ?>
        </p>

    </div>

    <div class="uk-text-center uk-width-medium-1-2 uk-container-center uk-animation-shake" ng-if="data && data.error">

        <h2><i class="uk-icon-bolt"></i></h2>
        <p class="uk-text-large">
            {{ data.error }}
        </p>

    </div>


    <div ng-if="data && !data.error">

        <div class="uk-grid" data-uk-grid-margin>

            <div class="uk-width-medium-1-2">

                <div class="app-panel">
                    <div class="uk-text-bold uk-text-muted">Local</div>
                    <div class="uk-h1 uk-text-muted">{{ data.local.version }}</div>

                    <div class="uk-text-bold uk-margin-top">Latest stable</div>
                    <div class="uk-h1">{{ data.stable.version }}</div>

                    <div class="uk-alert">
                        <?php echo $app("i18n")->get('Don\'t forget to backup the cockpit folder before any update.'); ?>
                    </div>
                </div>

                <div class="uk-margin-top">
                    <button class="uk-button uk-button-primary" ng-click="install()">
                        <span class="tn" ng-if="(data.local.version==data.stable.version)"><i class="uk-icon-refresh"></i>&nbsp; <?php echo $app("i18n")->get('Re-Install'); ?></span>
                        <span class="tn" ng-if="(data.local.version!=data.stable.version)"><i class="uk-icon-cloud-download"></i>&nbsp; <?php echo $app("i18n")->get('Update'); ?></span>
                    </button>

                    or
                    <a ng-click="install('master')"><?php echo $app("i18n")->get('Install latest development version'); ?></a> <span class="uk-badge app-badge"><?php echo $app("i18n")->get('Danger'); ?></span>
                </div>
            </div>
        </div>

    </div>

</div>
