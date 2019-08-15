<div id="SatelliteClient">
    <div class="row">
        <div class="small-4 columns">
            <input type="hidden" name="satellite_client_filter[conf_class_name]"
                   value="{$params['satellite_client_filter']['conf_class_name']}">
            <a href="#"
               class="handle button expand text-left {if $params['satellite_client_filter']['status'] eq  '1'} success {/if} radius"
               onclick="$( '#SatelliteClientDetails' ).toggle( 'slow' );return false;">
                <i class="fi-list dragicon"></i>
                Satellite Client
            </a>
        </div>
        <div class="small-2 columns text-center">
            <select name="satellite_client_filter[conf_status]" class="radius">
                <option value="1" {if $params['satellite_client_filter']['status'] eq  '1'} selected {/if} >Active
                </option>
                <option value="0" {if $params['satellite_client_filter']['status'] eq  '0'} selected {/if}>Inactive
                </option>
            </select>
        </div>
        <div class="small-2 columns text-center">
            <!--
        <select name="stopforumspam_blacklist_filter[conf_params][allowcaptcha]" class="radius">
            <option value="1" {if $params['satellite_client_filter']['allowcaptcha'] eq  '1'} selected {/if} >Challenge</option>
            <option value="0" {if $params['satellite_client_filter']['allowcaptcha'] eq  '0'} selected {/if}>Don't Challenge</option>
        </select>
-->
        </div>
        <div class="small-2 columns text-center left">
            <div id="SatelliteError">
                {if $SatelliteStatus eq  'offline'}Offline ({$SatelliteLastError}) -
                    <a onclick="resetSatellitelock();return false;">Reset</a>
                {literal}
                    <script>
                        function resetSatellitelock() {
                            $.get("{/literal}{$requestprotocol}://{$linkurl}{literal}Firewall/Satellite/Manage/resetSatelliteLock", function (data) {
                                $("#SatelliteError").hide();
                                alertify.alert(data);
                            });
                        }
                    </script>
                {/literal}
                {else}
                    <p> </p>
                {/if}
            </div>
        </div>
        {if isset($StatsBlockedByFilter['Firewall_Satellite_Controller_Filter']) }
        <div class="small-1 large-2 columns left has-tip" data-tooltip aria-haspopup="true"
             title="{($StatsBlockedByFilter['Firewall_Satellite_Controller_Filter']/$StatsBlockedByFilter['all']*100)|string_format:"%.2f"}%">
            <div class="radius progress success small-12"><span class="meter"
                                                               style="width: {($StatsBlockedByFilter['Firewall_Satellite_Controller_Filter']/$StatsBlockedByFilter['all']*100)|string_format:"%.2f"}%"></span>
            </div>
        </div>
        {/if}
        <!--
    <div class="small-1 columns text-center">
        <input type="text" class="radius" name="satellite_client_filter[conf_order]" value="{$params['satellite_client_filter']['order']|escape:'htmlall'}">
    </div>
    -->
    </div>

    <div id="SatelliteClientDetails" style="display: none;">
        <div class="panel">
            <div class="row">
                <div class="small-12 columns">
                    <div class="row">
                        <div class="small-2 columns">
                            <label for="satellite_client_filter[conf_params][satellite_url]" class="right"><span
                                        title="URL to Satellite Server">Server URL</span></label>
                        </div>
                        <div class="small-6 columns left">
                            <input type="text" name="satellite_client_filter[conf_params][satellite_url]"
                                   value="{$params['satellite_client_filter']['satellite_url']|escape:'htmlall'}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="small-12 columns">
                    <div class="row">
                        <div class="small-2 columns">
                            <label for="satellite_client_filter[conf_params][apikey]" class="right">
                                <span title="API key to access the particular Satellite">API Key</span></label>
                        </div>
                        <div class="small-6 columns left">
                            <input type="text" name="satellite_client_filter[conf_params][apikey]"
                                   value="{$params['satellite_client_filter']['apikey']|escape:'htmlall'}">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="small-12 columns">
                    <div class="row">
                        <div class="small-2 columns">
                            <label for="satellite_client_filter[conf_params][block_reason]" class="right"><span
                                        title="Text shown for {literal}{BlockReason}{/literal} placeholder in exit message and in data grids">Block Reason</span></label>
                        </div>
                        <div class="small-6 columns left">
                            <input type="text" name="satellite_client_filter[conf_params][block_reason]"
                                   value="{$params['satellite_client_filter']['block_reason']|escape:'htmlall'}">
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="small-1 columns left">
                            <span title="Exporting a Plugin allows you to re-import it in the future!">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/Core/Plugins/export?plugin=satellite_client_filter"
                               class="tiny button radius"><i class="fi-download medium"></i></a>
                                </span>
                </div>
                <div class="small-4 columns right text-right">
                            <span title="Deletion of a Filter also removes all data related to this filter!">
                            <a href="#" class="tiny button alert radius"
                               onClick="removePlugin('satellite_client_filter', 'Satellite'); return false;"><i
                                        class="fi-skull medium"></i></a>
                            </span>
                </div>
            </div>
        </div>
    </div>
</div>