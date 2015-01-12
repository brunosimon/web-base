<?php echo  $app->assets(['assets:vendor/uikit/js/components/form-password.min.js']) ; ?>

<h1>
    <a href="<?php $app->route('/settingspage'); ?>"><?php echo $app("i18n")->get('Settings'); ?></a> / <a href="<?php $app->route('/accounts/index'); ?>"><?php echo $app("i18n")->get('Accounts'); ?></a> / <?php echo $app("i18n")->get('Account'); ?>
</h1>

<div class="uk-grid" data-ng-controller="account" data-uk-margin ng-cloak>

    <div class="uk-width-medium-2-4">

        <div class="app-panel">


        <div class="uk-panel app-panel-box docked uk-text-center">
            <div class="uk-thumbnail uk-rounded">
                <img src="http://www.gravatar.com/avatar/<?php echo  md5(@$account['email']) ; ?>?d=mm&s=100" width="100" height="100" alt="">
            </div>

            <h2 class="uk-text-truncate">{{ account.name }}</h2>
        </div>


            <div class="uk-grid" data-uk-margin>

                <div class="uk-width-medium-1-1">

                    <form class="uk-form" data-ng-submit="save()" data-ng-show="account">


                        <div class="uk-form-row">
                            <label class="uk-text-small"><?php echo $app("i18n")->get('Name'); ?></label>
                            <input class="uk-width-1-1 uk-form-large" type="text" data-ng-model="account.name">
                        </div>

                        <div class="uk-form-row">
                            <label class="uk-text-small"><?php echo $app("i18n")->get('Username'); ?></label>
                            <input class="uk-width-1-1 uk-form-large" type="text" data-ng-model="account.user">
                        </div>

                        <div class="uk-form-row">
                            <label class="uk-text-small"><?php echo $app("i18n")->get('Email'); ?></label>
                            <input class="uk-width-1-1 uk-form-large" type="text" data-ng-model="account.email">
                        </div>

                        <hr>

                        <div class="uk-form-row">
                            <label class="uk-text-small"><?php echo $app("i18n")->get('New Password'); ?></label>
                            <div class="uk-form-password uk-width-1-1">
                                <input class="uk-form-large uk-width-1-1" type="password" placeholder="<?php echo $app("i18n")->get('Password'); ?>" data-ng-model="account.password">
                                <a href="" class="uk-form-password-toggle" data-uk-form-password>Show</a>
                            </div>
                            <div class="uk-alert">
                                <?php echo $app("i18n")->get('Leave the password field empty to keep your current password.'); ?>
                            </div>
                        </div>

                        <div class="uk-form-row">
                            <button class="uk-button uk-button-large uk-button-primary uk-width-1-2"><?php echo $app("i18n")->get('Save'); ?></button>
                        </div>

                    </form>

                </div>

            </div>
        </div>

    </div>

    <div class="uk-width-medium-1-4 uk-form">
        <h3><?php echo $app("i18n")->get('System'); ?></h3>
        <div class="uk-form-row">
            <label class="uk-text-small"><?php echo $app("i18n")->get('Language'); ?></label>

            <div class="uk-form-controls uk-margin-small-top">
                <div class="uk-form-select">
                    <a>{{ languages[account.i18n] }}</a>
                    <select class="uk-width-1-1 uk-form-large" data-ng-model="account.i18n">
                        <?php foreach($languages as $lang) { ?>
                        <option value="<?php echo  $lang['i18n'] ; ?>"><?php echo  $lang['language'] ; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
        </div>

        <?php if($app["user"]["group"]=="admin" AND @$account["_id"]!=$app["user"]["_id"]) { ?>
        <div class="uk-form-row">
            <label class="uk-text-small"><?php echo $app("i18n")->get('Group'); ?></label>

            <div class="uk-form-controls uk-margin-small-top">
                <div class="uk-form-select">
                    <i class="uk-icon-sitemap uk-margin-small-right"></i>
                    <a>{{ account.group }}</a>
                    <select class="uk-width-1-1 uk-form-large" data-ng-model="account.group">
                        <?php foreach($groups as $group) { ?>
                        <option value="<?php echo  $group ; ?>"><?php echo  $group ; ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>

        </div>
        <?php } ?>

    </div>
</div>
<script>

    App.module.controller("account", function($scope, $rootScope, $http){

        $scope.account = <?php echo  json_encode($account) ; ?>;

        $scope.save = function(){

            var account = angular.copy($scope.account),
                isnew   = account["_id"] ? false:true;

            $http.post(App.route("/accounts/save"), {"account": account}).success(function(data){

                if (data && Object.keys(data).length) {
                    App.notify("<?php echo $app("i18n")->get('Account saved!'); ?>");

                    $scope.account = data;
                    $scope.account.password = "";
                }

            }).error(App.module.callbacks.error.http);
        };

        $scope.languages = {};

        <?php foreach($languages as $lang) { ?>
        $scope.languages['<?php echo  $lang['i18n'] ; ?>'] = '<?php echo  $lang['language'] ; ?>';
        <?php } ?>

    });
</script>