<?php $app->start('header'); ?>

    <?php echo  $app->assets(['assets:vendor/codemirror/codemirror.js','assets:vendor/codemirror/codemirror.css','assets:vendor/codemirror/pastel-on-dark.css'], $app['cockpit/version']) ; ?>

    <?php echo  $app->assets(['assets:vendor/loadie/jquery.loadie.js', 'assets:vendor/loadie/loadie.css'], $app['cockpit/version']) ; ?>

    <?php echo  $app->assets(['assets:vendor/uikit/js/components/upload.min.js']) ; ?>
    <?php echo  $app->assets(['assets:vendor/fuzzysearch.js']) ; ?>

    <?php echo  $app->assets(['mediamanager:assets/js/index.js'], $app['cockpit/version']) ; ?>

    <?php echo  $app->assets(['assets:js/angular/directives/mediapreview.js'], $app['cockpit/version']) ; ?>

<?php $app->end('header'); ?>


<div data-ng-controller="mediamanager" ng-cloak>

    <div class="uk-navbar">
        <span class="uk-hidden-small uk-navbar-brand"><?php echo $app("i18n")->get('Mediamanager'); ?></span>
        <ul class="uk-navbar-nav">
            <li class="uk-parent" data-uk-dropdown>
                <a><i class="uk-icon-star"></i><span class="uk-hidden-small">&nbsp; <?php echo $app("i18n")->get('Bookmarks'); ?></span></a>
                <div class="uk-dropdown uk-dropdown-navbar">

                    <ul id="mmbookmarks" class="uk-nav uk-nav-navbar uk-nav-parent-icon" ng-show="(bookmarks.folders.length || bookmarks.files.length)">

                        <li class="uk-nav-header" ng-if="bookmarks.folders.length"><?php echo $app("i18n")->get('Folders'); ?></li>
                        <li ng-repeat="folder in bookmarks.folders" ng-if="bookmarks.folders.length">
                            <a data-idx="{{ $index }}" data-group="folders" href="#{{ folder.path }}" ng-click="updatepath(folder.path)" draggable="true"><i class="uk-icon-folder-o"></i> {{ folder.name }}</a>
                        </li>

                        <li class="uk-nav-header" ng-if="bookmarks.files.length"><?php echo $app("i18n")->get('Files'); ?></li>
                        <li ng-repeat="file in bookmarks.files" ng-if="bookmarks.files.length">
                            <a data-idx="{{ $index }}" data-group="files" ng-click="open(file)" draggable="true"><i class="uk-icon-file-o"></i> {{ file.name }}</a>
                        </li>


                    </ul>

                    <div class="uk-text-muted" ng-show="(!bookmarks.folders.length && !bookmarks.files.length)">
                        <?php echo $app("i18n")->get('You have nothing bookmarked.'); ?>
                    </div>
                </div>
            </li>
            <li><a href="" ng-click="action('createfolder')"><i class="uk-icon-plus-circle"></i><span class="uk-hidden-small">&nbsp; <?php echo $app("i18n")->get('Folder'); ?></span></a></li>
            <li><a href="" ng-click="action('createfile')"><i class="uk-icon-plus-circle"></i><span class="uk-hidden-small">&nbsp; <?php echo $app("i18n")->get('File'); ?></span></a></li>
        </ul>

        <div class="uk-navbar-flip">

            <div class="uk-navbar-content">
                <div id="dirsearch" class="uk-autocomplete uk-search" ng-show="dirlist">
                    <input class="uk-search-field" type="text" placeholder="..." data-uk-tooltip title="<?php echo $app("i18n")->get('Find files...'); ?>">
                    <div class="uk-dropdown uk-dropdown-flip"></div>
                </div>
            </div>

            <div class="uk-navbar-content">
                <span class="uk-button uk-form-file" data-uk-tooltip title="<?php echo $app("i18n")->get('Upload files'); ?>">
                    <input id="js-upload-select" type="file" multiple="true" title="">
                    <i class="uk-icon-plus"></i>
                </span>
            </div>
        </div>
    </div>
    <br>
    <div class="app-panel">

        <div class="uk-panel app-panel-box docked">
            <ul class="uk-breadcrumb">
                <li ng-click="updatepath('/')"><a href="#/" title="Change dir to root"><i class="uk-icon-home"></i></a></li>
                <li ng-repeat="crumb in breadcrumbs"><a href="#{{ crumb.path }}" ng-click="updatepath(crumb.path)" title="Change dir to {{ crumb.name }}">{{ crumb.name }}</a></li>
            </ul>
        </div>

        <div class="uk-navbar uk-margin-large-bottom">

            <div class="uk-navbar-content">
                <div class="uk-button-group uk-margin-right">
                    <button class="uk-button" data-ng-class="mode=='table' ? 'uk-button-primary':''" data-ng-click="(mode='table')" title="<?php echo $app("i18n")->get('Table mode'); ?>" data-uk-tooltip="{pos:'bottom'}"><i class="uk-icon-th-list"></i></button>
                    <button class="uk-button" data-ng-class="mode=='list' ? 'uk-button-primary':''" data-ng-click="(mode='list')" title="<?php echo $app("i18n")->get('List mode'); ?>" data-uk-tooltip="{pos:'bottom'}"><i class="uk-icon-th"></i></button>
                </div>
                <button class="uk-button uk-button-danger" ng-click="deleteSelected()" ng-show="hasSelected()"><i class="uk-icon-trash-o"></i></button>
            </div>

            <div class="uk-navbar-content">
                <span class="uk-alert uk-alert-warning" data-ng-show="dir && (dir.folders.length && viewfilter=='files')"><span class="uk-icon-bolt"></span> <?php echo $app("i18n")->get('Folders hidden via filter'); ?>: <strong>{{ dir.folders.length }}</strong></span>
                <span class="uk-alert uk-alert-warning" data-ng-show="dir && (dir.files.length && viewfilter=='folders')"><span class="uk-icon-bolt"></span> <?php echo $app("i18n")->get('Files hidden via filter'); ?>: <strong>{{ dir.files.length }}</strong></span>
            </div>
            <div class="uk-navbar-flip">
                <div class="uk-navbar-content uk-form">
                    <div class="uk-form-icon uk-hidden-small">
                        <i class="uk-icon-filter"></i>
                        <input type="text" placeholder="<?php echo $app("i18n")->get('Filter by name...'); ?>" data-ng-model="namefilter">
                    </div>
                    <div class="uk-button-group">
                        <button class="uk-button" data-ng-class="viewfilter=='all' ? 'uk-button-primary':''" data-ng-click="(viewfilter='all')" title="<?php echo $app("i18n")->get('Show files + directories'); ?>" data-uk-tooltip="{pos:'bottom'}"><?php echo $app("i18n")->get('All'); ?></button>
                        <button class="uk-button" data-ng-class="viewfilter=='folders' ? 'uk-button-primary':''" data-ng-click="(viewfilter='folders')" title="<?php echo $app("i18n")->get('Show only directories'); ?>" data-uk-tooltip="{pos:'bottom'}"><i class="uk-icon-folder-o"></i> <span class="uk-text-small">{{dir.folders.length}}</span></button>
                        <button class="uk-button" data-ng-class="viewfilter=='files' ? 'uk-button-primary':''" data-ng-click="(viewfilter='files')" title="<?php echo $app("i18n")->get('Show only files'); ?>" data-uk-tooltip="{pos:'bottom'}"><i class="uk-icon-file-o"></i> <span class="uk-text-small">{{dir.files.length}}</span></button>
                    </div>
                </div>
            </div>
        </div>

        <ul class="uk-grid uk-grid-small" data-ng-show="mode=='list' && dir && (dir.folders.length || dir.files.length)">
            <li class="uk-grid-margin uk-width-medium-1-5 uk-width-1-1" ng-repeat="folder in dir.folders track by folder.path" data-type="folder" data-ng-hide="(viewfilter=='files' || !matchName(folder.name))">
                <div class="app-panel">
                    <span class="js-select"><input type="checkbox" ng-model="selected[folder.path]"></span>
                    <div class="mm-type mm-type-folder">
                        <i class="uk-icon-folder-o"></i>
                    </div>
                    <div class="app-panel-box docked-bottom uk-text-center">
                        <div class="uk-text-truncate mm-caption" title="{{ folder.name }}"><a href="#{{ folder.path }}" ng-click="updatepath(folder.path)">{{ folder.name }}</a></div>
                        <ul class="uk-subnav uk-subnav-line mm-actions">
                            <li><a ng-click="addBookmark(folder)" title="<?php echo $app("i18n")->get('Bookmark folder'); ?>"><i class="uk-icon-star"></i></a></li>
                            <li><a ng-click="action('rename', folder)" title="<?php echo $app("i18n")->get('Rename folder'); ?>"><i class="uk-icon-text-width"></i></a></li>
                            <li><a ng-click="action('remove', folder)" title="<?php echo $app("i18n")->get('Delete folder'); ?>"><i class="uk-icon-minus-circle"></i></a></li>
                        </ul>
                    </div>
                </div>
            </li>
            <li class="uk-grid-margin uk-width-medium-1-5 uk-width-1-1" ng-repeat="file in dir.files track by file.path" data-ng-hide="(viewfilter=='folders' || !matchName(file.name))">
                <div class="app-panel">
                    <span class="js-select"><input type="checkbox" ng-model="selected[file.path]"> </span>
                    <div class="mm-type mm-type-file">
                        <i class="uk-icon-file-o" media-preview="{{ file.url }}"></i>
                    </div>
                    <div class="app-panel-box docked-bottom uk-text-center">
                        <div class="uk-text-truncate mm-caption" title="{{ file.name }}"><a ng-click="open(file)">{{ file.name }}</a></div>
                        <ul class="uk-subnav uk-subnav-line mm-actions">
                            <li><a ng-click="addBookmark(file)" title="<?php echo $app("i18n")->get('Bookmark file'); ?>"><i class="uk-icon-star"></i></a></li>
                            <li><a ng-click="action('rename', file)" title="<?php echo $app("i18n")->get('Rename file'); ?>"><i class="uk-icon-text-width"></i></a></li>
                            <li><a ng-click="action('download', file)" title="<?php echo $app("i18n")->get('Download file'); ?>"><i class="uk-icon-paperclip"></i></a></li>
                            <li><a ng-click="action('remove', file)" title="<?php echo $app("i18n")->get('Delete file'); ?>"><i class="uk-icon-minus-circle"></i></a></li>
                        </ul>
                    </div>
                </div>
            </li>
        </ul>


        <table class="uk-table uk-table-hover media-table" data-ng-show="mode=='table' && dir && (dir.folders.length || dir.files.length)">
            <thead>
                <tr>
                    <th width="20" ng-click="selectAllToggle()"><input type="checkbox" ng-model="selectAll"></th>
                    <th width="20"></th>
                    <th><?php echo $app("i18n")->get('Name'); ?></th>
                    <th class="uk-text-right" width="100"><?php echo $app("i18n")->get('Size'); ?></th>
                    <th class="uk-text-right" width="150"><?php echo $app("i18n")->get('Last modified'); ?></th>
                    <th class="uk-text-right" width="50">&nbsp;</th>
                </tr>
            </thead>
            <tbody>
                <tr ng-repeat="folder in dir.folders track by folder.path" data-type="folder" data-ng-hide="(viewfilter=='files' || !matchName(folder.name))">
                   <td><input type="checkbox" ng-model="selected[folder.path]"></td>
                   <td><i class="uk-icon-folder-o"></i></td>
                   <td><div class="uk-text-truncate" title="{{ folder.name }}"><a href="#{{ folder.path }}" ng-click="updatepath(folder.path)">{{ folder.name }}</a></div></td>
                   <td>&nbsp;</td>
                   <td>&nbsp;</td>
                   <td class="uk-text-right">
                       <div class="mm-actions" data-uk-dropdown="{mode:'click'}">
                           <i class="uk-icon-bars"></i>
                           <div class="uk-dropdown uk-dropdown-flip uk-text-left">
                               <ul class="uk-nav uk-nav-dropdown uk-nav-parent-icon">
                                   <li class="uk-nav-header uk-text-truncate"><i class="uk-icon-folder-o"></i> {{ folder.name }}</li>
                                   <li><a ng-click="addBookmark(folder)" title="<?php echo $app("i18n")->get('Bookmark folder'); ?>"><i class="uk-icon-star"></i> <?php echo $app("i18n")->get('Bookmark folder'); ?></a></li>
                                   <li><a ng-click="action('rename', folder)" title="<?php echo $app("i18n")->get('Rename folder'); ?>"><i class="uk-icon-text-width"></i> <?php echo $app("i18n")->get('Rename folder'); ?></a></li>
                                   <li class="uk-nav-divider"></li>
                                   <li class="uk-danger"><a ng-click="action('remove', folder)" title="<?php echo $app("i18n")->get('Delete folder'); ?>"><i class="uk-icon-minus-circle"></i> <?php echo $app("i18n")->get('Delete folder'); ?></a></li>
                               </ul>
                           </div>
                       </div>
                   </td>
                </tr>

                <tr ng-repeat="file in dir.files track by file.path" data-type="file" data-ng-hide="(viewfilter=='folders' || !matchName(file.name))">
                   <td><input type="checkbox" ng-model="selected[file.path]"></td>
                   <td><i class="uk-icon-file-o" media-preview="{{ file.url }}"></i></td>
                   <td><div class="uk-text-truncate" title="{{ file.name }}"><a ng-click="open(file)">{{ file.name }}</a></div></td>
                   <td class="uk-text-right">{{ file.size }}</td>
                   <td class="uk-text-right">{{ file.lastmodified }}</td>
                   <td class="uk-text-right">
                       <div class="mm-actions" data-uk-dropdown="{mode:'click'}">
                           <i class="uk-icon-bars"></i>
                           <div class="uk-dropdown uk-dropdown-flip uk-text-left">
                               <ul class="uk-nav uk-nav-dropdown uk-nav-parent-icon">
                                   <li class="uk-nav-header uk-text-truncate"><i class="uk-icon-file-o"></i> {{ file.name }}</li>
                                   <li><a ng-click="addBookmark(file)" title="<?php echo $app("i18n")->get('Bookmark file'); ?>"><i class="uk-icon-star"></i> <?php echo $app("i18n")->get('Bookmark file'); ?></a></li>
                                   <li><a ng-click="action('rename', file)" title="<?php echo $app("i18n")->get('Rename file'); ?>"><i class="uk-icon-text-width"></i> <?php echo $app("i18n")->get('Rename file'); ?></a></li>
                                   <li><a ng-click="action('download', file)" title="<?php echo $app("i18n")->get('Download file'); ?>"><i class="uk-icon-paperclip"></i> <?php echo $app("i18n")->get('Download file'); ?></a></li>
                                   <li class="uk-nav-divider"></li>
                                   <li class="uk-danger"><a ng-click="action('remove', file)" title="<?php echo $app("i18n")->get('Delete file'); ?>"><i class="uk-icon-minus-circle"></i> <?php echo $app("i18n")->get('Delete file'); ?></a></li>
                               </ul>
                           </div>
                       </div>
                   </td>
                </tr>
            </tbody>
        </table>

        <div class="uk-margin uk-text-center" data-ng-show="dir && (!dir.folders.length && !dir.files.length)">
            <h2><i class="uk-icon-folder-open-o"></i></h2>
            <p class="uk-text-large">
                <?php echo $app("i18n")->get('This folder is empty.'); ?>
            </p>
        </div>

    </div>
