<div id="IPRangesWhitelist">
    <div class="row">
        <div class="small-4 columns">
            <input type="hidden" name="ipranges_whitelist_filter[conf_class_name]"
                   value="{$params['ipranges_whitelist_filter']['conf_class_name']}">
            <a href="#"
               class="handle button expand text-left {if $params['ipranges_whitelist_filter']['status'] eq  '1'} success {/if} radius"
               onclick="$( '#IPRangesWhitelistDetails' ).toggle( 'slow' );return false;" id="IPRangeswhitelistButtonExpand">
                <i class="fi-list dragicon"></i>
                IP Ranges
            </a>
            <script>
                var IPRangesWhitelistToggle = false;
                $( "#IPRangeswhitelistButtonExpand" ).click(function() {
                    if(IPRangesWhitelistToggle === false){
                        IPRangesWhitelistToggle = true;
                        $("#gridIPRangesWhitelist").data("kendoGrid").dataSource.read();
                    } else {
                        IPRangesWhitelistToggle = false;
                    }
                });
            </script>
        </div>
        <div class="small-2 columns text-center">
            <select name="ipranges_whitelist_filter[conf_status]" class="radius">
                <option value="1" {if $params['ipranges_whitelist_filter']['status'] eq  '1'} selected {/if} >Active
                </option>
                <option value="0" {if $params['ipranges_whitelist_filter']['status'] eq  '0'} selected {/if}>Inactive
                </option>
            </select>
        </div>
        <div class="small-2 columns text-center">
            &nbsp;
        </div>
        <!--
    <div class="small-1 columns text-center">
        <input type="text" class="radius" name="ipranges_whitelist_filter[conf_order]" value="{$params['ipranges_whitelist_filter']['order']|escape:'htmlall'}">
    </div>
    -->
    </div>

    <div id="IPRangesWhitelistDetails" style="display: none;">
        <!--Grid-->
        {include file="file:{$path_modules}/Firewall/IPRangesWhitelist/Templates/{$config->template}/Grid.tpl"}
        <!-- End Grid -->

        <!-- New -->

        <div class="panel">
            <div class="row">
                <!--Export-->
                <div class="small-1 columns left">
                            <span data-tooltip aria-haspopup="true" class="has-tip"
                                  title="Exporting a Filter List allows you to re-import it in the future!">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/IPRangesWhitelist/Export/"
                               class="tiny button radius"><i class="fi-download medium"></i></a>
                                </span>
                </div>
                <!--Import-->
                <div class="small-1 columns left">
                            <span title="Import entries to this filter list!">
                            <a href="#" id="IPRangesWhitelistImportUndo" class="tiny button radius"
                               onclick="return false;"><i class="fi-upload medium"></i></a>
                            </span>
                    <div id="IPRangesWhitelistImportWindow"></div>

                    <script>
                        $("#IPRangesWhitelistImportUndo").click(function () {
                            var url = "{$requestprotocol}://{$linkurl}Firewall/IPRangesWhitelist/Import";
                            var IPRangesWhitelistGrid = $("#gridIPRangesWhitelist").data("kendoGrid");
                            var IPRangesWhitelistImportWindow = $("#IPRangesWhitelistImportWindow"),
                                    undo = $("#IPRangesWhitelistImportUndo")
                                            .bind("click", function () {
                                                if (!IPRangesWhitelistImportWindow.data("kendoWindow")) {
                                                    IPRangesWhitelistImportWindow.data("kendoWindow").center();
                                                    IPRangesWhitelistImportWindow.data("kendoWindow").open();
                                                } else {
                                                    IPRangesWhitelistImportWindow.data("kendoWindow")
                                                            .content("Loading...") // add loading message
                                                            .refresh(url) // request the URL via AJAX
                                                            .center()
                                                            .open(); // open the window
                                                }
                                                undo.hide();
                                            });

                            var onOpen = function () {
                                IPRangesWhitelistImportWindow.data("kendoWindow").open();
                            }

                            var onClose = function () {
                                IPRangesWhitelistGrid.dataSource.read();
                                undo.show();
                            }

                            IPRangesWhitelistImportWindow.kendoWindow({
                                modal: true,
                                width: "615px",
                                height: "300px",
                                title: "Import Ip Ranges Whitelist",
                                content: url,
                                close: onClose
                            });


                        });
                    </script>
                    <!-- End Import -->
                </div>
                <!-- ReInitialize -->
                <div class="small-1 columns left">
                             <span title="Remove all entries from this filter list!">
                            <a href="#" id="btnIPRangesWhitelistReinitialize" class="tiny button radius"
                               onclick="return false;"><i class="fi-trash medium"></i></a>
                             </span>
                    {literal}
                    <script>
                        $("#btnIPRangesWhitelistReinitialize").bind("click", function () {
                            var IPRangesWhitelistGrid = $("#gridIPRangesWhitelist").data("kendoGrid");
                            var dataSource = IPRangesWhitelistGrid.dataSource;
                            var totalRecords = dataSource.total();
                            if (totalRecords > 0) {
                                var result = confirm("Are you sure you want to reinitialize the IP Ranges Whitelist?");
                                if (result) {
                                    $.get("{/literal}{$requestprotocol}://{$linkurl}{literal}Firewall/IPRangesWhitelist/Manage/reinitialize", function (data) {
                                                IPRangesWhitelistGrid.dataSource.read();
                                            })
                                            .fail(function () {
                                                alertify.alert("Server unreachable or session expired!");
                                            })
                                }
                            }
                        });
                    </script>{/literal}
                    <div id="resIPRangesWhitelistReinitialize"></div>
                </div>
                <!-- End ReInitialize -->
                <div class="small-1 columns left">
                            <span title="Exporting a Plugin allows you to re-import it in the future!">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/Core/Plugins/export?plugin=ipranges_whitelist_filter"
                               class="tiny button radius"><i class="fi-save medium"></i></a>
                                </span>
                </div>
                <div class="small-4 columns right text-right">
                            <span title="Deletion of a Filter also removes all data related to this filter!">
                            <a href="#" class="tiny button alert radius"
                               onClick="removePlugin('ipranges_whitelist_filter', 'IPRangesWhitelist'); return false;"><i
                                        class="fi-skull medium"></i></a>
                            </span>
                </div>
            </div>
        </div>

        <!-- New -->

    </div>
</div>