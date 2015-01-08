
<h1><a href="<?php $app->route('/settingspage'); ?>"><?php echo $app("i18n")->get('Settings'); ?></a> / System Information</h1>

<div class="uk-grid" data-uk-grid-margin>

    <div class="uk-width-medium-3-4">
        <div class="app-panel">

            <div id="settings-info" class="uk-switcher">

                <div>

                    <p><strong><span class="uk-badge app-badge">System</span></strong></p>

                    <strong>General</strong>
                    <table class="uk-table uk-table-striped">
                        <tbody>
                            <tr>
                                <td width="30%">Version</td>
                                <td><?php echo  $info['app']['version'] ; ?></td>
                            </tr>
                            <tr>
                                <td width="30%">Cache size</td>
                                <td><a id="clearcache" href="#clearcache" title="Click to clear cache" data-uk-tooltip><?php echo  $info['sizeCache']=='n/a' ? '0 KB':$info['sizeCache'] ; ?></a></td>
                            </tr>
                            <tr>
                                <td width="30%">Data size</td>
                                <td><a id="vacuumdata" href="#vacuumdata" title="Click to optimize data" data-uk-tooltip><?php echo  $info['sizeData']=='n/a' ? '0 KB':$info['sizeData'] ; ?></a></td>
                            </tr>
                    </table>

                    <script>

                        $("#clearcache, #vacuumdata").on("click", function(e){

                            e.preventDefault();

                            var progress = $('<i class="uk-icon-spinner uk-icon-spin"></i>'),
                                ele = $(this).hide().after(progress);

                            App.request('/settings/'+this.id, {}, function(data){
                                App.notify('Done.');

                                setTimeout(function(){
                                    ele.text(data.size=="n/a" ? '0 KB':data.size).show();
                                    progress.remove();
                                }, 500);
                            }, "json");

                        });
                    </script>

                    <strong>Mailer</strong>

                    <?php if($info["mailer"]) { ?>

                    <table class="uk-table uk-table-striped">
                        <tbody>
                            <?php foreach($info['mailer'] as $key => $value) { ?>
                            <tr>
                                <td width="30%"><?php echo  $key ; ?></td>
                                <td><?php echo  ($key=="password") ? str_pad("", strlen($value), '*') : $value ; ?></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>

                    <p>
                        <button id="btnTestEmail" class="uk-button uk-button-primary"><i class="uk-icon-envelope-o"></i> Send test email</button>
                    </p>

                    <script>

                        $("#btnTestEmail").on("click", function(){

                            var email = prompt("Send test email to:", '<?php echo  @$info['mailer']['from'] ; ?>');

                            if (email && email.match(/^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/)) {

                                App.request('/settings/test/email', {"email":email}, function(data){
                                    App.notify(data.status ? 'Email was sent. Please check your mailbox.': 'Sending email failed.', data.status ? 'info':'danger');
                                }, "json");

                            } else {
                                App.notify("Please provide a valid email adress", "danger");
                            }

                        });
                    </script>

                    <?php }else{ ?>

                    <div class="uk-alert">
                        No mailer settings found.
                    </div>

                    <?php } ?>


                    <strong>Directories</strong>

                    <table class="uk-table uk-table-striped">
                        <thead class="uk-text-small">
                            <tr>
                                <th>Path</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($info['folders'] as $folder=>$permission) { ?>
                            <tr>
                                <td class="uk-text-small" style="font-family:monospace;"><?php echo  $app->pathToUrl($folder) ; ?></td>
                                <td><div class="uk-badge uk-badge-<?php echo  $permission ? 'success':'danger' ; ?>">writable</div></td>
                            </tr>
                            <?php } ?>
                        </tbody>
                    </table>


                </div>

                <div>
                    <p>
                        <strong><span class="uk-badge app-badge">PHP</span></strong>
                    </p>
                    <table class="uk-table uk-table-striped">
                        <tbody>
                            <tr>
                                <td width="30%">Version</td>
                                <td><?php echo  $info['phpversion'] ; ?></td>
                            </tr>
                            <tr>
                                <td width="30%">PHP SAPI</td>
                                <td><?php echo  $info['sapi_name'] ; ?></td>
                            </tr>
                            <tr>
                                <td width="30%">System</td>
                                <td><?php echo  $info['system'] ; ?></td>
                            </tr>
                            <tr>
                                <td width="30%">Loaded Extensions</td>
                                <td><?php echo  implode(", ", $info['extensions']) ; ?></td>
                            </tr>
                            <tr>
                                <td width="30%">Memory limit</td>
                                <td><?php echo  ini_get("memory_limit") ; ?></td>
                            </tr>
                            <tr>
                                <td width="30%">Upload file size limit</td>
                                <td><?php echo  ini_get("upload_max_filesize") ; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>

            </div>

            <?php $app->trigger("cockpit.settings.infopage.main"); ?>
        </div>
    </div>

    <div class="uk-width-medium-1-4">
        <ul class="uk-nav uk-nav-side" data-uk-switcher="{connect:'#settings-info'}">
            <li><a href="#SYSTEM">System</a></li>
            <li><a href="#PHP">PHP</a></li>
        </ul>

        <?php $app->trigger("cockpit.settings.infopage.aside"); ?>
    </div>

</div>