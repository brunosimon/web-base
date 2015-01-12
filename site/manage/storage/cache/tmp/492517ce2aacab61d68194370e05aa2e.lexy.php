<?php if(count($collections)) { ?>

    <div class="uk-margin-bottom">
        <span class="uk-button-group">
            <?php if ($app->module("auth")->hasaccess("Collections", 'manage.collections')) { ?>
            <a class="uk-button uk-button-success uk-button-small" href="<?php $app->route('/collections/collection'); ?>" title="<?php echo $app("i18n")->get('Add collection'); ?>" data-uk-tooltip="{pos:'bottom'}"><i class="uk-icon-plus-circle"></i></a>
            <?php } ?>
            <a class="uk-button app-button-secondary uk-button-small" href="<?php $app->route('/collections'); ?>" title="<?php echo $app("i18n")->get('Show all collections'); ?>" data-uk-tooltip="{pos:'bottom'}"><i class="uk-icon-ellipsis-h"></i></a>
        </span>
    </div>


    <span class="uk-text-small uk-text-uppercase uk-text-muted"><?php echo $app("i18n")->get('Latest'); ?></span>
    <ul class="uk-list uk-list-space">
        <?php foreach($collections as $collection) { ?>
        <li><a href="<?php $app->route('/collections/entries/'.$collection['_id']); ?>"><i class="uk-icon-map-marker"></i> <?php echo  $collection["name"] ; ?></a></li>
        <?php } ?>
    </ul>

<?php }else{ ?>

    <div class="uk-text-center">
        <h2><i class="uk-icon-list"></i></h2>
        <p class="uk-text-muted">
            <?php echo $app("i18n")->get('You don\'t have any collections created.'); ?>
        </p>
        <?php if ($app->module("auth")->hasaccess("Collections", 'manage.collections')) { ?>
        <a href="<?php $app->route('/collections/collection'); ?>" class="uk-button uk-button-success" title="<?php echo $app("i18n")->get('Create a collection'); ?>" data-uk-tooltip="{pos:'bottom'}"><i class="uk-icon-plus-circle"></i></a>
        <?php } ?>
    </div>

<?php } ?>