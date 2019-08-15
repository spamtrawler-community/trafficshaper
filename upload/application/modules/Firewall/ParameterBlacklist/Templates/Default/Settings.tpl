<div id="ParameterBlacklist">
    <div class="row">
        <div class="small-4 columns">
            <input type="hidden" name="parameter_blacklist_filter[conf_class_name]"
                   value="{$params['parameter_blacklist_filter']['conf_class_name']}">
            <a href="#"
               class="handle button expand text-left {if $params['parameter_blacklist_filter']['status'] eq  '1'} success {/if} radius"
               onclick="$( '#ParameterBlacklistDetails' ).toggle( 'slow' );return false;" id="ParameterblacklistButtonExpand">
                <i class="fi-list dragicon"></i>
                Parameter (GET)
            </a>
            <script>
                var ParameterBlacklistToggle = false;
                $( "#ParameterblacklistButtonExpand" ).click(function() {
                    if(ParameterBlacklistToggle === false){
                        ParameterBlacklistToggle = true;
                        $("#gridParameterBlacklist").data("kendoGrid").dataSource.read();
                    } else {
                        ParameterBlacklistToggle = false;
                    }
                });
            </script>
        </div>
        <div class="small-2 columns text-center">
            <select name="parameter_blacklist_filter[conf_status]" class="radius">
                <option value="1" {if $params['parameter_blacklist_filter']['status'] eq  '1'} selected {/if} >Active
                </option>
                <option value="0" {if $params['parameter_blacklist_filter']['status'] eq  '0'} selected {/if}>Inactive
                </option>
            </select>
        </div>
        <div class="small-2 columns text-center left">
            <select name="parameter_blacklist_filter[conf_params][allowcaptcha]" class="radius">
                <option value="1" {if $params['parameter_blacklist_filter']['allowcaptcha'] eq  '1'} selected {/if} >
                    Challenge
                </option>
                <option value="0" {if $params['parameter_blacklist_filter']['allowcaptcha'] eq  '0'} selected {/if}>
                    Don't
                    Challenge
                </option>
            </select>
        </div>
        {if isset($StatsBlockedByFilter['Firewall_ParameterBlacklist_Controller_Filter']) }
        <div class="small-1 large-2 columns left" title="{($StatsBlockedByFilter['Firewall_ParameterBlacklist_Controller_Filter']/$StatsBlockedByFilter['all']*100)|string_format:"%.2f"}%">
            <!--     <div style="background-color: '#FFFF00'; width: '{$StatsBlockedByFilter['Firewall_IPBlacklist_Controller_Filter']}%'; height: '20px'"></div>     -->
            <div class="radius progress success small-12"><span class="meter"
                                                               style="width: {($StatsBlockedByFilter['Firewall_ParameterBlacklist_Controller_Filter']/$StatsBlockedByFilter['all']*100)|string_format:"%.2f"}%"></span>
            </div>
        </div>
        {/if}
        <!--
    <div class="small-1 columns text-center">
        <input type="text" class="radius" name="parameter_blacklist_filter[conf_order]" value="{$params['parameter_blacklist_filter']['order']|escape:'htmlall'}">
    </div>
    -->
    </div>

    <div id="ParameterBlacklistDetails" style="display: none;">
        <!--Grid-->
        {include file="file:{$path_modules}/Firewall/ParameterBlacklist/Templates/{$config->template}/Grid.tpl"}
        <!-- End Grid -->
        <div class="panel">
            <div class="row">
                <div class="small-2 columns">
                    <label for="parameter_blacklist_filter[conf_params][block_reason]" class="right"><span
                                title="Text shown for {literal}{BlockReason}{/literal} placeholder in exit message and in data grids">Block Reason</span></label>
                </div>
                <div class="small-6 columns left">
                    <input type="text" name="parameter_blacklist_filter[conf_params][block_reason]"
                           value="{$params['parameter_blacklist_filter']['block_reason']|escape:'htmlall'}">
                </div>
            </div>
            <hr/>
            <div class="row">
                <!--Export-->
                <div class="small-1 columns left">
                            <span data-tooltip aria-haspopup="true" class="has-tip"
                                  title="Exporting a Filter List allows you to re-import it in the future!">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/ParameterBlacklist/Export/"
                               class="tiny button radius"><i class="fi-download medium"></i></a>
                                </span>
                </div>
                <!-- Import -->
                <div class="small-1 columns left">
                    <span title="Import entries to this filter list!">
                            <a href="#" id="ParameterBlacklistImportUndo" class="tiny button radius"
                               onclick="return false;"><i class="fi-upload medium"></i></a>
                    </span>
                    <div id="ParameterBlacklistImportWindow"></div>
                    <script>
                        $("#ParameterBlacklistImportUndo").click(function () {
                            var url = "{$requestprotocol}://{$linkurl}Firewall/ParameterBlacklist/Import";
                            var ParameterBlacklistGrid = $("#gridParameterBlacklist").data("kendoGrid");
                            var ParameterBlacklistImportWindow = $("#ParameterBlacklistImportWindow"),
                                    undo = $("#ParameterBlacklistImportUndo")
                                            .bind("click", function () {
                                                if (!ParameterBlacklistImportWindow.data("kendoWindow")) {
                                                    ParameterBlacklistImportWindow.data("kendoWindow").center();
                                                    ParameterBlacklistImportWindow.data("kendoWindow").open();
                                                } else {
                                                    ParameterBlacklistImportWindow.data("kendoWindow")
                                                            .content("Loading...") // add loading message
                                                            .refresh(url) // request the URL via AJAX
                                                            .center()
                                                            .open(); // open the window
                                                }
                                                undo.hide();
                                            });

                            var onOpen = function () {
                                ParameterBlacklistImportWindow.data("kendoWindow").open();
                            }

                            var onClose = function () {
                                ParameterBlacklistGrid.dataSource.read();
                                undo.show();
                            }

                            ParameterBlacklistImportWindow.kendoWindow({
                                modal: true,
                                width: "615px",
                                height: "300px",
                                title: "Import Parameter Blacklist",
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
                            <a href="#" id="btnParameterBlacklistReinitialize" class="tiny button radius"
                               onclick="return false;"><i class="fi-trash medium"></i></a>
                             </span>
                    {literal}
                    <script>
                        $("#btnParameterBlacklistReinitialize").bind("click", function () {
                            var ParameterBlacklistGrid = $("#gridParameterBlacklist").data("kendoGrid");
                            var dataSource = ParameterBlacklistGrid.dataSource;
                            var totalRecords = dataSource.total();
                            if (totalRecords > 0) {
                                var result = confirm("Are you sure you want to reinitialize the Parameter Blacklist?");
                                if (result) {
                                    $.get("{/literal}{$requestprotocol}://{$linkurl}{literal}Firewall/ParameterBlacklist/Manage/reinitialize", function (data) {
                                                ParameterBlacklistGrid.dataSource.read();
                                            })
                                            .fail(function () {
                                                alertify.alert("Server unreachable or session expired!");
                                            })
                                }
                            }
                        });
                    </script>{/literal}
                    <div id="resParameterBlacklistReinitialize"></div>
                </div>
                <!-- End ReInitialize -->
                <div class="small-1 columns left">
                            <span title="Exporting a Plugin allows you to re-import it in the future!">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/Core/Plugins/export?plugin=parameter_blacklist_filter"
                               class="tiny button radius"><i class="fi-save medium"></i></a>
                                </span>
                </div>
                <div class="small-4 columns right text-right">
                            <span title="Deletion of a Filter also removes all data related to this filter!">
                            <a href="#" class="tiny button alert radius"
                               onClick="removePlugin('parameter_blacklist_filter', 'ParameterBlacklist'); return false;"><i
                                        class="fi-skull medium"></i></a>
                            </span>
                </div>
            </div>
        </div>
    </div>
</div>