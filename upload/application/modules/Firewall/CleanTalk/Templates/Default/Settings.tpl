<div id="CleanTalk">
<div class="row">
    <div class="small-4 columns">
        <input type="hidden" name="cleantalk_blacklist_filter[conf_class_name]"
               value="{$params['cleantalk_blacklist_filter']['conf_class_name']}">
        <a href="#"
           class="handle button expand text-left {if $params['cleantalk_blacklist_filter']['status'] eq  '1'} success {/if} radius"
           onclick="$( '#CleanTalkDetails' ).toggle( 'slow' );return false;" id="CleantalkButtonExpand">
            <i class="fi-list dragicon"></i>
            CleanTalk
        </a>
        <script>
            var CleantalkToggle = false;
            $( "#CleantalkButtonExpand" ).click(function() {
                if(CleantalkToggle === false){
                    CleantalkToggle = true;
                    $("#gridCleanTalk").data("kendoGrid").dataSource.read();
                } else {
                    CleantalkToggle = false;
                }
            });
        </script>
    </div>
    <div class="small-2 columns text-center">
        <select name="cleantalk_blacklist_filter[conf_status]" class="radius">
            <option value="1" {if $params['cleantalk_blacklist_filter']['status'] eq  '1'} selected {/if} >Active
            </option>
            <option value="0" {if $params['cleantalk_blacklist_filter']['status'] eq  '0'} selected {/if}>Inactive
            </option>
        </select>
    </div>
    <div class="small-2 columns text-center left">
        <select name="cleantalk_blacklist_filter[conf_params][allowcaptcha]" class="radius">
            <option value="1" {if $params['cleantalk_blacklist_filter']['allowcaptcha'] eq  '1'} selected {/if} >
                Challenge
            </option>
            <option value="0" {if $params['cleantalk_blacklist_filter']['allowcaptcha'] eq  '0'} selected {/if}>Don't
                Challenge
            </option>
        </select>
    </div>
    {if isset($StatsBlockedByFilter['Firewall_CleanTalk_Controller_Filter']) }
    <div class="small-1 large-2 columns left has-tip" data-tooltip aria-haspopup="true"
         title="{($StatsBlockedByFilter['Firewall_CleanTalk_Controller_Filter']/$StatsBlockedByFilter['all']*100)|string_format:"%.2f"}%">
        <div class="radius progress success small-12"><span class="meter"
                                                           style="width: {($StatsBlockedByFilter['Firewall_CleanTalk_Controller_Filter']/$StatsBlockedByFilter['all']*100)|string_format:"%.2f"}%"></span>
        </div>
    </div>
    {/if}
    <!--
    <div class="small-1 columns text-center">
        <input type="text" class="radius" name="cleantalk_blacklist_filter[conf_order]" value="{$params['cleantalk_blacklist_filter']['order']|escape:'htmlall'}">
    </div>
    -->
</div>

<div id="CleanTalkDetails" style="display: none;">
    <!--Grid-->
    {include file="file:{$path_modules}/Firewall/CleanTalk/Templates/{$config->template}/Grid.tpl"}
    <!-- End Grid -->
    <div class="panel">
        <div class="row">
            <div class="small-12 columns">
                <div class="row">
                    <div class="small-2 columns">
                        <label for="cleantalk_blacklist_filter[conf_params][block_reason]" class="right"><span
                                    data-tooltip aria-haspopup="true" class="has-tip"
                                    title="Text shown for {literal}{BlockReason}{/literal} placeholder in exit message and in data grids">Block Reason</span></label>
                    </div>
                    <div class="small-6 columns left">
                        <input type="text" name="cleantalk_blacklist_filter[conf_params][block_reason]"
                               value="{$params['cleantalk_blacklist_filter']['block_reason']|escape:'htmlall'}">
                    </div>
                </div>
                <div class="row">
                    <div class="small-2 columns">
                        <label for="cleantalk_blacklist_filter[conf_params][api_key]" class="right"><span data-tooltip
                                                                                                          aria-haspopup="true"
                                                                                                          class="has-tip"
                                                                                                          title="Your CleanTalk API Key">API Key</span></label>
                    </div>
                    <div class="small-6 columns left">
                        <input type="text" name="cleantalk_blacklist_filter[conf_params][api_key]"
                               value="{$params['cleantalk_blacklist_filter']['api_key']|escape:'htmlall'}">
                    </div>
                    <div class="small-3 columns left">
                        <a href="https://cleantalk.org/register?platform=spamtrawler" target="_blank">Get API Key</a>
                    </div>
                </div>
            </div>
        </div>
        <hr/>
        <div class="row">
            <div class="small-1 columns left">
                            <span data-tooltip aria-haspopup="true" class="has-tip"
                                  title="Exporting a Plugin allows you to re-import it in the future!">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/Core/Plugins/export?plugin=cleantalk_blacklist_filter"
                               class="tiny button radius"><i class="fi-download medium"></i></a>
                                </span>
            </div>
            <div class="small-4 columns right text-right">
                            <span data-tooltip aria-haspopup="true" class="has-tip"
                                  title="Deletion of a Filter also removes all data related to this filter!">
                            <a href="#" class="tiny button alert radius"
                               onClick="removePlugin('cleantalk_blacklist_filter', 'CleanTalk'); return false;"><i
                                        class="fi-skull medium"></i></a>
                            </span>
            </div>
        </div>
    </div>
</div>
</div>