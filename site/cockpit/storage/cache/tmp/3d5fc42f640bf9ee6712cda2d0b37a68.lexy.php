<?php $app->start('header'); ?>

    <?php echo  $app->assets(['collections:assets/collections.js','collections:assets/js/collection.js'], $app['cockpit/version']) ; ?>
    <?php echo  $app->assets(['assets:vendor/uikit/js/components/nestable.min.js'], $app['cockpit/version']) ; ?>

    <?php $app->trigger('cockpit.content.fields.sources'); ?>

<?php $app->end('header'); ?>

<div data-ng-controller="collection" data-id="<?php echo  $id ; ?>" ng-cloak>

    <h1>
        <a href="<?php $app->route("/collections"); ?>"><?php echo $app("i18n")->get('Collections'); ?></a> /
        <span class="uk-text-muted" ng-show="!collection.name"><?php echo $app("i18n")->get('Collection'); ?></span>
        <span ng-show="collection.name">{{ collection.name }}</span>
    </h1>

    <form class="uk-form" data-ng-submit="save()" data-ng-show="collection">

        <div class="uk-grid">

            <div class="uk-width-3-4">

                    <div class="uk-form-row">
                        <input class="uk-width-1-1 uk-form-large" type="text" placeholder="<?php echo $app("i18n")->get('Name'); ?>" data-ng-model="collection.name" required>
                        <div class="uk-margin-top">
                            <input class="uk-width-1-1 uk-form-blank uk-text-muted" type="text" data-ng-model="collection.slug" app-slug="collection.name" placeholder="<?php echo $app("i18n")->get('Slug...'); ?>" title="slug" data-uk-tooltip="{pos:'left'}">
                        </div>
                    </div>

                    <div class="uk-form-row uk-margin uk-text-center" data-ng-show="!collection.fields || !collection.fields.length">
                            <div class="app-panel">
                                <h2><?php echo $app("i18n")->get('Fields'); ?></h2>
                                <p>
                                    <?php echo $app("i18n")->get('It seems you don\'t have any fields created.'); ?>
                                </p>
                            <button data-ng-click="addfield()" type="button" class="uk-button uk-button-success uk-button-large"><?php echo $app("i18n")->get('Add field'); ?></button>
                        </div>
                    </div>

                    <div class="uk-form-row uk-margin" data-ng-show="collection.fields && collection.fields.length">
                        <strong><?php echo $app("i18n")->get('Fields'); ?></strong>
                    </div>

                    <div class="uk-form-row" data-ng-show="collection.fields && collection.fields.length">
                        <ul class="uk-list">
                            <li class="uk-margin-bottom uk-clearfix" data-ng-repeat="field in collection.fields">

                                <div class="uk-panel app-panel">

                                    <div class="uk-grid uk-grid-small">
                                        <div class="uk-width-3-4">
                                            <input class="uk-width-1-1 uk-form-blank" type="text" data-ng-model="field.name" placeholder="<?php echo $app("i18n")->get('Field name'); ?>" pattern="[a-zA-Z0-9_]+" required>
                                        </div>
                                        <div class="uk-width-1-4 uk-text-right">
                                            <a ng-click="toggleOptions($index)"><i class="uk-icon-cog"></i></a>
                                            <a data-ng-click="remove(field)" class="uk-close"></a>
                                        </div>
                                    </div>

                                    <div id="options-field-{{ $index }}" class="app-panel-box docked-bottom uk-hidden">

                                        <div class="uk-grid uk-grid-small">
                                            <div class="uk-width-1-3">
                                                <label class="uk-text-small"><?php echo $app("i18n")->get('Field type'); ?></label>
                                                <select class="uk-width-1-1" data-ng-model="field.type" title="<?php echo $app("i18n")->get('Field type'); ?>" ng-options="f.name as f.label for f in contentfields"></select>
                                            </div>
                                            <div class="uk-width-1-3">
                                                <label class="uk-text-small"><?php echo $app("i18n")->get('Field label'); ?></label>
                                                <input class="uk-width-1-1" type="text" data-ng-model="field.label" placeholder="<?php echo $app("i18n")->get('Field label'); ?>">
                                            </div>
                                            <div class="uk-width-1-3">
                                                <label class="uk-text-small"><?php echo $app("i18n")->get('Default value'); ?></label>
                                                <input type="text" class="uk-width-1-1" data-ng-model="field.default" placeholder="<?php echo $app("i18n")->get('Default value'); ?>">
                                            </div>

                                            <div class="uk-width-1-1 uk-grid-margin">

                                                <strong class="uk-text-small">Extra options</strong>
                                                <hr>
                                                <div class="uk-form uk-form-horizontal">

                                                    <div class="uk-form-row">
                                                        <label class="uk-form-label"><?php echo $app("i18n")->get('Required'); ?></label>
                                                        <div class="uk-form-controls">
                                                            <input type="checkbox" data-ng-model="field.required" />
                                                        </div>
                                                    </div>

                                                    <div class="uk-form-row" data-ng-if="field.type=='text'">
                                                        <label class="uk-form-label"><?php echo $app("i18n")->get('Slug'); ?></label>
                                                        <div class="uk-form-controls">
                                                            <input type="checkbox" data-ng-model="field.slug" />
                                                        </div>
                                                    </div>

                                                    <?php if(count($locales)) { ?>
                                                    <div class="uk-form-row">
                                                        <label class="uk-form-label"><?php echo $app("i18n")->get('Localize'); ?></label>
                                                        <div class="uk-form-controls">
                                                            <input type="checkbox" data-ng-model="field.localize" />
                                                        </div>
                                                    </div>
                                                    <?php } ?>

                                                    <div class="uk-form-row" data-ng-if="field.type=='select'">
                                                        <label class="uk-form-label"><?php echo $app("i18n")->get('Options'); ?></label>
                                                        <div class="uk-form-controls">
                                                            <input class="uk-form-blank" type="text" data-ng-model="field.options" ng-list placeholder="<?php echo $app("i18n")->get('options...'); ?>" title="<?php echo $app("i18n")->get('Separate different options by comma'); ?>" data-uk-tooltip>
                                                        </div>
                                                    </div>

                                                    <div class="uk-form-row" data-ng-if="field.type=='media'">
                                                        <label class="uk-form-label"><?php echo $app("i18n")->get('Extensions'); ?></label>
                                                        <div class="uk-form-controls">
                                                            <input class="uk-form-blank" type="text" data-ng-model="field.allowed" placeholder="*.*" title="<?php echo $app("i18n")->get('Allowed media types'); ?>" data-uk-tooltip>
                                                        </div>
                                                    </div>

                                                    <div class="uk-form-row" data-ng-if="field.type=='code'">
                                                        <label class="uk-form-label"><?php echo $app("i18n")->get('Syntax'); ?></label>
                                                        <div class="uk-form-controls">
                                                            <select data-ng-model="field.syntax" title="<?php echo $app("i18n")->get('Code syntax'); ?>" data-uk-tooltip>
                                                                <option value="text">Text</option>
                                                                <option value="css">CSS</option>
                                                                <option value="htmlmixed">Html</option>
                                                                <option value="javascript">Javascript</option>
                                                                <option value="markdown">Markdown</option>
                                                            </select>
                                                        </div>
                                                    </div>

                                                    <div class="uk-form-row" data-ng-if="field.type=='link-collection'">
                                                        <label class="uk-form-label"><?php echo $app("i18n")->get('Collection'); ?></label>
                                                        <div class="uk-form-controls">
                                                            <select ng-options="c._id as c.name for c in collections" data-ng-model="field.collection" title="<?php echo $app("i18n")->get('Related collection'); ?>" data-uk-tooltip required></select>
                                                            <input type="checkbox" data-ng-model="field.multiple"> <?php echo $app("i18n")->get('multiple'); ?>
                                                        </div>
                                                    </div>

                                                    <div class="uk-form-row" data-ng-if="field.type=='multifield'">
                                                        <label class="uk-form-label"><?php echo $app("i18n")->get('Multi field'); ?></label>
                                                        <div class="uk-form-controls">
                                                            <span class="uk-text-muted uk-text-small"><?php echo $app("i18n")->get('Allowed fields'); ?>:</span>
                                                            <div class="uk-scrollable-box uk-panel-box uk-margin-small-top">
                                                                <div ng-repeat="cf in contentfields" ng-if="(['select', 'boolean', 'multifield'].indexOf(cf.name) == -1)">
                                                                    <input type="checkbox" data-ng-model="field.allowedfields[cf.name]"> {{ cf.label }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <?php $app->trigger('cockpit.content.fields.settings'); ?>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                            </li>
                        </ul>

                        <button data-ng-click="addfield()" type="button" class="uk-button uk-button-success"><i class="uk-icon-plus-circle" title="<?php echo $app("i18n")->get('Add field'); ?>"></i></button>
                    </div>
                    <br>
                    <br>

                    <div class="uk-form-row" data-ng-show="collection.fields && collection.fields.length">
                        <div class="uk-button-group">
                            <button type="submit" class="uk-button uk-button-primary uk-button-large"><?php echo $app("i18n")->get('Save Collection'); ?></button>
                            <a href="<?php $app->route('/collections/entries'); ?>/{{ collection._id }}" class="uk-button uk-button-large" data-ng-show="collection._id"><i class="uk-icon-list"></i> <?php echo $app("i18n")->get('Goto entries'); ?></a>
                        </div>
                        &nbsp;
                        <a href="<?php $app->route('/collections'); ?>"><?php echo $app("i18n")->get('Cancel'); ?></a>
                    </div>

            </div>
            <div class="uk-width-1-4" data-ng-show="collection.fields && collection.fields.length">

                <strong><?php echo $app("i18n")->get('Settings'); ?></strong>

                <div class="uk-margin">
                    <p><?php echo $app("i18n")->get("Group"); ?></p>
                    <div class="uk-form-controls uk-margin-small-top">
                        <div class="uk-form-select">
                            <i class="uk-icon-sitemap uk-margin-small-right"></i>
                            <a>{{ collection.group || '- <?php echo $app("i18n")->get("No group"); ?> -' }}</a>
                            <select class="uk-width-1-1 uk-margin-small-top" data-ng-model="collection.group">
                                <option ng-repeat="group in groups" value="{{ group }}">{{ group }}</option>
                                <option value="">- <?php echo $app("i18n")->get("No group"); ?> -</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="uk-margin">
                    <p>
                        <?php echo $app("i18n")->get('Fields on entries list page'); ?>:
                    </p>
                    <ul id="fields-list" class="uk-nestable" data-uk-nestable="{maxDepth:1}">
                        <li class="uk-nestable-list-item" data-ng-repeat="field in collection.fields">
                            <div class="uk-nestable-item uk-nestable-item-table">
                                <div class="uk-nestable-handle"></div>
                                <input type="checkbox" data-ng-checked="field.lst" data-ng-model="field.lst">
                                {{ field.name }}
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="uk-margin">
                    <p>
                        <?php echo $app("i18n")->get('Order entries on list page'); ?>:
                    </p>

                    <select class="uk-width-1-1 uk-margin-bottom" data-ng-model="collection.sortfield"  ng-options="f.name as f.name for f in sortfields"></select>
                    <select class="uk-width-1-1" data-ng-model="collection.sortorder">
                        <option value="-1"><?php echo $app("i18n")->get('descending'); ?></option>
                        <option value="1"><?php echo $app("i18n")->get('ascending'); ?></option>
                    </select>
                </div>

            </div>

        </div>

    </form>
</div>
