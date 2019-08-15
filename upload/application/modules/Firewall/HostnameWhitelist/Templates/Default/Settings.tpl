<div id="HostnameWhitelist">
    <div class="row">
        <div class="small-4 columns">
            <input type="hidden" name="hostname_whitelist_filter[conf_class_name]"
                   value="{$params['hostname_whitelist_filter']['conf_class_name']}">
            <a href="#"
               class="handle button expand text-left {if $params['hostname_whitelist_filter']['status'] eq  '1'} success {/if} radius"
               onclick="$( '#HostnameWhitelistDetails' ).toggle( 'slow' );return false;" id="HostnameWhitelistButton">
                <i class="fi-list dragicon"></i>
                Hostnames
            </a>
            <script>
            var HostnameWhitelistToggle = false;
            $( "#HostnameWhitelistButton" ).click(function() {
                if(HostnameWhitelistToggle === false){
                    HostnameWhitelistToggle = true;
                    $("#gridHostnameWhitelist").data("kendoGrid").dataSource.read();
                } else {
                    HostnameWhitelistToggle = false;
                }
            });
            </script>
        </div>
        <div class="small-2 columns text-center">
            <select name="hostname_whitelist_filter[conf_status]" class="radius">
                <option value="1" {if $params['hostname_whitelist_filter']['status'] eq  '1'} selected {/if} >Active
                </option>
                <option value="0" {if $params['hostname_whitelist_filter']['status'] eq  '0'} selected {/if}>Inactive
                </option>
            </select>
        </div>
        <div class="small-2 columns text-center">
            &nbsp;
        </div>
        <!--
    <div class="small-1 columns text-center">
        <input type="text" class="radius" name="hostname_whitelist_filter[conf_order]" value="{$params['hostname_whitelist_filter']['order']|escape:'htmlall'}">
    </div>
    -->
    </div>

    <div id="HostnameWhitelistDetails" style="display: none;">
        <!--Grid-->
        {include file="file:{$path_modules}/Firewall/HostnameWhitelist/Templates/{$config->template}/Grid.tpl"}
        <!-- End Grid -->
        <div class="panel">
            <div class="row">
                <!--Export-->
                <div class="small-1 columns left">
                            <span data-tooltip aria-haspopup="true" class="has-tip"
                                  title="Exporting a Filter List allows you to re-import it in the future!">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/HostnameWhitelist/Export/"
                               class="tiny button radius"><i class="fi-download medium"></i></a>
                                </span>
                </div>
                <!--Import-->
                <div class="small-1 columns left">
                            <span title="Import entries to this filter list!">
                            <a href="#" id="HostnameWhitelistImportUndo" class="tiny button radius"
                               onclick="return false;"><i class="fi-upload medium"></i></a>
                            </span>
                    <div id="HostnameWhitelistImportWindow"></div>



                    <script>
                        $("#HostnameWhitelistImportUndo").click(function () {
                            var url = "{$requestprotocol}://{$linkurl}Firewall/HostnameWhitelist/Import";
                            var HostnameWhitelistGrid = $("#gridHostnameWhitelist").data("kendoGrid");
                            var HostnameWhitelistImportWindow = $("#HostnameWhitelistImportWindow"),
                                    undo = $("#HostnameWhitelistImportUndo")
                                            .bind("click", function () {
                                                if (!HostnameWhitelistImportWindow.data("kendoWindow")) {
                                                    HostnameWhitelistImportWindow.data("kendoWindow").center();
                                                    HostnameWhitelistImportWindow.data("kendoWindow").open();
                                                } else {
                                                    HostnameWhitelistImportWindow.data("kendoWindow")
                                                            .content("Loading...") // add loading message
                                                            .refresh(url) // request the URL via AJAX
                                                            .center()
                                                            .open(); // open the window
                                                }
                                                undo.hide();
                                            });

                            var onOpen = function () {
                                HostnameWhitelistImportWindow.data("kendoWindow").open();
                            }

                            var onClose = function () {
                                HostnameWhitelistGrid.dataSource.read();
                                undo.show();
                            }

                            HostnameWhitelistImportWindow.kendoWindow({
                                modal: true,
                                width: "615px",
                                height: "300px",
                                title: "Import Hostname Whitelist",
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
                            <a href="#" id="btnHostnameWhitelistReinitialize" class="tiny button radius"
                               onclick="return false;"><i class="fi-trash medium"></i></a>
                             </span>
                    {literal}
                    <script>
                        $("#btnHostnameWhitelistReinitialize").bind("click", function () {
                            var HostnameWhitelistGrid = $("#gridHostnameWhitelist").data("kendoGrid");
                            var dataSource = HostnameWhitelistGrid.dataSource;
                            var totalRecords = dataSource.total();
                            if (totalRecords > 0) {
                                var result = confirm("Are you sure you want to reinitialize the Hostname Whitelist?");
                                if (result) {
                                    $.get("{/literal}{$requestprotocol}://{$linkurl}{literal}Firewall/HostnameWhitelist/Manage/reinitialize", function (data) {
                                                HostnameWhitelistGrid.dataSource.read();
                                            })
                                            .fail(function () {
                                                alertify.alert("Server unreachable or session expired!");
                                            })
                                }
                            }
                        });
                    </script>{/literal}
                    <div id="resHostnameWhitelistReinitialize"></div>
                </div>
                <!-- End ReInitialize -->
                <div class="small-1 columns left">
                            <span title="Exporting a Plugin allows you to re-import it in the future!">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/Core/Plugins/export?plugin=hostname_whitelist_filter"
                               class="tiny button radius"><i class="fi-save medium"></i></a>
                                </span>
                </div>
                <div class="small-4 columns right text-right">
                            <span title="Deletion of a Filter also removes all data related to this filter!">
                            <a href="#" class="tiny button alert radius"
                               onClick="removePlugin('hostname_whitelist_filter', 'HostnameWhitelist'); return false;"><i
                                        class="fi-skull medium"></i></a>
                            </span>
                </div>
            </div>
        </div>
    </div>
</div>