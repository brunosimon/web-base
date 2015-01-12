<?php echo  $app->assets(['auth:assets/js/groups.js']) ; ?>

<style>
    .group-list li {
        position: relative;
        overflow: hidden;
    }
    .group-actions {
        position: absolute;
        display:none;
        min-width: 60px;
        text-align: right;
        top: 5px;
        right: 10px;
    }

    .group-actions, .group-actions a { font-size: 11px; }

    .group-list li:hover .group-actions a { color: #666; }
    .group-list li.uk-active .group-actions a { color: #fff; }

    .group-list li.uk-active .group-actions ,
    .group-list li:hover .group-actions { display:block; }
</style>

<h1>
    <a href="<?php $app->route('/settingspage'); ?>"><?php echo $app("i18n")->get('Settings'); ?></a> / <a href="<?php $app->route('/accounts/index'); ?>"><?php echo $app("i18n")->get('Accounts'); ?></a> / <?php echo $app("i18n")->get('Groups'); ?>
</h1>

<script>
    var ACL_DATA           = <?php echo  json_encode($acl) ; ?>,
        ACL_GROUP_SETTINGS = <?php echo  json_encode($app["cockpit.acl.groups.settings"]) ; ?>;
</script>

<div class="app-panel" data-ng-controller="groups" ng-cloak>

    <div class="uk-grid uk-grid-divider" data-uk-grid-margin>
        <div class="uk-width-medium-1-4">
            <ul class="uk-nav uk-nav-side group-list">
                <li class="uk-nav-header"><i class="uk-icon-group"></i> <?php echo $app("i18n")->get('Groups'); ?></li>
                <li data-ng-repeat="(group,data) in acl" data-ng-class="active==group ? 'uk-active':''">
                    <a href="#{{ group }}" data-ng-click="setActive(group)">{{ group }}</a>
                    <ul class="uk-subnav group-actions uk-animation-slide-right" data-ng-if="group!='admin'">
                        <li><a href="#" data-ng-click="addOrEditGroup(group)"><i class="uk-icon-pencil"></i></a></li>
                        <li><a href="#" data-ng-click="addOrEditGroup(group, true)"><i class="uk-icon-trash-o"></i></a></li>
                    </ul>
                </li>
            </ul>
            <hr>
            <button class="uk-button uk-button-success" data-ng-click="addOrEditGroup()" title="<?php echo $app("i18n")->get('Add group'); ?>" data-uk-tooltip="{pos:'bottom'}"><i class="uk-icon-plus"></i></button>
            <button class="uk-button uk-button-primary" data-ng-click="save()"><?php echo $app("i18n")->get('Save'); ?></button>
        </div>
        <div class="uk-width-medium-3-4">   
            
            <div class="uk-margin-large-bottom">
                <ul class="uk-tab" data-uk-tab="{connect:'#group-sections'}">
                    <li class="uk-active"><a><?php echo $app("i18n")->get("Access"); ?></a></li>
                    <li><a><?php echo $app("i18n")->get("Settings"); ?></a></li>
                </ul>
            </div>
            
            <div id="group-sections" class="uk-switcher uk-margin">
                <div>
                    <div class="uk-margin" data-ng-repeat="(resource, actions) in acl[active]">
                        
                        <div class="uk-grid uk-grid-divider">
                            <div class="uk-width-medium-1-3 uk-text-small">
                                <strong><i class="uk-icon-cog"></i> {{ resource }}</strong>
                            </div>
                            <div class="uk-width-medium-2-3">
                                <table class="uk-table uk-table-hover uk-text-small">
                                    <tbody>
                                        <tr data-ng-repeat="(key, value) in actions">
                                            <td data-ng-class="value ? '':'uk-text-muted'" width="80%">{{ key }}</td>
                                            <td align="right"><input type="checkbox" data-ng-disabled="active=='admin'" data-ng-model="acl[active][resource][key]"></td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                        
                    </div>                    
                </div>
                <div class="uk-form">
                    <div class="uk-form-row">
                        <label>
                            <?php echo $app("i18n")->get('Media root path'); ?>
                        </label>
                        <input type="text" placeholder="/" class="uk-width-1-1" data-ng-model="groupsettings[active]['media.path']" title="<?php echo $app("i18n")->get('Relative to'); ?> <?php echo  $app->pathToUrl("site:") ; ?>" data-uk-tooltip>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>