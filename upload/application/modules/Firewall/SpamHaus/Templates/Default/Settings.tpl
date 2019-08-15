<div id="SpamHaus">
    <div class="row">
        <div class="small-4 columns">
            <input type="hidden" name="spamhaus_blacklist_filter[conf_class_name]"
                   value="{$params['spamhaus_blacklist_filter']['conf_class_name']}">
            <a href="#"
               class="handle button expand text-left {if $params['spamhaus_blacklist_filter']['status'] eq  '1'} success {/if} radius"
               onclick="$( '#SpamHausDetails' ).toggle( 'slow' );return false;" id="SpamHausButtonExpand">
                <i class="fi-list dragicon"></i>
                SpamHaus
            </a>
            <script>
                var SpamHausToggle = false;
                $( "#SpamHausButtonExpand" ).click(function() {
                    if(SpamHausToggle === false){
                        SpamHausToggle = true;
                        $("#gridSpamHaus").data("kendoGrid").dataSource.read();
                    } else {
                        SpamHausToggle = false;
                    }
                });
            </script>
        </div>
        <div class="small-2 columns text-center">
            <select name="spamhaus_blacklist_filter[conf_status]" class="radius">
                <option value="1" {if $params['spamhaus_blacklist_filter']['status'] eq  '1'} selected {/if} >Active
                </option>
                <option value="0" {if $params['spamhaus_blacklist_filter']['status'] eq  '0'} selected {/if}>Inactive
                </option>
            </select>
        </div>
        <div class="small-2 columns text-center left">
            <select name="spamhaus_blacklist_filter[conf_params][allowcaptcha]" class="radius">
                <option value="1" {if $params['spamhaus_blacklist_filter']['allowcaptcha'] eq  '1'} selected {/if} >
                    Challenge
                </option>
                <option value="0" {if $params['spamhaus_blacklist_filter']['allowcaptcha'] eq  '0'} selected {/if}>Don't
                    Challenge
                </option>
            </select>
        </div>
        {if isset($StatsBlockedByFilter['Firewall_SpamHaus_Controller_Filter']) }
        <div class="small-1 large-2 columns left has-tip" data-tooltip aria-haspopup="true"
             title="{($StatsBlockedByFilter['Firewall_SpamHaus_Controller_Filter']/$StatsBlockedByFilter['all']*100)|string_format:"%.2f"}%">
            <div class="radius progress success small-12"><span class="meter"
                                                               style="width: {($StatsBlockedByFilter['Firewall_SpamHaus_Controller_Filter']/$StatsBlockedByFilter['all']*100)|string_format:"%.2f"}%"></span>
            </div>
        </div>
        {/if}
        <!--
    <div class="small-1 columns text-center">
        <input type="text" class="radius" name="spamhaus_blacklist_filter[conf_order]" value="{$params['spamhaus_blacklist_filter']['order']|escape:'htmlall'}">
    </div>
    -->
    </div>

    <div id="SpamHausDetails" style="display: none;">
        <!--Grid-->
        {include file="file:{$path_modules}/Firewall/SpamHaus/Templates/{$config->template}/Grid.tpl"}
        <!-- End Grid -->
        <div class="panel">
            <div class="row">
                <div class="small-12 columns">
                    <div class="row">
                        <div class="small-2 columns">
                            <label for="spamhaus_blacklist_filter[conf_params][block_reason]" class="right"><span
                                        title="Text shown for {literal}{BlockReason}{/literal} placeholder in exit message and in data grids">Block Reason</span></label>
                        </div>
                        <div class="small-6 columns left">
                            <input type="text" name="spamhaus_blacklist_filter[conf_params][block_reason]"
                                   value="{$params['spamhaus_blacklist_filter']['block_reason']|escape:'htmlall'}">
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="small-1 columns left">
                            <span title="Exporting a Plugin allows you to re-import it in the future!">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/Core/Plugins/export?plugin=spamhaus_blacklist_filter"
                               class="tiny button radius"><i class="fi-download medium"></i></a>
                                </span>
                </div>
                <div class="small-4 columns right text-right">
                            <span title="Deletion of a Filter also removes all data related to this filter!">
                            <a href="#" class="tiny button alert radius"
                               onClick="removePlugin('spamhaus_blacklist_filter', 'SpamHaus'); return false;"><i
                                        class="fi-skull medium"></i></a>
                            </span>
                </div>
            </div>
        </div>
    </div>
</div>