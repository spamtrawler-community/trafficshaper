<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" >
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Head.tpl"}
<script src="//{$ressourceurl}/js/tinymce/tinymce.min.js"></script>
<script src="//{$ressourceurl}/js/tinymceconf.js"></script>
<body>
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Header.tpl"}
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/MainNav.tpl"}

{if isset($errors)}
    <div class="row">
        {literal}<script>$(function() { $('#divError').delay(5000).fadeOut('slow') });</script>{/literal}
                <div class="small-12 columns" id="divError">
                    {foreach from=$errors item=message}
                        <div data-alert="" class="alert-box alert">
                            {$message}
                            <a href="" class="close">×</a>
                        </div>
                    {/foreach}

                </div>
    </div>
{/if}

<div class="row">
    <div id="tabstrip" data-options="deep_linking:true">
        <ul>
            <li {if $tab eq 'firewall'}class="k-state-active"{/if}>
                {$language['Firewall']['Settings']}
            </li>
            <li {if $tab eq 'automation'}class="k-state-active"{/if}>
                {$language['Firewall']['Automation_Integration']}
            </li>
            <li {if $tab eq 'plugins'}class="k-state-active"{/if}>
                {$language['Firewall']['Plugins']}
            </li>
        </ul>

        <!-- Firewall Tab -->
        <div>
            <form action="{$requestprotocol}://{$linkurl}Firewall/Core/Manage/save" id="FirewallSettings" method="POST">
                <!-- Core -->
                <fieldset>
                    <legend>{$language['Firewall']['Core']}</legend>
                    <ul class="no-bullet">
                    {foreach from=$modules_firewall_core key=k item=v}
                    <li>
                        {include file="{$v}"}
                    </li>
                    {/foreach}
                    </ul>
                    <div class="row text-right">
                        <div class="small-11 columns right">
                            <input type="submit" class="button success radius" value="{$language['Firewall']['Save']}">
                        </div>
                    </div>
                </fieldset>

                <!-- Whitelists -->
                <fieldset>
                    <legend>{$language['Firewall']['Whitelists']}</legend>
                    <ul class="no-bullet sortable">
                    {foreach from=$modules_firewall_whitelists key=k item=v}
                        <li>
                        {include file="{$v}"}
                        </li>
                    {/foreach}
                    </ul>
                    <div class="row text-right">
                        <div class="small-11 columns right">
                            <input type="submit" class="button success radius" value="{$language['Firewall']['Save']}">
                        </div>
                    </div>
                </fieldset>

                <!-- Content Filter -->
                <fieldset>
                    <legend>Content {$language['Firewall']['Blacklists']}</legend>
                    <div class="row">
                        <div class="small-4 columns">
                            <span data-tooltip aria-haspopup="true" class="has-tip" title='{$language['Firewall']['Tooltip_Filter']}'>{$language['Firewall']['Filter']}</span>
                        </div>
                        <div class="small-2 columns">
                            <span data-tooltip aria-haspopup="true" class="has-tip" title='{$language['Firewall']['Tooltip_Status']}'>{$language['Firewall']['Status']}</span>
                        </div>
                        <div class="small-2 columns">
                            <span data-tooltip aria-haspopup="true" class="has-tip" title='{$language['Firewall']['Tooltip_Options']}'>{$language['Firewall']['Options']}</span>
                        </div>
                        <div class="small-1 large-2 columns left">
                            <span data-tooltip aria-haspopup="true" class="has-tip" title='{$language['Firewall']['Tooltip_Performance']}'>{$language['Firewall']['Performance']}</span>
                        </div>
                    </div>
                    <hr />
                    <ul class="no-bullet sortable">
                        {foreach from=$modules_firewall_contentblacklists key=k item=v}
                            <li>
                                {include file="{$v}"}
                            </li>
                        {/foreach}
                    </ul>
                    <div class="row text-right">
                        <div class="small-11 columns right">
                            <input type="submit" class="button success radius" value="{$language['Firewall']['Save']}">
                        </div>
                    </div>
                </fieldset>

                <!-- Blacklists -->
                <fieldset>
                    <legend>{$language['Firewall']['Blacklists']}</legend>
                    <div class="row">
                        <div class="small-4 columns">
                            <span data-tooltip aria-haspopup="true" class="has-tip" title='{$language['Firewall']['Tooltip_Filter']}'>{$language['Firewall']['Filter']}</span>
                        </div>
                        <div class="small-2 columns">
                            <span data-tooltip aria-haspopup="true" class="has-tip" title='{$language['Firewall']['Tooltip_Status']}'>{$language['Firewall']['Status']}</span>
                        </div>
                        <div class="small-2 columns">
                            <span data-tooltip aria-haspopup="true" class="has-tip" title='{$language['Firewall']['Tooltip_Options']}'>{$language['Firewall']['Options']}</span>
                        </div>
                        <div class="small-1 large-2 columns left">
                            <span data-tooltip aria-haspopup="true" class="has-tip" title='{$language['Firewall']['Tooltip_Performance']}'>{$language['Firewall']['Performance']}</span>
                        </div>
                    </div>
                    <hr />
                    <ul class="no-bullet sortable">
                    {foreach from=$modules_firewall_blacklists key=k item=v}
                        <li>
                        {include file="{$v}"}
                        </li>
                    {/foreach}
                        </ul>
                    <div class="row text-right">
                        <div class="small-11 columns right">
                            <input type="submit" class="button success radius" value="{$language['Firewall']['Save']}">
                        </div>
                    </div>
                </fieldset>

                <!-- Remote Services -->
                <fieldset>
                    <legend>{$language['Firewall']['Remote_Services']}</legend>
                    <div class="row">
                        <div class="small-4 columns">
                            <span data-tooltip aria-haspopup="true" class="has-tip" title='{$language['Firewall']['Tooltip_Filter']}'>{$language['Firewall']['Filter']}</span>
                        </div>
                        <div class="small-2 columns">
                            <span data-tooltip aria-haspopup="true" class="has-tip" title='{$language['Firewall']['Tooltip_Status']}'>{$language['Firewall']['Status']}</span>
                        </div>
                        <div class="small-2 columns">
                            <span data-tooltip aria-haspopup="true" class="has-tip" title='{$language['Firewall']['Tooltip_Options']}'>{$language['Firewall']['Options']}</span>
                        </div>
                        <div class="small-1 large-2 columns left">
                            <span data-tooltip aria-haspopup="true" class="has-tip" title='{$language['Firewall']['Tooltip_Performance']}'>{$language['Firewall']['Performance']}</span>
                        </div>
                    </div>
                    <hr />
                    <ul class="no-bullet sortable">
                    {foreach from=$modules_firewall_remote key=k item=v}
                    <li>
                        {include file="{$v}"}
                    </li>
                    {/foreach}
                    </ul>
                    <div class="row text-right">
                        <div class="small-11 columns right">
                            <input type="submit" class="button success radius" value="{$language['Firewall']['Save']}">
                        </div>
                    </div>
                </fieldset>
                <!-- End Include Module Settings -->
            </form>
    </div>


    <!-- Automation Tab -->
	<div>
        <div id="divApiURL" {if $params['firewall_core']['mode'] neq  'server'}style="display:none;"{/if}>
        <fieldset>
            <legend>{$language['Firewall']['Api_URLS']}</legend>
            <div class="row">
                <div class="small-12 columns">

                    <!-- Firewall -->
                    <div class="row">
                        <div class="small-2 columns">
                            <label for="firewall_core[conf_params][firewall_url]" class="right">{$language['Firewall']['Firewall_HTTP']}</label>
                        </div>
                        <div class="small-6 columns left">
                            <input type="url" name="firewall_core[conf_params][firewall_url]" value="http://{{$linkurl}|escape:'htmlall'}Firewall/Core/Filter">
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-2 columns">
                            <label for="firewall_core[conf_params][firewall_url]" class="right">{$language['Firewall']['Firewall_HTTPS']}</label>
                        </div>
                        <div class="small-6 columns left">
                            <input type="url" name="firewall_core[conf_params][firewall_url]" value="https://{{$linkurl}|escape:'htmlall'}Firewall/Core/Filter">
                        </div>
                    </div>
                    <!-- /Firewall -->

                </div>
            </div>
        </fieldset>
        </div>

        <div id="divIncludeStatements" {if $params['firewall_core']['mode'] eq  'server'}style="display:none;"{/if}>
            <fieldset>
                <legend>{$language['Firewall']['Include_Statements']}</legend>
                <div class="row">
                    <div class="small-12 columns">

                        <!-- Firewall -->
                        <div class="row">
                            <div class="small-2 columns">
                                <label for="firewall_core[conf_params][firewall_include_path]" class="right">Firewall</label>
                            </div>
                            <div class="small-6 columns left">
                                <input type="url" name="firewall_core[conf_params][firewall_include_path]" value="include('{{$smarty.const.TRAWLER_PATH_ROOT}|escape:'htmlall'}/firewall.php');">
                            </div>
                        </div>
                        <!-- /Firewall -->

                    </div>
                </div>
            </fieldset>
        </div>
    </div>

        <!-- Plugins Tab -->
        <div>
            <div id="divPlugins">
                <fieldset>
                    <legend>{$language['Firewall']['Install']}</legend>
                    {if isset($smarty.get.error)}
	                    <div id="pluginError" data-alert class="alert-box alert radius">
							  {$smarty.get.error|escape:'html'}
							  <a href="#" class="close">x</a>
						</div>
                        <!--
						<script>
							$(document).ready(function() {
								$("#pluginError").fadeTo(15000,1).fadeOut(1000);
							});
						</script>
						-->
					{/if}
                    <form action="{$requestprotocol}://{$linkurl}Firewall/Core/Plugins/install" id="PluginUpload" method="POST" enctype='multipart/form-data'>
                        <div class="row">
                            <div class="small-12 columns">

                                <!-- Upload -->
                                <div class="row">
                                    <div class="small-2 columns">
                                        <label for="firewall_plugin_file" class="right">{$language['Firewall']['Upload']}</label>
                                    </div>
                                    <div class="small-6 columns left">
                                        <input type="file" name="firewall_plugin_file">
                                    </div>
                                </div>
                                <!-- /Firewall -->

                            </div>
                        </div>
                        <div class="row text-right">
                            <div class="small-11 columns right">
                                <input type="submit" class="button success radius" value="{$language['Firewall']['Upload']}">
                            </div>
                        </div>
                    </form>
                </fieldset>
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
</div>
{literal}
    <script>
        // Attach a submit handler to the form
        $( "#FirewallSettings" ).submit(function( event ) {

            // Stop form from submitting normally
            event.preventDefault();

            // Get some values from elements on the page:
            var $form = $( this ),
                    url = $form.attr( "action" );

            tinyMCE.triggerSave();

            // Send the data using post
            var posting = $.post( url, $( this ).serialize() );

            // Put the results in a div
            posting.done(function( data ) {
                if( data !== 'ok'){
                    alertify.alert( data );
                } else {
                    location.reload(true);
                    //alert( 'Settings Updated!' );
                }
            });
        });
    </script>
{/literal}

<!-- Make modules sortable -->
<script type="text/javascript" src="//{$ressourceurl}/js/jquery-ui.min.js"></script>
<script type="text/javascript" src="//{$ressourceurl}/js/jquery.ui.touch-punch.min.js"></script>
{literal}
<script>
    $(function() {
        $( ".sortable" ).sortable({handle: '.handle'});
    });
</script>
{/literal}
<!-- Remove Plugin -->
<script>
function removePlugin(id, div){
    var PluginDiv = div;
    var request = $.ajax({
    url: "{$requestprotocol}://{$linkurl}Firewall/Core/Plugins/remove",
    method: "POST",
    data: { id : id },
    dataType: "html"
    });

    request.done(function( msg ) {
        $('#' + PluginDiv).remove();
        alert(msg);
    });

    request.fail(function( jqXHR, textStatus ) {
    alert( "Request failed: " + textStatus );
    });
}
</script>
<!-- /Remove Plugin -->
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Footer.tpl"}
</body>
</html>