<?php $app->start('header'); ?>

    <?php echo  $app->assets(['collections:assets/collections.js','collections:assets/js/entries.js'], $app['cockpit/version']) ; ?>

    <?php if($collection['sortfield'] == 'custom-order') { ?>

        <?php echo  $app->assets(['assets:vendor/uikit/js/components/sortable.min.js'], $app['cockpit/version']) ; ?>

    <?php } ?>

    <style>
        td .uk-grid+.uk-grid { margin-top: 5px; }

        .uk-sortable-dragged {
            border: 1px #ccc dashed;
            height: 40px;
            background: rgba(255, 255, 255, 0.3);
            border-radius: 2px;
        }

        .uk-sortable-dragged td {
            display: none;
        }
    </style>

    <script>
        var COLLECTION = <?php echo  json_encode($collection) ; ?>;
    </script>

<?php $app->end('header'); ?>



<div data-ng-controller="entries" ng-cloak>

    <nav class="uk-navbar uk-margin-bottom">
        <span class="uk-navbar-brand"><a href="<?php $app->route("/collections"); ?>"><?php echo $app("i18n")->get('Collections'); ?></a> / <?php echo  $collection['name'] ; ?></span>
        <ul class="uk-navbar-nav">
            <?php if ($app->module("auth")->hasaccess("Collections", 'manage.collections')) { ?>
            <li><a href="<?php $app->route('/collections/collection/'.$collection["_id"]); ?>" title="<?php echo $app("i18n")->get('Edit collection'); ?>" data-uk-tooltip="{pos:'bottom'}"><i class="uk-icon-pencil"></i></a></li>
            <li><a class="uk-text-danger" ng-click="emptytable()" title="<?php echo $app("i18n")->get('Empty collection'); ?>" data-uk-tooltip="{pos:'bottom'}"><i class="uk-icon-trash-o"></i></a></li>
            <?php } ?>
            <li><a href="<?php $app->route('/collections/entry/'.$collection["_id"]); ?>" title="<?php echo $app("i18n")->get('Add entry'); ?>" data-uk-tooltip="{pos:'bottom'}"><i class="uk-icon-plus-circle"></i></a></li>
        </ul>

        <?php if($collection['sortfield'] != 'custom-order') { ?>
        <div class="uk-navbar-content" data-ng-show="collection && collection.count">
            <form class="uk-form uk-margin-remove uk-display-inline-block" method="get" action="?nc=<?php echo  time() ; ?>">
                <div class="uk-form-icon">
                    <i class="uk-icon-filter"></i>
                    <input type="text" placeholder="<?php echo $app("i18n")->get('Filter entries...'); ?>" name="filter" value="<?php echo  $app->param('filter', '') ; ?>"> &nbsp;
                    <a class="uk-text-small" href="<?php $app->route('/collections/entries/'.$collection['_id']); ?>" data-ng-show="filter"><i class="uk-icon-times"></i> <?php echo $app("i18n")->get('Reset filter'); ?></a>
                </div>
            </form>
        </div>
        <?php } ?>

        <div class="uk-navbar-flip">
            <?php if ($app->module("auth")->hasaccess("Collections", 'manage.collections')) { ?>
            <ul class="uk-navbar-nav">
                <li>
                    <a href="<?php $app->route('/api/collections/export/'.$collection['_id']); ?>" download="<?php echo  $collection['name'] ; ?>.json" title="<?php echo $app("i18n")->get('Export data'); ?>" data-uk-tooltip="{pos:'bottom'}">
                        <i class="uk-icon-share-alt"></i>
                    </a>
                </li>
            </ul>
            <?php } ?>
        </div>
    </nav>

    <div class="app-panel uk-margin uk-text-center" data-ng-show="entries && !filter && !entries.length">
        <h2><i class="uk-icon-list"></i></h2>
        <p class="uk-text-large">
            <?php echo $app("i18n")->get('It seems you don\'t have any entries created.'); ?>
        </p>
        <a href="<?php $app->route('/collections/entry/'.$collection["_id"]); ?>" class="uk-button uk-button-success uk-button-large"><?php echo $app("i18n")->get('Add entry'); ?></a>
    </div>

    <div class="app-panel uk-margin uk-text-center" data-ng-show="entries && filter && !entries.length">
        <h2><i class="uk-icon-search"></i></h2>
        <p class="uk-text-large">
            <?php echo $app("i18n")->get('No entries found.'); ?>
        </p>
    </div>

    <div class="uk-grid" data-uk-grid-margin data-ng-show="entries && entries.length">

        <div class="uk-width-1-1">
            <div class="app-panel">
                <table id="entries-table" class="uk-table uk-table-striped" multiple-select="{model:entries}">
                    <thead>
                        <tr>
                            <th width="10"><input class="js-select-all" type="checkbox"></th>
                            <th>
                                <?php echo $app("i18n")->get('Fields'); ?>
                            </th>
                            <th width="15%"><?php echo $app("i18n")->get('Modified'); ?></th>
                            <th width="10%">&nbsp;</th>
                        </tr>
                    </thead>
                    <tbody <?php echo  $collection['sortfield'] == 'custom-order' ? 'data-uk-sortable="{animation:false}"':'' ; ?>>
                        <tr class="js-multiple-select" data-ng-repeat="entry in entries track by entry._id">
                            <td><input class="js-select" type="checkbox"></td>
                            <td>
                                <div class="uk-grid uk-grid-preserve uk-text-small" data-ng-repeat="field in fields" data-ng-if="fields.length">
                                    <div class="uk-width-medium-1-5">
                                        <strong>{{ (field.label || field.name) }}</strong>
                                    </div>
                                    <div class="uk-width-medium-4-5">
                                        <a class="uk-link-muted" href="<?php $app->route('/collections/entry/'.$collection["_id"]); ?>/{{ entry._id }}">{{ entry[field.name] }}</a>
                                    </div>
                                </div>
                                <div class="uk-text-small" data-ng-if="!fields.length">
                                    <a href="<?php $app->route('/collections/entry/'.$collection["_id"]); ?>/{{ entry._id }}"><?php echo $app("i18n")->get('Show entry'); ?></a>
                                </div>
                            </td>
                            <td>{{ entry.modified | fmtdate:'d M, Y' }}</td>
                            <td class="uk-text-right">
                                <div data-uk-dropdown>
                                    <i class="uk-icon-bars"></i>
                                    <div class="uk-dropdown uk-dropdown-flip uk-text-left">
                                        <ul class="uk-nav uk-nav-dropdown uk-nav-parent-icon">
                                            <li><a href="<?php $app->route('/collections/entry/'.$collection["_id"]); ?>/{{ entry._id }}"><i class="uk-icon-pencil"></i> <?php echo $app("i18n")->get('Edit entry'); ?></a></li>
                                            <li><a href="#" data-ng-click="remove($index, entry._id)"><i class="uk-icon-trash-o"></i> <?php echo $app("i18n")->get('Delete entry'); ?></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>

                <div class="uk-margin-top">
                    <?php if($collection['sortfield'] != 'custom-order') { ?>
                    <button class="uk-button uk-button-primary" data-ng-click="loadmore()" data-ng-show="entries && !nomore"><?php echo $app("i18n")->get('Load more...'); ?></button>
                    <?php } ?>
                    <button class="uk-button uk-button-danger" data-ng-click="removeSelected()" data-ng-show="selected"><i class="uk-icon-trash-o"></i> <?php echo $app("i18n")->get('Delete entries'); ?></button>
                </div>

            </div>
        </div>
</div>
