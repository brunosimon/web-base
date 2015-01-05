<?php if(count($regions)) { ?>

    <div class="uk-margin-bottom">
        <span class="uk-button-group">
            <?php if ($app->module("auth")->hasaccess("Regions", 'create.regions')) { ?>
            <a class="uk-button uk-button-success uk-button-small" href="<?php $app->route('/regions/region'); ?>" title="<?php echo $app("i18n")->get('Add region'); ?>" data-uk-tooltip="{pos:'bottom'}"><i class="uk-icon-plus-circle"></i></a>
            <?php } ?>
            <a class="uk-button app-button-secondary uk-button-small" href="<?php $app->route('/regions'); ?>" title="<?php echo $app("i18n")->get('Show all regions'); ?>" data-uk-tooltip="{pos:'bottom'}"><i class="uk-icon-ellipsis-h"></i></a>
        </span>
    </div>

    <span class="uk-text-small uk-text-uppercase uk-text-muted"><?php echo $app("i18n")->get('Latest'); ?></span>
    <ul class="uk-list uk-list-space">
        <?php foreach($regions as $region) { ?>
        <li><a href="<?php $app->route('/regions/region/'.$region['_id']); ?>"><i class="uk-icon-map-marker"></i> <?php echo  $region["name"] ; ?></a></li>
        <?php } ?>
    </ul>

<?php }else{ ?>

    <div class="uk-text-center">
        <h2><i class="uk-icon-th-large"></i></h2>
        <p class="uk-text-muted">
            <?php echo $app("i18n")->get('You don\'t have any regions created.'); ?>
        </p>

        <?php if ($app->module("auth")->hasaccess("Regions", 'create.regions')) { ?>
        <a href="<?php $app->route('/regions/region'); ?>" class="uk-button uk-button-success" title="<?php echo $app("i18n")->get('Create a region'); ?>" data-uk-tooltip="{pos:'bottom'}"><i class="uk-icon-plus-circle"></i></a>
        <?php } ?>
    </div>

<?php } ?>