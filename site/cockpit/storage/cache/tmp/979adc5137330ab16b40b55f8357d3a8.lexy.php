<div class="app-dashboard-widget">
    <div class="app-panel">
        <?php if(isset($title)) { ?>

        <div class="uk-panel app-panel-box docked">
            <div class="uk-clearfix">
                <strong><?php echo  $title ; ?></strong>

                <?php if(isset($badge)) { ?>
                    <span class="uk-float-right uk-badge"><?php echo  $badge ; ?></span>
                <?php } ?>
            </div>
        </div>

        <?php } ?>

        <?php echo  $content_for_layout ; ?>
    </div>
</div>