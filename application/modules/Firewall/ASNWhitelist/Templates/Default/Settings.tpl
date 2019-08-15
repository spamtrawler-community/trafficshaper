<div id="ASNWhitelist">
    <div class="row">
        <div class="small-4 columns">
            <input type="hidden" name="asn_whitelist_filter[conf_class_name]"
                   value="{$params['asn_whitelist_filter']['conf_class_name']}">
            <a href="#"
               class="handle button expand text-left {if $params['asn_whitelist_filter']['status'] eq  '1'} success {/if} radius"
               onclick="$( '#ASNWhitelistDetails' ).toggle( 'slow' );return false;" id="AsnWhitelistButtonExpand">
                <i class="fi-list dragicon"></i>
                ASN
            </a>
            <script>
                var AsnWhitelistToggle = false;
                $( "#AsnWhitelistButtonExpand" ).click(function() {
                    if(AsnWhitelistToggle === false){
                        AsnWhitelistToggle = true;
                        $("#gridASNWhitelist").data("kendoGrid").dataSource.read();
                    } else {
                        AsnWhitelistToggle = false;
                    }
                });
            </script>
        </div>
        <div class="small-2 columns text-center">
            <select name="asn_whitelist_filter[conf_status]" class="radius">
                <option value="1" {if $params['asn_whitelist_filter']['status'] eq  '1'} selected {/if} >Active</option>
                <option value="0" {if $params['asn_whitelist_filter']['status'] eq  '0'} selected {/if}>Inactive
                </option>
            </select>
        </div>
        <div class="small-2 columns text-center">
            &nbsp;
        </div>
        <!--
    <div class="small-1 columns text-center">
        <input type="text" class="radius" name="asn_whitelist_filter[conf_order]" value="{$params['asn_whitelist_filter']['order']|escape:'htmlall'}">
    </div>
    -->
    </div>

    <div id="ASNWhitelistDetails" style="display: none;">
        <!--Grid-->
        {include file="file:{$path_modules}/Firewall/ASNWhitelist/Templates/{$config->template}/Grid.tpl"}
        <!-- End Grid -->
        <div class="panel">
            <div class="row">
                <!--Export-->
                <div class="small-1 columns left">
                            <span data-tooltip aria-haspopup="true" class="has-tip"
                                  title="Exporting a Filter List allows you to re-import it in the future!">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/ASNWhitelist/Export/"
                               class="tiny button radius"><i class="fi-download medium"></i></a>
                                </span>
                </div>
                <!--Import-->
                <div class="small-1 columns left">
                    <div id="ASNWhitelistImportWindow"></div>

                            <span data-tooltip aria-haspopup="true" class="has-tip"
                                  title="Import entries to this filter list!">
                            <a href="#" id="ASNWhitelistImportUndo" class="tiny button radius"
                               onclick="return false;"><i class="fi-upload medium"></i></a>
                            </span>

                    <script>
                        $("#ASNWhitelistImportUndo").click(function () {
                            var url = "{$requestprotocol}://{$linkurl}Firewall/ASNWhitelist/Import";
                            var ASNWhitelistGrid = $("#gridASNWhitelist").data("kendoGrid");
                            var ASNWhitelistImportWindow = $("#ASNWhitelistImportWindow"),
                                    undo = $("#ASNWhitelistImportUndo")
                                            .bind("click", function () {
                                                if (!ASNWhitelistImportWindow.data("kendoWindow")) {
                                                    ASNWhitelistImportWindow.data("kendoWindow").center();
                                                    ASNWhitelistImportWindow.data("kendoWindow").open();
                                                } else {
                                                    ASNWhitelistImportWindow.data("kendoWindow")
                                                            .content("Loading...") // add loading message
                                                            .refresh(url) // request the URL via AJAX
                                                            .center()
                                                            .open(); // open the window
                                                }
                                                undo.hide();
                                            });

                            var onOpen = function () {
                                ASNWhitelistImportWindow.data("kendoWindow").open();
                            }

                            var onClose = function () {
                                ASNWhitelistGrid.dataSource.read();
                                undo.show();
                            }

                            ASNWhitelistImportWindow.kendoWindow({
                                modal: true,
                                width: "615px",
                                height: "300px",
                                title: "Import ASN Whitelist",
                                content: url,
                                close: onClose
                            });


                        });
                    </script>
                    <!-- End Import -->
                </div>
                <!-- ReInitialize -->
                <div class="small-1 columns left">
                             <span data-tooltip aria-haspopup="true" class="has-tip"
                                   title="Remove all entries from this filter list!">
                            <a href="#" id="btnASNWhitelistReinitialize" class="tiny button radius"
                               onclick="return false;"><i class="fi-trash medium"></i></a>
                             </span>
                    {literal}
                    <script>
                        $("#btnASNWhitelistReinitialize").bind("click", function () {
                            var ASNWhitelistGrid = $("#gridASNWhitelist").data("kendoGrid");
                            var dataSource = ASNWhitelistGrid.dataSource;
                            var totalRecords = dataSource.total();
                            if (totalRecords > 0) {
                                var result = confirm("Are you sure you want to reinitialize the ASN Whitelist?");
                                if (result) {
                                    $.get("{/literal}{$requestprotocol}://{$linkurl}{literal}Firewall/ASNWhitelist/Manage/reinitialize", function (data) {
                                                ASNWhitelistGrid.dataSource.read();
                                            })
                                            .fail(function () {
                                                alertify.alert("Server unreachable or session expired!");
                                            })
                                }
                            }
                        });
                    </script>{/literal}
                    <div id="resASNWhitelistReinitialize"></div>
                </div>
                <!-- End ReInitialize -->
                <div class="small-1 columns left">
                            <span data-tooltip aria-haspopup="true" class="has-tip"
                                  title="Exporting a Plugin allows you to re-import it in the future!">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/Core/Plugins/export?plugin=asn_whitelist_filter"
                               class="tiny button radius"><i class="fi-save medium"></i></a>
                                </span>
                </div>
                <div class="small-4 columns right text-right">
                            <span data-tooltip aria-haspopup="true" class="has-tip"
                                  title="Deletion of a Filter also removes all data related to this filter!">
                            <a href="#" class="tiny button alert radius"
                               onClick="removePlugin('asn_whitelist_filter', 'ASNWhitelist'); return false;"><i
                                        class="fi-skull medium"></i></a>
                            </span>
                </div>
            </div>
        </div>
    </div>
</div>