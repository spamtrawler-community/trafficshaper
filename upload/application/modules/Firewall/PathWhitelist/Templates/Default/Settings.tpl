<div id="PathWhitelist">
    <div class="row">
        <div class="small-4 columns">
            <input type="hidden" name="path_whitelist_filter[conf_class_name]"
                   value="{$params['path_whitelist_filter']['conf_class_name']}">
            <a href="#"
               class="handle button expand text-left {if $params['path_whitelist_filter']['status'] eq  '1'} success {/if} radius"
               onclick="$( '#PathWhitelistDetails' ).toggle( 'slow' );return false;" id="PathwhitelistButtonExpand">
                <i class="fi-list dragicon"></i>
                Paths
            </a>
            <script>
                var PathWhitelistToggle = false;
                $( "#PathwhitelistButtonExpand" ).click(function() {
                    if(PathWhitelistToggle === false){
                        PathWhitelistToggle = true;
                        $("#gridPathWhitelist").data("kendoGrid").dataSource.read();
                    } else {
                        PathWhitelistToggle = false;
                    }
                });
            </script>
        </div>
        <div class="small-2 columns text-center">
            <select name="path_whitelist_filter[conf_status]" class="radius">
                <option value="1" {if $params['path_whitelist_filter']['status'] eq  '1'} selected {/if} >Active
                </option>
                <option value="0" {if $params['path_whitelist_filter']['status'] eq  '0'} selected {/if}>Inactive
                </option>
            </select>
        </div>
        <div class="small-2 columns text-center">
            &nbsp;
        </div>
        <!--
    <div class="small-1 columns text-center">
        <input type="text" class="radius" name="path_whitelist_filter[conf_order]" value="{$params['path_whitelist_filter']['order']|escape:'htmlall'}">
    </div>
    -->
    </div>

    <div id="PathWhitelistDetails" style="display: none;">
        <!--Grid-->
        {include file="file:{$path_modules}/Firewall/PathWhitelist/Templates/{$config->template}/Grid.tpl"}
        <!-- End Grid -->
        <div class="panel">
            <div class="row">
                <!--Export-->
                <div class="small-1 columns left">
                            <span data-tooltip aria-haspopup="true" class="has-tip"
                                  title="Exporting a Filter List allows you to re-import it in the future!">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/PathWhitelist/Export/"
                               class="tiny button radius"><i class="fi-download medium"></i></a>
                                </span>
                </div>
                <!--Import-->
                <div class="small-1 columns left">
                            <span title="Import entries to this filter list!">
                            <a href="#" id="PathWhitelistImportUndo" class="tiny button radius"
                               onclick="return false;"><i class="fi-upload medium"></i></a>
                            </span>
                    <div id="PathWhitelistImportWindow"></div>
                    <script>
                        $("#PathWhitelistImportUndo").click(function () {
                            var url = "{$requestprotocol}://{$linkurl}Firewall/PathWhitelist/Import";
                            var PathWhitelistGrid = $("#gridPathWhitelist").data("kendoGrid");
                            var PathWhitelistImportWindow = $("#PathWhitelistImportWindow"),
                                    undo = $("#PathWhitelistImportUndo")
                                            .bind("click", function () {
                                                if (!PathWhitelistImportWindow.data("kendoWindow")) {
                                                    PathWhitelistImportWindow.data("kendoWindow").center();
                                                    PathWhitelistImportWindow.data("kendoWindow").open();
                                                } else {
                                                    PathWhitelistImportWindow.data("kendoWindow")
                                                            .content("Loading...") // add loading message
                                                            .refresh(url) // request the URL via AJAX
                                                            .center()
                                                            .open(); // open the window
                                                }
                                                undo.hide();
                                            });

                            var onOpen = function () {
                                PathWhitelistImportWindow.data("kendoWindow").open();
                            }

                            var onClose = function () {
                                PathWhitelistGrid.dataSource.read();
                                undo.show();
                            }

                            PathWhitelistImportWindow.kendoWindow({
                                modal: true,
                                width: "615px",
                                height: "300px",
                                title: "Import Path Whitelist",
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
                            <a href="#" id="btnPathWhitelistReinitialize" class="tiny button radius"
                               onclick="return false;"><i class="fi-trash medium"></i></a>
                             </span>
                 {literal}
                    <script>
                        $("#btnPathWhitelistReinitialize").bind("click", function () {
                            var PathWhitelistGrid = $("#gridPathWhitelist").data("kendoGrid");
                            var dataSource = PathWhitelistGrid.dataSource;
                            var totalRecords = dataSource.total();
                            if (totalRecords > 0) {
                                var result = confirm("Are you sure you want to reinitialize the Path Whitelist?");
                                if (result) {
                                    $.get("{/literal}{$requestprotocol}://{$linkurl}{literal}Firewall/PathWhitelist/Manage/reinitialize", function (data) {
                                                PathWhitelistGrid.dataSource.read();
                                            })
                                            .fail(function () {
                                                alertify.alert("Server unreachable or session expired!");
                                            })
                                }
                            }
                        });
                    </script>{/literal}
                    <div id="resPathWhitelistReinitialize"></div>
                </div>
                <!-- End ReInitialize -->
                <div class="small-1 columns left">
                            <span title="Exporting a Plugin allows you to re-import it in the future!">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/Core/Plugins/export?plugin=path_whitelist_filter"
                               class="tiny button radius"><i class="fi-save medium"></i></a>
                                </span>
                </div>
                <div class="small-4 columns right text-right">
                            <span title="Deletion of a Filter also removes all data related to this filter!">
                            <a href="#" class="tiny button alert radius"
                               onClick="removePlugin('path_whitelist_filter', 'PathWhitelist'); return false;"><i
                                        class="fi-skull medium"></i></a>
                            </span>
                </div>
            </div>
        </div>
    </div>
</div>