</div>

<div id="mm-image-preview" class="uk-modal">
    <div class="uk-modal-dialog">
        <div class="modal-content uk-text-center"></div>
    </div>
</div>

<div id="mm-editor">
    <nav class="uk-navbar">
        <div class="uk-navbar-content">
            <i class="uk-icon-pencil"></i> &nbsp; <strong class="uk-text-small filename"></strong>
        </div>
        <ul class="uk-navbar-nav">
            <li><a data-editor-action="save" title="<?php echo $app("i18n")->get('Save file'); ?>" data-uk-tooltip><i class="uk-icon-save"></i></a></li>
        </ul>
        <div class="uk-navbar-flip">
            <ul class="uk-navbar-nav">
                <li><a data-editor-action="close" title="<?php echo $app("i18n")->get('Close file'); ?>" data-uk-tooltip><i class="uk-icon-times"></i></a></li>
            </ul>
        </div>
    </nav>
    <textarea></textarea>
</div>

<style>

    ul .app-panel {
        position: relative;
    }

    .app-panel a {
        cursor: pointer;
    }

    .app-panel .js-select {
        position: absolute;
        top: 10px;
        right: 10px;
    }

    .mm-type {
        position: relative;
        text-align: center;
        padding: 15px;
        height: 50px;
    }

    .mm-type > div {
        height: 40px;
    }

    .mm-type > i {
        font-size: 40px;
    }

    .mm-type-folder > i {
        color: #ccc;
    }

    .mm-caption {
        text-align: center;
    }

    ul .mm-actions { margin: 10px 0 0 0; }

    ul .mm-actions > li > a { color: #ccc; }

    .media-dir .uk-icon-folder-o, .media-table .uk-icon-folder-o {
        color: #999;
    }

    .mm-actions { cursor: pointer; }

    .media-dir .media-url-preview {
        height: 35px;
        width: 35px;
        display: inline-block;
    }

    .media-table .media-url-preview {
        height: 14px;
        width: 14px;
    }

    /* dirsearch */

    #dirsearch .uk-dropdown {
        width: 400px;
    }

    /* editor */

    #mm-editor {
        display: none;
        position: fixed;
        left: 0;
        top: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.3);
        border: 10px rgba(0,0,0,0.3) solid;
        z-index: 100;
    }

    #mm-editor .uk-navbar {
        background: #f7f7f7;
        border-radius: 3px 3px 0 0;
    }

    #mm-editor .CodeMirror {
        border: none;
        border-radius:  0 0 3px 3px;
    }

    #mm-editor a { cursor: pointer; }

</style>