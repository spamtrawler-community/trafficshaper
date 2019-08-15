<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" >
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Head.tpl"}
<body>
<script src="//{$ressourceurl}/js/tinymce/tinymce.min.js"></script>
<script src="//{$ressourceurl}/js/tinymceconf.js"></script>
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Header.tpl"}
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/MainNav.tpl"}

<div class="row">
    <form action="{$requestprotocol}://{$linkurl}Admin/Settings/Manage/save" id="frmSettings" method="POST">
    <div id="tabstrip">
        <ul>
            <li class="k-state-active">
                System
            </li>
        </ul>

        <!-- System Tab -->
        <div>
            <fieldset>
                <legend>System</legend>
                <!-- URL
                <div class="row">
                    <div class="small-12 columns">
                        <div class="row">
                            <div class="small-2 columns">
                                <label for="core[conf_params][url]" class="right">System URL</label>
                            </div>
                            <div class="small-9 columns left">
                                <input type="text" name="core[conf_params][url]" value="{$core['url']}">
                            </div>
                        </div>
                    </div>
                </div> -->

                <!-- System Time -->
                <div class="row">
                    <div class="small-12 columns">
                        <div class="row">
                            <div class="small-2 columns">
                                <label for="core[conf_params][url]" class="right">Timezone</label>
                            </div>
                            <div class="small-9 columns left">
                                <select name="core[conf_params][timezone]" class="radius">
                                    {foreach from=$timezones key=k item=v}
                                        <option value="{$v}" {if $core['timezone'] eq  $v} selected {/if} >{$v}</option>
                                    {/foreach}
                                 </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="small-12 columns">
                        <div class="row">
                            <div class="small-2 columns">
                                <label for="core[conf_params][admin_auth_method]" class="right">Admin Auth Method</label>
                            </div>
                            <div class="small-6 columns left">
                                <select id="selAdminAuthMethod" name="core[conf_params][admin_auth_method]" class="radius">
                                    <option value="UsernamePassword" {if $core['admin_auth_method'] eq  'UsernamePassword'} selected {/if} >Username & Password</option>
                                    <option value="DuoSecurity" {if $core['admin_auth_method'] eq  'DuoSecurity'} selected {/if}>Username + Password + DuoSecurity (Two Factor Authorization)</option>
                                    <option value="U2F" {if $core['admin_auth_method'] eq  'U2F'} selected {/if}>Username + Password + U2F USB key (Two Factor Authorization)</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div id="rowDuoSecParams" {if $core['admin_auth_method'] neq  'DuoSecurity'}style="display: none;"{/if}>
                    <!-- AKEY -->
                    <div class="row">
                        <div class="small-12 columns">
                            <div class="row">
                                <div class="small-2 columns">
                                    <label for="core[conf_params][duosec_akey]" class="right">AKEY</label>
                                </div>
                                <div class="small-9 columns left">
                                    <input type="text" name="core[conf_params][duosec_akey]" value="{$core['duosec_akey']}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- IKEY -->
                    <div class="row">
                        <div class="small-12 columns">
                            <div class="row">
                                <div class="small-2 columns">
                                    <label for="core[conf_params][duosec_ikey]" class="right">IKEY</label>
                                </div>
                                <div class="small-9 columns left">
                                    <input type="text" name="core[conf_params][duosec_ikey]" value="{$core['duosec_ikey']}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SKEY -->
                    <div class="row">
                        <div class="small-12 columns">
                            <div class="row">
                                <div class="small-2 columns">
                                    <label for="core[conf_params][duosec_skey]" class="right">SKEY</label>
                                </div>
                                <div class="small-9 columns left">
                                    <input type="text" name="core[conf_params][duosec_skey]" value="{$core['duosec_skey']}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- HOST -->
                    <div class="row">
                        <div class="small-12 columns">
                            <div class="row">
                                <div class="small-2 columns">
                                    <label for="core[conf_params][duosec_host]" class="right">Host</label>
                                </div>
                                <div class="small-9 columns left">
                                    <input type="text" name="core[conf_params][duosec_host]" value="{$core['duosec_host']}">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    var selAdminAuthMethod = $("#selAdminAuthMethod");
                    selAdminAuthMethod.on("change", function(e) {
                        if(selAdminAuthMethod.val() != 'DuoSecurity'){
                            $('#rowDuoSecParams').hide();
                        } else {
                            $('#rowDuoSecParams').show();
                        }
                    });
                </script>

                <div class="row">
                    <div class="small-12 columns">
                        <div class="row">
                            <div class="small-2 columns">
                                <label for="core[conf_params][sysmode]" class="right">System Mode</label>
                            </div>
                            <div class="small-6 columns left">
                                <select id="selSysmode" name="core[conf_params][sysmode]" class="radius">
                                    <option value="0" {if $core['sysmode'] eq  '0'} selected {/if} >Production</option>
                                    <option value="1" {if $core['sysmode'] eq  '1'} selected {/if}>Development</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="small-12 columns">
                        <div class="row">
                            <div class="small-2 columns">
                                <label for="core[conf_params][maintenance_status]" class="right">Maintenance Status</label>
                            </div>
                            <div class="small-6 columns left">
                                <select id="selMaintananceStatus" name="core[conf_params][maintenance_status]" class="radius">
                                    <option value="0" {if $core['maintenance_status'] eq  '0'} selected {/if} >Off</option>
                                    <option value="1" {if $core['maintenance_status'] eq  '1'} selected {/if}>On</option>
                                </select>
                            </div>
                            <div class="small-3 columns text-left">
                                {if $core['maintenance_status'] eq  '1'} <i class="fi-lightbulb icon-large icon-active"></i> {/if}
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    var selMaintananceStatus = $("#selMaintananceStatus");
                    selMaintananceStatus.on("change", function(e) {
                        if(selMaintananceStatus.val() != '1'){
                            $('#rowMaintenanceAction').hide();
                            $('#rowMaintenanceRedirectionTarget').hide();
                            $('#rowMaintenanceExitMessage').hide();
                        } else {
                            $('#rowMaintenanceAction').show();

                            var maintenanceAction = $('#selMaintenanceAction').val();
                            if(maintenanceAction == 'redirect'){
                                $('#rowMaintenanceRedirectionTarget').show();
                            } else if(maintenanceAction == 'exitmessage'){
                                $('#rowMaintenanceExitMessage').show();
                            }
                        }
                    });
                </script>

                <div id="rowMaintenanceAction" class="row" {if $core['maintenance_status'] neq '1'}style="display:none;"{/if}>
                    <div class="small-12 columns">
                        <div class="row">
                            <div class="small-2 columns">
                                <label for="core[conf_params][maintenance_action]" class="right">Maintenance Action</label>
                            </div>
                            <div class="small-6 columns left">
                                <select id="selMaintenanceAction" name="core[conf_params][maintenance_action]" class="radius">
                                    <option value="unavailable" {if $core['maintenance_action'] eq  'unavailable'} selected {/if} >Service unavailable Header (503)</option>
                                    <option value="redirect" {if  $core['maintenance_action'] eq  'redirect'} selected {/if}>Redirect</option>
                                    <option value="exitmessage" {if  $core['maintenance_action'] eq  'exitmessage'} selected {/if}>Show Exit Message</option>
                                </select>
                            </div>
                            <div class="small-3 columns text-left">
                                {if $core['maintenance_action'] eq  'exitmessage'} <i class="fi-clipboard-notes icon-large"></i> {/if}
                                {if $core['maintenance_action'] eq  'unavailable'} <i class="fi-tools icon-large"></i> {/if}
                                {if $core['maintenance_action'] eq  'redirect'} <i class="fi-web icon-large"></i> {/if}
                            </div>
                        </div>
                    </div>
                </div>

                <div id="rowMaintenanceRedirectionTarget" class="row" {if $core['maintenance_status'] neq '1' || $core['maintenance_action'] neq 'redirect'}style="display:none;"{/if}>
                    <div class="small-12 columns">
                        <div class="row">
                            <div class="small-2 columns">
                                <label for="core[conf_params][maintenance_redirection_target]" class="right">Redirection Target</label>
                            </div>
                            <div class="small-6 columns left">
                                <input type="text" name="core[conf_params][maintenance_redirection_target]" value="{$core['maintenance_redirection_target']|escape:'htmlall'}">
                            </div>
                        </div>
                    </div>
                </div>

                <div id="rowMaintenanceExitMessage" class="row" {if $core['maintenance_status'] neq '1' || $core['maintenance_action'] neq 'exitmessage'}style="display:none;"{/if}>
                    <div class="small-12 columns">
                        <div class="row">
                            <div class="small-2 columns">
                                <label for="core[conf_params][maintenance_exit_message]" class="right">Exit Message</label>
                            </div>
                            <div class="small-9 columns left">
                                <textarea name="core[conf_params][maintenance_exit_message]">{$core['maintenance_exit_message']}</textarea>
                            </div>
                        </div>
                    </div>
                </div>
                <script>
                    var selMaintenanceAction = $("#selMaintenanceAction");
                    selMaintenanceAction.on("change", function(e) {
                        if(selMaintenanceAction.val() != 'redirect'){
                            $('#rowMaintenanceRedirectionTarget').hide();
                        } else {
                            $('#rowMaintenanceRedirectionTarget').show();
                        }

                        if(selMaintenanceAction.val() != 'exitmessage'){
                            $('#rowMaintenanceExitMessage').hide();
                        } else {
                            $('#rowMaintenanceExitMessage').show();
                        }
                    });
                </script>
<br /><br />
            </fieldset>
            <div class="row text-right">
                <div class="small-11 columns right">
                    <input type="submit" class="button success radius" value="Save">
                </div>
            </div>
        </div>

    <script>
        $(document).ready(function() {
            $("#tabstrip").kendoTabStrip({
                animation:  {
                    open: {
                        effects: "fadeIn"
                    }
                }
            });
        });
    </script>
</div>
    </form>

    {literal}
        <script>
            // Attach a submit handler to the form
            $( "#frmSettings" ).submit(function( event ) {

                // Stop form from submitting normally
                event.preventDefault();

                // Get some values from elements on the page:
                var $form = $( this ),
                        url = $form.attr( "action" );

                //tinyMCE.triggerSave();

                // Send the data using post
                var posting = $.post( url, $( this ).serialize() );

                // Put the results in a div
                posting.done(function( data ) {
                    if( data !== 'ok'){
                        alertify.alert( data );
                    } else {
                        location.reload(true);
                    }
                });
            });
        </script>
    {/literal}

    {include file="file:{$path_modules}/Admin/Templates/{$core_template}/Footer.tpl"}
</body>
</html>
