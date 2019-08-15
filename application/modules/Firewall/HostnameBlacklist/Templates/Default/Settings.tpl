<div id="HostnameBlacklist">
    <div class="row">
        <div class="small-4 columns">
            <input type="hidden" name="hostname_blacklist_filter[conf_class_name]"
                   value="{$params['hostname_blacklist_filter']['conf_class_name']}">
            <a href="#"
               class="handle button expand text-left {if $params['hostname_blacklist_filter']['status'] eq  '1'} success {/if} radius"
               onclick="$( '#HostnameBlacklistDetails' ).toggle( 'slow' );return false;" id="HostnameblacklistButtonExpand">
                <i class="fi-list dragicon"></i>
                Hostnames
            </a>
            <script>
                var HostnameBlacklistToggle = false;
                $( "#HostnameblacklistButtonExpand" ).click(function() {
                    if(HostnameBlacklistToggle === false){
                        HostnameBlacklistToggle = true;
                        $("#gridHostnameBlacklist").data("kendoGrid").dataSource.read();
                    } else {
                        HostnameBlacklistToggle = false;
                    }
                });
            </script>
        </div>
        <div class="small-2 columns text-center">
            <select name="hostname_blacklist_filter[conf_status]" class="radius">
                <option value="1" {if $params['hostname_blacklist_filter']['status'] eq  '1'} selected {/if} >Active
                </option>
                <option value="0" {if $params['hostname_blacklist_filter']['status'] eq  '0'} selected {/if}>Inactive
                </option>
            </select>
        </div>
        <div class="small-2 columns text-center left">
            <select name="hostname_blacklist_filter[conf_params][allowcaptcha]" class="radius">
                <option value="1" {if $params['hostname_blacklist_filter']['allowcaptcha'] eq  '1'} selected {/if} >
                    Challenge
                </option>
                <option value="0" {if $params['hostname_blacklist_filter']['allowcaptcha'] eq  '0'} selected {/if}>Don't
                    Challenge
                </option>
            </select>
        </div>
        {if isset($StatsBlockedByFilter['Firewall_HostnameBlacklist_Controller_Filter']) }
        <div class="small-1 large-2 columns left has-tip" data-tooltip aria-haspopup="true"
             title="{($StatsBlockedByFilter['Firewall_HostnameBlacklist_Controller_Filter']/$StatsBlockedByFilter['all']*100)|string_format:"%.2f"}%">
            <div class="radius progress success small-12"><span class="meter"
                                                               style="width: {($StatsBlockedByFilter['Firewall_HostnameBlacklist_Controller_Filter']/$StatsBlockedByFilter['all']*100)|string_format:"%.2f"}%"></span>
            </div>
        </div>
        {/if}
        <!--
    <div class="small-1 columns text-center">
        <input type="text" class="radius" name="hostname_blacklist_filter[conf_order]" value="{$params['hostname_blacklist_filter']['order']|escape:'htmlall'}">
    </div>
    -->
    </div>

    <div id="HostnameBlacklistDetails" style="display: none;">
        <!--Grid-->
        {include file="file:{$path_modules}/Firewall/HostnameBlacklist/Templates/{$config->template}/Grid.tpl"}
        <!-- End Grid -->
        <div class="panel">
            <div class="row">
                <div class="small-2 columns">
                    <label for="hostname_blacklist_filter[conf_params][block_reason]" class="right"><span
                                title="Text shown for {literal}{BlockReason}{/literal} placeholder in exit message and in data grids">Block Reason</span></label>
                </div>
                <div class="small-6 columns left">
                    <input type="text" name="hostname_blacklist_filter[conf_params][block_reason]"
                           value="{$params['hostname_blacklist_filter']['block_reason']|escape:'htmlall'}">
                </div>
            </div>
            <hr/>
            <div class="row">
                <!--Export-->
                <div class="small-1 columns left">
                            <span data-tooltip aria-haspopup="true" class="has-tip"
                                  title="Exporting a Filter List allows you to re-import it in the future!">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/HostnameBlacklist/Export/"
                               class="tiny button radius"><i class="fi-download medium"></i></a>
                                </span>
                </div>
                <!-- Import -->
                <div class="small-1 columns left">
                    <span title="Import entries to this filter list!">
                            <a href="#" id="HostnameBlacklistImportUndo" class="tiny button radius"
                               onclick="return false;"><i class="fi-upload medium"></i></a>
                    </span>
                    <div id="HostnameBlacklistImportWindow"></div>

                    <script>
                        $("#HostnameBlacklistImportUndo").click(function () {
                            var url = "{$requestprotocol}://{$linkurl}Firewall/HostnameBlacklist/Import";
                            var HostnameBlacklistGrid = $("#gridHostnameBlacklist").data("kendoGrid");
                            var HostnameBlacklistImportWindow = $("#HostnameBlacklistImportWindow"),
                                    undo = $("#HostnameBlacklistImportUndo")
                                            .bind("click", function () {
                                                if (!HostnameBlacklistImportWindow.data("kendoWindow")) {
                                                    HostnameBlacklistImportWindow.data("kendoWindow").center();
                                                    HostnameBlacklistImportWindow.data("kendoWindow").open();
                                                } else {
                                                    HostnameBlacklistImportWindow.data("kendoWindow")
                                                            .content("Loading...") // add loading message
                                                            .refresh(url) // request the URL via AJAX
                                                            .center()
                                                            .open(); // open the window
                                                }
                                                undo.hide();
                                            });

                            var onOpen = function () {
                                HostnameBlacklistImportWindow.data("kendoWindow").open();
                            }

                            var onClose = function () {
                                HostnameBlacklistGrid.dataSource.read();
                                undo.show();
                            }

                            HostnameBlacklistImportWindow.kendoWindow({
                                modal: true,
                                width: "615px",
                                height: "300px",
                                title: "Import Hostname Blacklist",
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
                            <a href="#" id="btnHostnameBlacklistReinitialize" class="tiny button radius"
                               onclick="return false;"><i class="fi-trash medium"></i></a>
                             </span>
                    {literal}
                    <script>
                        $("#btnHostnameBlacklistReinitialize").bind("click", function () {
                            var HostnameBlacklistGrid = $("#gridHostnameBlacklist").data("kendoGrid");
                            var dataSource = HostnameBlacklistGrid.dataSource;
                            var totalRecords = dataSource.total();
                            if (totalRecords > 0) {
                                var result = confirm("Are you sure you want to reinitialize the Hostname Blacklist?");
                                if (result) {
                                    $.get("{/literal}{$requestprotocol}://{$linkurl}{literal}Firewall/HostnameBlacklist/Manage/reinitialize", function (data) {
                                                HostnameBlacklistGrid.dataSource.read();
                                            })
                                            .fail(function () {
                                                alertify.alert("Server unreachable or session expired!");
                                            })
                                }
                            }
                        });
                    </script>{/literal}
                    <div id="resHostnameBlacklistReinitialize"></div>
                </div>
                <!-- End ReInitialize -->
                <div class="small-1 columns left">
                            <span title="Exporting a Plugin allows you to re-import it in the future!">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/Core/Plugins/export?plugin=hostname_blacklist_filter"
                               class="tiny button radius"><i class="fi-save medium"></i></a>
                                </span>
                </div>
                <div class="small-4 columns right text-right">
                            <span title="Deletion of a Filter also removes all data related to this filter!">
                            <a href="#" class="tiny button alert radius"
                               onClick="removePlugin('hostname_blacklist_filter', 'HostnameBlacklist'); return false;"><i
                                        class="fi-skull medium"></i></a>
                            </span>
                </div>
            </div>
        </div>
    </div>
</div>