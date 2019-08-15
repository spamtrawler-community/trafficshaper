<div id="TestBlacklist">
    <div class="row">
        <div class="small-4 columns">
            <input type="hidden" name="test_blacklist_filter[conf_class_name]"
                   value="{$params['test_blacklist_filter']['conf_class_name']}">
            <a href="#"
               class="handle button expand text-left {if $params['test_blacklist_filter']['status'] eq  '1'} success {/if} radius"
               onclick="$( '#TestBlacklistDetails' ).toggle( 'slow' );return false;">
                <i class="fi-list dragicon"></i>
                Tests
            </a>
        </div>
        <div class="small-2 columns text-center">
            <select name="test_blacklist_filter[conf_status]" class="radius">
                <option value="1" {if $params['test_blacklist_filter']['status'] eq  '1'} selected {/if} >Active
                </option>
                <option value="0" {if $params['test_blacklist_filter']['status'] eq  '0'} selected {/if}>Inactive
                </option>
            </select>
        </div>
        <div class="small-2 columns text-center">
            <select name="test_blacklist_filter[conf_params][allowcaptcha]" class="radius">
                <option value="1" {if $params['test_blacklist_filter']['allowcaptcha'] eq  '1'} selected {/if} >
                    Challenge
                </option>
                <option value="0" {if $params['test_blacklist_filter']['allowcaptcha'] eq  '0'} selected {/if}>Don't
                    Challenge
                </option>
            </select>
        </div>
        <div class="small-1 large-2 columns left" title="{($StatsBlockedByFilter['Firewall_TestBlacklist_Controller_Filter']/$StatsBlockedByFilter['all']*100)|string_format:"%.2f"}%">
            <div class="radius progress success small-12"><span class="meter"
                                                                style="width: {($StatsBlockedByFilter['Firewall_TestBlacklist_Controller_Filter']/$StatsBlockedByFilter['all']*100)|string_format:"%.2f"}"></span>
            </div>
        </div>
        <!--
    <div class="small-1 columns text-center">
        <input type="text" name="test_blacklist_filter[conf_order]" value="{$params['test_blacklist_filter']['order']|escape:'htmlall'}">
    </div>
    -->
    </div>

    <div id="TestBlacklistDetails" style="display: none;">
        <div class="panel">
            <div class="row">
                <div class="small-12 columns">
                    <div class="row">
                        <div class="small-2 columns">
                            <label for="test_blacklist_filter[conf_params][block_reason]" class="right"><span
                                        title="Text shown for {literal}{BlockReason}{/literal} placeholder in exit message and in data grids">Block Reason</span></label>
                        </div>
                        <div class="small-6 columns left">
                            <input type="text" name="test_blacklist_filter[conf_params][block_reason]"
                                   value="{$params['test_blacklist_filter']['block_reason']|escape:'htmlall'}">
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="small-12 columns">
                    <div class="row">
                        <div class="small-2 columns">
                            <label class="right"><span title="Exporting a Plugin allows you to re-import it in the future!">Export Filter</span></label>
                        </div>
                        <div class="small-6 columns left">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/Core/Plugins/export?plugin=test_blacklist_filter"
                               class="tiny button success radius">Export</a>
                        </div>
                    </div>
                </div>
            </div>
            <hr/>
            <div class="row">
                <div class="small-12 columns">
                    <div class="row">
                        <div class="small-2 columns">
                            <label class="right"><span title="Deletion of a Filter also removes all data related to this filter!">Delete Filter</span></label>
                        </div>
                        <div class="small-6 columns left">
                            <a href="#" class="tiny button alert radius"
                               onClick="removePlugin('test_blacklist_filter', 'TestBlacklist'); return false;">X</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>