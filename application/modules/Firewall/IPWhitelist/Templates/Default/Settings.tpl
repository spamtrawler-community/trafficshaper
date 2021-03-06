<div id="IPWhitelist">
    <div class="row">
        <div class="small-4 columns">
                <input type="hidden" name="ip_whitelist_filter[conf_class_name]"
                       value="{$params['ip_whitelist_filter']['conf_class_name']}">
                <span>
                    <a href="#" class="handle button expand text-left {if $params['ip_whitelist_filter']['status'] eq  '1'} success {/if} radius"
                   onclick="$( '#IPWhitelistDetails' ).toggle( 'slow' );return false;" id="IPwhitelistButtonExpand">
                        <i class="fi-list dragicon"></i>
                        IPs
                    </a>
                </span>
            <script>
                var IPWhitelistToggle = false;
                $( "#IPwhitelistButtonExpand" ).click(function() {
                    if(IPWhitelistToggle === false){
                        IPWhitelistToggle = true;
                        $("#gridIPWhitelist").data("kendoGrid").dataSource.read();
                    } else {
                        IPWhitelistToggle = false;
                    }
                });
            </script>
        </div>
        <div class="small-2 columns text-center">
            <select name="ip_whitelist_filter[conf_status]" class="radius">
                <option value="1" {if $params['ip_whitelist_filter']['status'] eq  '1'} selected {/if} >Active</option>
                <option value="0" {if $params['ip_whitelist_filter']['status'] eq  '0'} selected {/if}>Inactive</option>
            </select>
        </div>
        <div class="small-2 columns text-center">
            &nbsp;
        </div>
        <!--
    <div class="small-1 columns text-center">
        <input type="text" class="radius" name="ip_whitelist_filter[conf_order]" value="{$params['ip_whitelist_filter']['order']|escape:'htmlall'}">
    </div>
    -->
    </div>

    <div id="IPWhitelistDetails" style="display: none;">
        <!--Grid-->
        {include file="file:{$path_modules}/Firewall/IPWhitelist/Templates/{$config->template}/Grid.tpl"}
        <!-- End Grid -->
        <div class="panel">
            <div class="row">
                <!--Export-->
                <div class="small-1 columns left">
                            <span data-tooltip aria-haspopup="true" class="has-tip"
                                  title="Exporting a Filter List allows you to re-import it in the future!">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/IPWhitelist/Export/"
                               class="tiny button radius"><i class="fi-download medium"></i></a>
                                </span>
                </div>
                <!--Import-->
                <div class="small-1 columns left">
                            <span title="Import entries to this filter list!">
                            <a href="#" id="IPWhitelistImportUndo" class="tiny button radius"
                               onclick="return false;"><i class="fi-upload medium"></i></a>
                            </span>
                    <div id="IPWhitelistImportWindow"></div>
                    <script>
                        $("#IPWhitelistImportUndo").click(function () {
                            var url = "{$requestprotocol}://{$linkurl}Firewall/IPWhitelist/Import";
                            var IPWhitelistGrid = $("#gridIPWhitelist").data("kendoGrid");
                            var IPWhitelistImportWindow = $("#IPWhitelistImportWindow"),
                                    undo = $("#IPWhitelistImportUndo")
                                            .bind("click", function () {
                                                if (!IPWhitelistImportWindow.data("kendoWindow")) {
                                                    IPWhitelistImportWindow.data("kendoWindow").center();
                                                    IPWhitelistImportWindow.data("kendoWindow").open();
                                                } else {
                                                    IPWhitelistImportWindow.data("kendoWindow")
                                                            .content("Loading...") // add loading message
                                                            .refresh(url) // request the URL via AJAX
                                                            .center()
                                                            .open(); // open the window
                                                }
                                                undo.hide();
                                            });

                            var onOpen = function () {
                                IPWhitelistImportWindow.data("kendoWindow").open();
                            }

                            var onClose = function () {
                                IPWhitelistGrid.dataSource.read();
                                undo.show();
                            }

                            IPWhitelistImportWindow.kendoWindow({
                                modal: true,
                                width: "615px",
                                height: "300px",
                                title: "Import IP Whitelist",
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
                            <a href="#" id="btnIPWhitelistReinitialize" class="tiny button radius"
                               onclick="return false;"><i class="fi-trash medium"></i></a>
                             </span>
                    {literal}
                    <script>
                        $("#btnIPWhitelistReinitialize").bind("click", function () {
                            var IPWhitelistGrid = $("#gridIPWhitelist").data("kendoGrid");
                            var dataSource = IPWhitelistGrid.dataSource;
                            var totalRecords = dataSource.total();
                            if (totalRecords > 0) {
                                var result = confirm("Are you sure you want to reinitialize the IP Whitelist?");
                                if (result) {
                                    $.get("{/literal}{$requestprotocol}://{$linkurl}{literal}Firewall/IPWhitelist/Manage/reinitialize", function (data) {
                                                IPWhitelistGrid.dataSource.read();
                                            })
                                            .fail(function () {
                                                alertify.alert("Server unreachable or session expired!");
                                            })
                                }
                            }
                        });
                    </script>{/literal}
                    <div id="resIPWhitelistReinitialize"></div>
                </div>
                <!-- End ReInitialize -->
                <div class="small-1 columns left">
                            <span title="Exporting a Plugin allows you to re-import it in the future!">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/Core/Plugins/export?plugin=ip_whitelist_filter"
                               class="tiny button radius"><i class="fi-save medium"></i></a>
                                </span>
                </div>
                <div class="small-4 columns right text-right">
                            <span title="Deletion of a Filter also removes all data related to this filter!">
                            <a href="#" class="tiny button alert radius"
                               onClick="removePlugin('ip_whitelist_filter', 'IPWhitelist'); return false;"><i
                                        class="fi-skull medium"></i></a>
                            </span>
                </div>
            </div>
        </div>
    </div>
</div>