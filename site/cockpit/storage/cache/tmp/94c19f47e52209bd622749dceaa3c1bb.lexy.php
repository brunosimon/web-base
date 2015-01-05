<?php if(count($galleries)) { ?>

    <div class="uk-margin-bottom">
        <span class="uk-button-group">
            <?php if ($app->module("auth")->hasaccess("Galleries", 'create.gallery')) { ?>
            <a class="uk-button uk-button-success uk-button-small" href="<?php $app->route('/galleries/gallery'); ?>" title="<?php echo $app("i18n")->get('Add gallery'); ?>" data-uk-tooltip="{pos:'bottom'}"><i class="uk-icon-plus-circle"></i></a>
            <?php } ?>
            <a class="uk-button app-button-secondary uk-button-small" href="<?php $app->route('/galleries'); ?>" title="<?php echo $app("i18n")->get('Show all galleries'); ?>" data-uk-tooltip="{pos:'bottom'}"><i class="uk-icon-ellipsis-h"></i></a>
        </span>
    </div>

    <span class="uk-text-small uk-text-uppercase uk-text-muted"><?php echo $app("i18n")->get('Latest'); ?></span>
    <ul class="uk-list uk-list-space">
        <?php foreach($galleries as $gallery) { ?>
        <li>
            <a href="<?php $app->route('/galleries/gallery/'.$gallery['_id']); ?>">
                <i class="uk-icon-map-marker"></i> <?php echo  $gallery["name"] ; ?>
                <?php if(count($gallery["images"])) { ?>
                <div class="uk-margin-small-top">
                    <?php foreach(array_slice($gallery["images"], 0, 6) as $image) { ?>
                    <div class="uk-thumbnail uk-rounded uk-thumb-small">
                        <img src="<?php echo cockpit("mediamanager")->thumbnail($image['path'], 25, 25); ?>" width="25" height="25" title="<?php echo  $image['path'] ; ?>">
                    </div>
                    <?php } ?>
                </div>
                <?php } ?>
            </a>
        </li>
        <?php } ?>
    </ul>
<?php }else{ ?>

    <div class="uk-text-center">
        <h2><i class="uk-icon-picture-o"></i></h2>
        <p class="uk-text-muted">
            <?php echo $app("i18n")->get('You don\'t have any galleries created.'); ?>
        </p>

        <?php if ($app->module("auth")->hasaccess("Galleries", 'create.gallery')) { ?>
        <a href="<?php $app->route('/galleries/gallery'); ?>" class="uk-button uk-button-success" title="<?php echo $app("i18n")->get('Create a gallery'); ?>" data-uk-tooltip="{pos:'bottom'}"><i class="uk-icon-plus-circle"></i></a>
        <?php } ?>
    </div>

<?php } ?>