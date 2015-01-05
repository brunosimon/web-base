<?php $app->start('header'); ?>

    <style>

        .app-dashboard-widget {
            margin-bottom: 25px;
        }

    </style>

<?php $app->end('header'); ?>

<div class="uk-grid" data-uk-grid-margin>
    <div class="uk-width-medium-1-2">
        <?php $app->trigger('admin.dashboard.main'); ?>
    </div>
    <div class="uk-width-medium-1-2">
        <div class="uk-grid uk-grid-width-medium-1-2" data-uk-grid-match="{target:'.app-panel'}">
            <?php $app->trigger('admin.dashboard.aside'); ?>
        </div>
    </div>
</div>