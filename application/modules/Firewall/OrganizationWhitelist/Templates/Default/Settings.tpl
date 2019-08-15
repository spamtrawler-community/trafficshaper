<div id="OrganizationWhitelist">
    <div class="row">
        <div class="small-4 columns">
            <input type="hidden" name="organization_whitelist_filter[conf_class_name]"
                   value="{$params['organization_whitelist_filter']['conf_class_name']}">
            <a href="#"
               class="handle button expand text-left {if $params['organization_whitelist_filter']['status'] eq  '1'} success {/if} radius"
               onclick="$( '#OrganizationWhitelistDetails' ).toggle( 'slow' );return false;" id="OrganizationwhitelistButtonExpand">
                <i class="fi-list dragicon"></i>
                Organizations
            </a>
            <script>
                var OrganizationWhitelistToggle = false;
                $( "#OrganizationwhitelistButtonExpand" ).click(function() {
                    if(OrganizationWhitelistToggle === false){
                        OrganizationWhitelistToggle = true;
                        $("#gridOrganizationWhitelist").data("kendoGrid").dataSource.read();
                    } else {
                        OrganizationWhitelistToggle = false;
                    }
                });
            </script>
        </div>
        <div class="small-2 columns text-center" class="radius">
            <select name="organization_whitelist_filter[conf_status]">
                <option value="1" {if $params['organization_whitelist_filter']['status'] eq  '1'} selected {/if} >
                    Active
                </option>
                <option value="0" {if $params['organization_whitelist_filter']['status'] eq  '0'} selected {/if}>
                    Inactive
                </option>
            </select>
        </div>
        <div class="small-2 columns text-center">
            &nbsp;
        </div>
        <!--
    <div class="small-1 columns text-center">
        <input type="text" class="radius" name="organization_whitelist_filter[conf_order]" value="{$params['organization_whitelist_filter']['order']|escape:'htmlall'}">
    </div>
    -->
    </div>

    <div id="OrganizationWhitelistDetails" style="display: none;">
        <!--Grid-->
        {include file="file:{$path_modules}/Firewall/OrganizationWhitelist/Templates/{$config->template}/Grid.tpl"}
        <!-- End Grid -->
        <div class="panel">
            <div class="row">
                <!--Export-->
                <div class="small-1 columns left">
                            <span data-tooltip aria-haspopup="true" class="has-tip"
                                  title="Exporting a Filter List allows you to re-import it in the future!">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/OrganizationWhitelist/Export/"
                               class="tiny button radius"><i class="fi-download medium"></i></a>
                                </span>
                </div>
                <!--Import-->
                <div class="small-1 columns left">
                            <span title="Import entries to this filter list!">
                            <a href="#" id="OrganizationWhitelistImportUndo" class="tiny button radius"
                               onclick="return false;"><i class="fi-upload medium"></i></a>
                            </span>
                    <div id="OrganizationWhitelistImportWindow"></div>
                    <script>
                        $("#OrganizationWhitelistImportUndo").click(function () {
                            var url = "{$requestprotocol}://{$linkurl}Firewall/OrganizationWhitelist/Import";
                            var OrganizationWhitelistGrid = $("#gridOrganizationWhitelist").data("kendoGrid");
                            var OrganizationWhitelistImportWindow = $("#OrganizationWhitelistImportWindow"),
                                    undo = $("#OrganizationWhitelistImportUndo")
                                            .bind("click", function () {
                                                if (!OrganizationWhitelistImportWindow.data("kendoWindow")) {
                                                    OrganizationWhitelistImportWindow.data("kendoWindow").center();
                                                    OrganizationWhitelistImportWindow.data("kendoWindow").open();
                                                } else {
                                                    OrganizationWhitelistImportWindow.data("kendoWindow")
                                                            .content("Loading...") // add loading message
                                                            .refresh(url) // request the URL via AJAX
                                                            .center()
                                                            .open(); // open the window
                                                }
                                                undo.hide();
                                            });

                            var onOpen = function () {
                                OrganizationWhitelistImportWindow.data("kendoWindow").open();
                            }

                            var onClose = function () {
                                OrganizationWhitelistGrid.dataSource.read();
                                undo.show();
                            }

                            OrganizationWhitelistImportWindow.kendoWindow({
                                modal: true,
                                width: "615px",
                                height: "300px",
                                title: "Import Organization Whitelist",
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
                            <a href="#" id="btnOrganizationWhitelistReinitialize" class="tiny button radius"
                               onclick="return false;"><i class="fi-trash medium"></i></a>
                             </span>
                    {literal}
                    <script>
                        $("#btnOrganizationWhitelistReinitialize").bind("click", function () {
                            var OrganizationWhitelistGrid = $("#gridOrganizationWhitelist").data("kendoGrid");
                            var dataSource = OrganizationWhitelistGrid.dataSource;
                            var totalRecords = dataSource.total();
                            if (totalRecords > 0) {
                                var result = confirm("Are you sure you want to reinitialize the Organization Whitelist?");
                                if (result) {
                                    $.get("{/literal}{$requestprotocol}://{$linkurl}{literal}Firewall/OrganizationWhitelist/Manage/reinitialize", function (data) {
                                                OrganizationWhitelistGrid.dataSource.read();
                                            })
                                            .fail(function () {
                                                alertify.alert("Server unreachable or session expired!");
                                            })
                                }
                            }
                        });
                    </script>{/literal}
                    <div id="resOrganizationWhitelistReinitialize"></div>
                </div>
                <!-- End ReInitialize -->
                <div class="small-1 columns left">
                            <span title="Exporting a Plugin allows you to re-import it in the future!">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/Core/Plugins/export?plugin=organization_whitelist_filter"
                               class="tiny button radius"><i class="fi-save medium"></i></a>
                                </span>
                </div>
                <div class="small-4 columns right text-right">
                            <span title="Deletion of a Filter also removes all data related to this filter!">
                            <a href="#" class="tiny button alert radius"
                               onClick="removePlugin('organization_whitelist_filter', 'OrganizationWhitelist'); return false;"><i
                                        class="fi-skull medium"></i></a>
                            </span>
                </div>
            </div>
        </div>
    </div>
</div>