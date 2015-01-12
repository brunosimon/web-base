<?php if(count($forms)) { ?>

    <div class="uk-margin-bottom">
        <span class="uk-button-group">
            <?php if ($app->module("auth")->hasaccess("Forms", 'manage.forms')) { ?>
            <a class="uk-button uk-button-success uk-button-small" href="<?php $app->route('/forms/form'); ?>" title="<?php echo $app("i18n")->get('Add form'); ?>" data-uk-tooltip="{pos:'bottom'}"><i class="uk-icon-plus-circle"></i></a>
            <?php } ?>
            <a class="uk-button app-button-secondary uk-button-small" href="<?php $app->route('/forms'); ?>" title="<?php echo $app("i18n")->get('Show all forms'); ?>" data-uk-tooltip="{pos:'bottom'}"><i class="uk-icon-ellipsis-h"></i></a>
        </span>
    </div>

    <span class="uk-text-small uk-text-uppercase uk-text-muted"><?php echo $app("i18n")->get('Latest'); ?></span>
    <ul class="uk-list uk-list-space">
        <?php foreach($forms as $form) { ?>
        <li><a href="<?php $app->route('/forms/entries/'.$form['_id']); ?>"><i class="uk-icon-map-marker"></i> <?php echo  $form["name"] ; ?></a></li>
        <?php } ?>
    </ul>

<?php }else{ ?>

    <div class="uk-text-center">
        <h2><i class="uk-icon-inbox"></i></h2>
        <p class="uk-text-muted">
            <?php echo $app("i18n")->get('You don\'t have any forms created.'); ?>
        </p>

        <?php if ($app->module("auth")->hasaccess("Forms", 'manage.forms')) { ?>
        <a href="<?php $app->route('/forms/form'); ?>" class="uk-button uk-button-success" title="<?php echo $app("i18n")->get('Create a form'); ?>" data-uk-tooltip="{pos:'bottom'}"><i class="uk-icon-plus-circle"></i></a>
        <?php } ?>
    </div>

<?php } ?>
