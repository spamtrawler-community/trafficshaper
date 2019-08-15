<div id="StopForumSpam">
    <div class="row">
        <div class="small-4 columns">
            <input type="hidden" name="stopforumspam_blacklist_filter[conf_class_name]"
                   value="{$params['stopforumspam_blacklist_filter']['conf_class_name']}">
            <a href="#"
               class="handle button expand text-left {if $params['stopforumspam_blacklist_filter']['status'] eq  '1'} success {/if} radius"
               onclick="$( '#StopForumSpamDetails' ).toggle( 'slow' );return false;" id="StopForumSpamButtonExpand">
                <i class="fi-list dragicon"></i>
                StopForumSpam
            </a>
            <script>
                var StopForumSpamToggle = false;
                $( "#StopForumSpamButtonExpand" ).click(function() {
                    if(StopForumSpamToggle === false){
                        StopForumSpamToggle = true;
                        $("#gridStopForumSpam").data("kendoGrid").dataSource.read();
                    } else {
                        StopForumSpamToggle = false;
                    }
                });
            </script>
        </div>
        <div class="small-2 columns text-center">
            <select name="stopforumspam_blacklist_filter[conf_status]" class="radius">
                <option value="1" {if $params['stopforumspam_blacklist_filter']['status'] eq  '1'} selected {/if} >
                    Active
                </option>
                <option value="0" {if $params['stopforumspam_blacklist_filter']['status'] eq  '0'} selected {/if}>
                    Inactive
                </option>
            </select>
        </div>
        <div class="small-2 columns text-center left">
            <select name="stopforumspam_blacklist_filter[conf_params][allowcaptcha]" class="radius">
                <option value="1" {if $params['stopforumspam_blacklist_filter']['allowcaptcha'] eq  '1'} selected {/if} >
                    Challenge
                </option>
                <option value="0" {if $params['stopforumspam_blacklist_filter']['allowcaptcha'] eq  '0'} selected {/if}>
                    Don't Challenge
                </option>
            </select>
        </div>
        {if isset($StatsBlockedByFilter['Firewall_StopForumSpam_Controller_Filter']) }
        <div class="small-1 large-2 columns left has-tip" data-tooltip aria-haspopup="true"
             title="{($StatsBlockedByFilter['Firewall_StopForumSpam_Controller_Filter']/$StatsBlockedByFilter['all']*100)|string_format:"%.2f"}%">
            <div class="radius progress success small-12"><span class="meter"
                                                               style="width: {($StatsBlockedByFilter['Firewall_StopForumSpam_Controller_Filter']/$StatsBlockedByFilter['all']*100)|string_format:"%.2f"}%"></span>
            </div>
        </div>
        {/if}
        <!--
    <div class="small-1 columns text-center">
        <input type="text" class="radius" name="stopforumspam_blacklist_filter[conf_order]" value="{$params['stopforumspam_blacklist_filter']['order']|escape:'htmlall'}">
    </div>
    -->
    </div>

    <div id="StopForumSpamDetails" style="display: none;">
        <!--Grid-->
        {include file="file:{$path_modules}/Firewall/StopForumSpam/Templates/{$config->template}/Grid.tpl"}
        <!-- End Grid -->
        <div class="panel">
            <div class="row">
                <div class="small-12 columns">
                    <div class="row">
                        <div class="small-2 columns">
                            <label for="stopforumspam_blacklist_filter[conf_params][block_reason]" class="right"><span
                                        title="Text shown for {literal}{BlockReason}{/literal} placeholder in exit message and in data grids">Block Reason</span></label>
                        </div>
                        <div class="small-6 columns left">
                            <input type="text" name="stopforumspam_blacklist_filter[conf_params][block_reason]"
                                   value="{$params['stopforumspam_blacklist_filter']['block_reason']|escape:'htmlall'}">
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="small-1 columns left">
                            <span title="Exporting a Plugin allows you to re-import it in the future!">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/Core/Plugins/export?plugin=stopforumspam_blacklist_filter"
                               class="tiny button radius"><i class="fi-download medium"></i></a>
                                </span>
                </div>
                <div class="small-4 columns right text-right">
                            <span title="Deletion of a Filter also removes all data related to this filter!">
                            <a href="#" class="tiny button alert radius"
                               onClick="removePlugin('stopforumspam_blacklist_filter', 'StopForumSpam'); return false;"><i
                                        class="fi-skull medium"></i></a>
                            </span>
                </div>
            </div>
        </div>
    </div>
</div>