<div id="AntiWebSpam">
    <div class="row">
        <div class="small-4 columns">
            <input type="hidden" name="antiwebspam_blacklist_filter[conf_class_name]"
                   value="{$params['antiwebspam_blacklist_filter']['conf_class_name']}">
            <a href="#"
               class="handle button expand text-left {if $params['antiwebspam_blacklist_filter']['status'] eq  '1'} success {/if} radius"
               onclick="$( '#AntiWebSpamDetails' ).toggle( 'slow' );return false;" id="AntiWebSpamButtonExpand">
                <i class="fi-list dragicon"></i>
                AntiWebSpam
            </a>
            <script>
                var AntiWebSpamToggle = false;
                $( "#AntiWebSpamButtonExpand" ).click(function() {
                    if(AntiWebSpamToggle === false){
                        AntiWebSpamToggle = true;
                        $("#gridAntiWebSpam").data("kendoGrid").dataSource.read();
                    } else {
                        AntiWebSpamToggle = false;
                    }
                });
            </script>
        </div>
        <div class="small-2 columns text-center">
            <select name="antiwebspam_blacklist_filter[conf_status]" class="radius">
                <option value="1" {if $params['antiwebspam_blacklist_filter']['status'] eq  '1'} selected {/if} >
                    Active
                </option>
                <option value="0" {if $params['antiwebspam_blacklist_filter']['status'] eq  '0'} selected {/if}>
                    Inactive
                </option>
            </select>
        </div>
        <div class="small-2 columns text-center left">
            <select name="antiwebspam_blacklist_filter[conf_params][allowcaptcha]" class="radius">
                <option value="1" {if $params['antiwebspam_blacklist_filter']['allowcaptcha'] eq  '1'} selected {/if} >
                    Challenge
                </option>
                <option value="0" {if $params['antiwebspam_blacklist_filter']['allowcaptcha'] eq  '0'} selected {/if}>
                    Don't Challenge
                </option>
            </select>
        </div>
        {if isset($StatsBlockedByFilter['Firewall_AntiWebSpam_Controller_Filter']) }
        <div class="small-1 large-2 columns left has-tip" data-tooltip aria-haspopup="true"
             title="{($StatsBlockedByFilter['Firewall_AntiWebSpam_Controller_Filter']/$StatsBlockedByFilter['all']*100)|string_format:"%.2f"}%">
            <div class="radius progress success small-12"><span class="meter"
                                                                style="width: {($StatsBlockedByFilter['Firewall_AntiWebSpam_Controller_Filter']/$StatsBlockedByFilter['all']*100)|string_format:"%.2f"}"></span>
            </div>
        </div>
        {/if}
        <!--
    <div class="small-1 columns text-center">
        <input type="text" class="radius" name="antiwebspam_blacklist_filter[conf_order]" value="{$params['antiwebspam_blacklist_filter']['order']|escape:'htmlall'}">
    </div>
    -->
    </div>

    <div id="AntiWebSpamDetails" style="display: none;">
        <!--Grid-->
        {include file="file:{$path_modules}/Firewall/AntiWebSpam/Templates/{$config->template}/Grid.tpl"}
        <!-- End Grid -->
        <div class="panel">
            <div class="row">
                        <div class="small-2 columns text-left">
                            <label for="antiwebspam_blacklist_filter[conf_params][block_reason]" class="right"><span
                                        data-tooltip aria-haspopup="true" class="has-tip"
                                        title="Text shown for {literal}{BlockReason}{/literal} placeholder in exit message and in data grids">Block Reason</span></label>
                        </div>
                        <div class="small-6 columns left text-left">
                            <input type="text" name="antiwebspam_blacklist_filter[conf_params][block_reason]"
                                   value="{$params['antiwebspam_blacklist_filter']['block_reason']|escape:'htmlall'}">
                        </div>
            </div>
            <div class="row">
                <fieldset class="center">
                    <legend>Filter Types</legend>
                    <div class="small-11 columns">
                        <input id="antiwebspamcheckbox0"
                               name="antiwebspam_blacklist_filter[conf_params][filter_types][]" type="checkbox"
                               value="hostnames"
                               {if 'hostnames'|in_array:$params['antiwebspam_blacklist_filter']['filter_types']}checked{/if}><label
                                for="antiwebspamcheckbox0">Hostnames</label><br/>
                        <input id="antiwebspamcheckbox1"
                               name="antiwebspam_blacklist_filter[conf_params][filter_types][]" type="checkbox"
                               value="ips"
                               {if 'ips'|in_array:$params['antiwebspam_blacklist_filter']['filter_types']}checked{/if}><label
                                for="antiwebspamcheckbox1">IP Addresses</label><br/>
                        <input id="antiwebspamcheckbox2"
                               name="antiwebspam_blacklist_filter[conf_params][filter_types][]" type="checkbox"
                               value="emails"
                               {if 'emails'|in_array:$params['antiwebspam_blacklist_filter']['filter_types']}checked{/if}><label
                                for="antiwebspamcheckbox2">Email Addresses</label><br/>
                    </div>
                </fieldset>
            </div>
            <hr/>
            <div class="row">
                <div class="small-1 columns left">
                            <span data-tooltip aria-haspopup="true" class="has-tip"
                                  title="Exporting a Plugin allows you to re-import it in the future!">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/Core/Plugins/export?plugin=antiwebspam_blacklist_filter"
                               class="tiny button radius"><i class="fi-download medium"></i></a>
                                </span>
                </div>
                <div class="small-4 columns right text-right">
                            <span data-tooltip aria-haspopup="true" class="has-tip"
                                  title="Deletion of a Filter also removes all data related to this filter!">
                            <a href="#" class="tiny button alert radius"
                               onClick="removePlugin('url_whitelist_filter', 'AntiWebSpam'); return false;"><i
                                        class="fi-skull medium"></i></a>
                            </span>
                </div>
            </div>
        </div>
    </div>
</div>