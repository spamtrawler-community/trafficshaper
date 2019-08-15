<div id="ProjectHoneypot">
    <div class="row">
        <div class="small-4 columns">
            <input type="hidden" name="projecthoneypot_blacklist_filter[conf_class_name]"
                   value="{$params['projecthoneypot_blacklist_filter']['conf_class_name']}">
            <a href="#"
               class="handle button expand text-left {if $params['projecthoneypot_blacklist_filter']['status'] eq  '1'} success {/if} radius"
               onclick="$( '#ProjectHoneypotDetails' ).toggle( 'slow' );return false;" id="ProjectHoneyPotButtonExpand">
                <i class="fi-list dragicon"></i>
                ProjectHoneypot
            </a>
            <script>
                var ProjectHoneyPotToggle = false;
                $( "#ProjectHoneyPotButtonExpand" ).click(function() {
                    if(ProjectHoneyPotToggle === false){
                        ProjectHoneyPotToggle = true;
                        $("#gridProjectHoneypot").data("kendoGrid").dataSource.read();
                    } else {
                        ProjectHoneyPotToggle = false;
                    }
                });
            </script>
        </div>
        <div class="small-2 columns text-center">
            <select name="projecthoneypot_blacklist_filter[conf_status]" class="radius">
                <option value="1" {if $params['projecthoneypot_blacklist_filter']['status'] eq  '1'} selected {/if} >
                    Active
                </option>
                <option value="0" {if $params['projecthoneypot_blacklist_filter']['status'] eq  '0'} selected {/if}>
                    Inactive
                </option>
            </select>
        </div>
        <div class="small-2 columns text-center left">
            <select name="projecthoneypot_blacklist_filter[conf_params][allowcaptcha]" class="radius">
                <option value="1" {if $params['projecthoneypot_blacklist_filter']['allowcaptcha'] eq  '1'} selected {/if} >
                    Challenge
                </option>
                <option value="0" {if $params['projecthoneypot_blacklist_filter']['allowcaptcha'] eq  '0'} selected {/if}>
                    Don't Challenge
                </option>
            </select>
        </div>
        {if isset($StatsBlockedByFilter['Firewall_ProjectHoneypot_Controller_Filter']) }
        <div class="small-1 large-2 columns left has-tip" data-tooltip aria-haspopup="true"
             title="{($StatsBlockedByFilter['Firewall_ProjectHoneypot_Controller_Filter']/$StatsBlockedByFilter['all']*100)|string_format:"%.2f"}%">
            <div class="radius progress success small-12"><span class="meter"
                                                               style="width: {($StatsBlockedByFilter['Firewall_ProjectHoneypot_Controller_Filter']/$StatsBlockedByFilter['all']*100)|string_format:"%.2f"}%"></span>
            </div>
        </div>
        {/if}
        <!--
    <div class="small-1 columns text-center">
        <input type="text" class="radius" name="projecthoneypot_blacklist_filter[conf_order]" value="{$params['projecthoneypot_blacklist_filter']['order']|escape:'htmlall'}">
    </div>
    -->
    </div>

    <div id="ProjectHoneypotDetails" style="display: none;">
        <!--Grid-->
        {include file="file:{$path_modules}/Firewall/ProjectHoneypot/Templates/{$config->template}/Grid.tpl"}
        <!-- End Grid -->
        <div class="panel">
            <div class="row">
                <div class="small-12 columns">
                    <div class="row">
                        <div class="small-2 columns">
                            <label for="projecthoneypot_blacklist_filter[conf_params][block_reason]" class="right"><span
                                        title="Text shown for {literal}{BlockReason}{/literal} placeholder in exit message and in data grids">Block Reason</span></label>
                        </div>
                        <div class="small-6 columns left">
                            <input type="text" name="projecthoneypot_blacklist_filter[conf_params][block_reason]"
                                   value="{$params['projecthoneypot_blacklist_filter']['block_reason']|escape:'htmlall'}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-2 columns">
                            <label for="projecthoneypot_blacklist_filter[conf_params][api_key]" class="right"><span
                                        title="Your ProjectHoneypot API Key">API Key</span></label>
                        </div>
                        <div class="small-6 columns left">
                            <input type="text" name="projecthoneypot_blacklist_filter[conf_params][api_key]"
                                   value="{$params['projecthoneypot_blacklist_filter']['api_key']|escape:'htmlall'}">
                        </div>
                        <div class="small-3 columns left">
                            <a href="http://www.projecthoneypot.org/?rf=77963" target="_blank">Get API Key</a>
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-2 columns">
                            <label for="projecthoneypot_blacklist_filter[conf_params][last_activity]"
                                   class="right"><span title="Days since last activity">Last Activity</span></label>
                        </div>
                        <div class="small-6 columns left">
                            <input type="number" min="1" max="365"
                                   name="projecthoneypot_blacklist_filter[conf_params][last_activity]"
                                   value="{$params['projecthoneypot_blacklist_filter']['last_activity']|escape:'htmlall'}">
                        </div>
                    </div>
                    <div class="row">
                        <div class="small-2 columns">
                            <label for="projecthoneypot_blacklist_filter[conf_params][threat_score]" class="right"><span
                                        title="Threat Score">Threat Score</span></label>
                        </div>
                        <div class="small-6 columns left">
                            <input type="number" min="1" max="100"
                                   name="projecthoneypot_blacklist_filter[conf_params][threat_score]"
                                   value="{$params['projecthoneypot_blacklist_filter']['threat_score']|escape:'htmlall'}">
                        </div>
                        <div class="small-3 columns left">
                            <a href="https://www.projecthoneypot.org/threat_info.php" target="_blank">What is this ?</a>
                        </div>
                    </div>
                    <div class="row">
                        <fieldset class="small-11 center">
                            <legend>Blocked Visitor Types</legend>
                            <div class="small-10 columns">
                                <input id="checkbox0"
                                       name="projecthoneypot_blacklist_filter[conf_params][block_types][]"
                                       type="checkbox" value="0"
                                       {if '0'|in_array:$params['projecthoneypot_blacklist_filter']['block_types']}checked{/if}><label
                                        for="checkbox0">Search Engine</label><br/>
                                <input id="checkbox1"
                                       name="projecthoneypot_blacklist_filter[conf_params][block_types][]"
                                       type="checkbox" value="1"
                                       {if '1'|in_array:$params['projecthoneypot_blacklist_filter']['block_types']}checked{/if}><label
                                        for="checkbox1">Suspicious</label><br/>
                                <input id="checkbox2"
                                       name="projecthoneypot_blacklist_filter[conf_params][block_types][]"
                                       type="checkbox" value="2"
                                       {if '2'|in_array:$params['projecthoneypot_blacklist_filter']['block_types']}checked{/if}><label
                                        for="checkbox2">Harvester</label><br/>
                                <input id="checkbox3"
                                       name="projecthoneypot_blacklist_filter[conf_params][block_types][]"
                                       type="checkbox" value="3"
                                       {if '3'|in_array:$params['projecthoneypot_blacklist_filter']['block_types']}checked{/if}><label
                                        for="checkbox3">Suspicious & Harvester</label><br/>
                                <input id="checkbox4"
                                       name="projecthoneypot_blacklist_filter[conf_params][block_types][]"
                                       type="checkbox" value="4"
                                       {if '4'|in_array:$params['projecthoneypot_blacklist_filter']['block_types']}checked{/if}><label
                                        for="checkbox4">Comment Spammer</label><br/>
                                <input id="checkbox5"
                                       name="projecthoneypot_blacklist_filter[conf_params][block_types][]"
                                       type="checkbox" value="5"
                                       {if '5'|in_array:$params['projecthoneypot_blacklist_filter']['block_types']}checked{/if}><label
                                        for="checkbox5">Suspicious & Comment Spammer</label><br/>
                                <input id="checkbox6"
                                       name="projecthoneypot_blacklist_filter[conf_params][block_types][]"
                                       type="checkbox" value="6"
                                       {if '6'|in_array:$params['projecthoneypot_blacklist_filter']['block_types']}checked{/if}><label
                                        for="checkbox6">Harvester & Comment Spammer</label><br/>
                                <input id="checkbox7"
                                       name="projecthoneypot_blacklist_filter[conf_params][block_types][]"
                                       type="checkbox" value="7"
                                       {if '7'|in_array:$params['projecthoneypot_blacklist_filter']['block_types']}checked{/if}><label
                                        for="checkbox7">Suspicious & Harvester & Comment Spammer</label><br/>
                            </div>
                        </fieldset>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="small-1 columns left">
                            <span title="Exporting a Plugin allows you to re-import it in the future!">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/Core/Plugins/export?plugin=projecthoneypot_blacklist_filter"
                               class="tiny button radius"><i class="fi-download medium"></i></a>
                                </span>
                </div>
                <div class="small-4 columns right text-right">
                            <span title="Deletion of a Filter also removes all data related to this filter!">
                            <a href="#" class="tiny button alert radius"
                               onClick="removePlugin('projecthoneypot_blacklist_filter', 'ProjectHoneypotBlacklist'); return false;"><i
                                        class="fi-skull medium"></i></a>
                            </span>
                </div>
            </div>
        </div>
    </div>
</div>