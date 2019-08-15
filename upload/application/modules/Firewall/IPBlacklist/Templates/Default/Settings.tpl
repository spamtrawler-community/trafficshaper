<div id="IPBlacklist">
<div class="row">
    <div class="small-4 columns">
        <input type="hidden" name="ip_blacklist_filter[conf_class_name]"
               value="{$params['ip_blacklist_filter']['conf_class_name']}">
        <a href="#" class="handle button expand text-left {if $params['ip_blacklist_filter']['status'] eq  '1'} success {/if} radius"
           onclick="$( '#IPBlacklistDetails' ).toggle( 'slow' );return false;" id="IPblacklistButtonExpand">
            <i class="fi-list dragicon"></i>
            IPs
        </a>
        <script>
            var IPBlacklistToggle = false;
            $( "#IPblacklistButtonExpand" ).click(function() {
                if(IPBlacklistToggle === false){
                    IPBlacklistToggle = true;
                    $("#gridIPBlacklist").data("kendoGrid").dataSource.read();
                } else {
                    IPBlacklistToggle = false;
                }
            });
        </script>
    </div>
    <div class="small-2 columns text-center">
        <select name="ip_blacklist_filter[conf_status]" class="radius">
            <option value="1" {if $params['ip_blacklist_filter']['status'] eq  '1'} selected {/if} >Active</option>
            <option value="0" {if $params['ip_blacklist_filter']['status'] eq  '0'} selected {/if}>Inactive</option>
        </select>
    </div>
    <div class="small-2 columns text-center left">
        <select name="ip_blacklist_filter[conf_params][allowcaptcha]" class="radius">
            <option value="1" {if $params['ip_blacklist_filter']['allowcaptcha'] eq  '1'} selected {/if} >Challenge
            </option>
            <option value="0" {if $params['ip_blacklist_filter']['allowcaptcha'] eq  '0'} selected {/if}>Don't
                Challenge
            </option>
        </select>
    </div>
    {if isset($StatsBlockedByFilter['Firewall_IPBlacklist_Controller_Filter']) }
    <div class="small-1 large-2 columns left has-tip" data-tooltip aria-haspopup="true"
         title="{($StatsBlockedByFilter['Firewall_IPBlacklist_Controller_Filter']/$StatsBlockedByFilter['all']*100)|string_format:"%.2f"}%">
        <!--     <div style="background-color: '#FFFF00'; width: '{$StatsBlockedByFilter['Firewall_IPBlacklist_Controller_Filter']}%'; height: '20px'"></div>     -->
        <div class="radius progress success small-12"><span class="meter"
                                                           style="width: {($StatsBlockedByFilter['Firewall_IPBlacklist_Controller_Filter']/$StatsBlockedByFilter['all']*100)|string_format:"%.2f"}%"></span>
        </div>
    </div>
    {/if}
</div>

<div id="IPBlacklistDetails" style="display: none;">
    <!--Grid-->
    {include file="file:{$path_modules}/Firewall/IPBlacklist/Templates/{$config->template}/Grid.tpl"}
    <!-- End Grid -->
    <div class="panel">
        <div class="row">
            <div class="small-2 columns">
                <label for="ip_blacklist_filter[conf_params][block_reason]" class="right"><span
                            title="Text shown for {literal}{BlockReason}{/literal} placeholder in exit message and in data grids">Block Reason</span></label>
            </div>
            <div class="small-6 columns left">
                <input type="text" name="ip_blacklist_filter[conf_params][block_reason]"
                       value="{$params['ip_blacklist_filter']['block_reason']|escape:'htmlall'}">
            </div>
        </div>
        <hr/>
        <div class="row">
            <!--Export-->
            <div class="small-1 columns left">
                            <span data-tooltip aria-haspopup="true" class="has-tip"
                                  title="Exporting a Filter List allows you to re-import it in the future!">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/IPBlacklist/Export/"
                               class="tiny button radius"><i class="fi-download medium"></i></a>
                                </span>
            </div>
            <!-- Import -->
            <div class="small-1 columns left">
                    <span title="Import entries to this filter list!">
                            <a href="#" id="IPBlacklistImportUndo" class="tiny button radius"
                               onclick="return false;"><i class="fi-upload medium"></i></a>
                    </span>
                <div id="IPBlacklistImportWindow"></div>
                <script>
                    $("#IPBlacklistImportUndo").click(function () {
                        var url = "{$requestprotocol}://{$linkurl}Firewall/IPBlacklist/Import";
                        var IPBlacklistGrid = $("#gridIPBlacklist").data("kendoGrid");
                        var IPBlacklistImportWindow = $("#IPBlacklistImportWindow"),
                                undo = $("#IPBlacklistImportUndo")
                                        .bind("click", function () {
                                            if (!IPBlacklistImportWindow.data("kendoWindow")) {
                                                IPBlacklistImportWindow.data("kendoWindow").center();
                                                IPBlacklistImportWindow.data("kendoWindow").open();
                                            } else {
                                                IPBlacklistImportWindow.data("kendoWindow")
                                                        .content("Loading...") // add loading message
                                                        .refresh(url) // request the URL via AJAX
                                                        .center()
                                                        .open(); // open the window
                                            }
                                            undo.hide();
                                        });

                        var onClose = function () {
                            IPBlacklistGrid.dataSource.read();
                            undo.show();
                        };

                        IPBlacklistImportWindow.kendoWindow({
                            modal: true,
                            width: "615px",
                            height: "300px",
                            title: "Import IP Whitelist",
                            content: url,
                            close: onClose
                        });
                    });
                </script>
            </div>
            <!-- End Import -->
            <!-- ReInitialize -->
            <div class="small-1 columns left">
                             <span title="Remove all entries from this filter list!">
                            <a href="#" id="btnIPBlacklistReinitialize" class="tiny button radius"
                               onclick="return false;"><i class="fi-trash medium"></i></a>
                             </span>
                {literal}
                <script>
                    $("#btnIPBlacklistReinitialize").bind("click", function () {
                        var IPBlacklistGrid = $("#gridIPBlacklist").data("kendoGrid");
                        var dataSource = IPBlacklistGrid.dataSource;
                        var totalRecords = dataSource.total();
                        if (totalRecords > 0) {
                            var result = confirm("Are you sure you want to reinitialize the IP Blacklist?");
                            if (result) {
                                $.get("{/literal}{$requestprotocol}://{$linkurl}{literal}Firewall/IPBlacklist/Manage/reinitialize", function (data) {
                                            IPBlacklistGrid.dataSource.read();
                                        })
                                        .fail(function () {
                                            alertify.alert("Server unreachable or session expired!");
                                        })
                            }
                        }
                    });
                </script>{/literal}
                <div id="resIPBlacklistReinitialize"></div>
            </div>
            <!-- End ReInitialize -->
            <div class="small-1 columns left">
                            <span title="Exporting a Plugin allows you to re-import it in the future!">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/Core/Plugins/export?plugin=ip_blacklist_filter"
                               class="tiny button radius"><i class="fi-save medium"></i></a>
                                </span>
            </div>
            <div class="small-4 columns right text-right">
                            <span title="Deletion of a Filter also removes all data related to this filter!">
                            <a href="#" class="tiny button alert radius"
                               onClick="removePlugin('ip_blacklist_filter', 'IPBlacklist'); return false;"><i
                                        class="fi-skull medium"></i></a>
                            </span>
            </div>
        </div>
    </div>
</div>
</div>