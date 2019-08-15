<div id="ASNBlacklist">
    <div class="row">
        <div class="small-4 columns">
            <input type="hidden" name="asn_blacklist_filter[conf_class_name]"
                   value="{$params['asn_blacklist_filter']['conf_class_name']}">
            <a href="#"
               class="handle button expand text-left {if $params['asn_blacklist_filter']['status'] eq  '1'} success {/if} radius"
               onclick="$( '#ASNBlacklistDetails' ).toggle( 'slow' );return false;" id="AsnBlacklistButtonExpand">
                <i class="fi-list dragicon"></i>
                ASN
            </a>
            <script>
                var AsnBlacklistToggle = false;
                $( "#AsnBlacklistButtonExpand" ).click(function() {
                    if(AsnBlacklistToggle === false){
                        AsnBlacklistToggle = true;
                        $("#gridASNBlacklist").data("kendoGrid").dataSource.read();
                    } else {
                        AsnBlacklistToggle = false;
                    }
                });
            </script>
        </div>
        <div class="small-2 columns text-center">
            <select name="asn_blacklist_filter[conf_status]" class="radius">
                <option value="1" {if $params['asn_blacklist_filter']['status'] eq  '1'} selected {/if} >Active</option>
                <option value="0" {if $params['asn_blacklist_filter']['status'] eq  '0'} selected {/if}>Inactive
                </option>
            </select>
        </div>
        <div class="small-2 columns text-center left">
            <select name="asn_blacklist_filter[conf_params][allowcaptcha]" class="radius">
                <option value="1" {if $params['asn_blacklist_filter']['allowcaptcha'] eq  '1'} selected {/if} >
                    Challenge
                </option>
                <option value="0" {if $params['asn_blacklist_filter']['allowcaptcha'] eq  '0'} selected {/if}>Don't
                    Challenge
                </option>
            </select>
        </div>
        {if isset($StatsBlockedByFilter['Firewall_ASNBlacklist_Controller_Filter']) }
            <div class="small-1 large-2 columns left has-tip" data-tooltip aria-haspopup="true"
                 title="{($StatsBlockedByFilter['Firewall_ASNBlacklist_Controller_Filter']/$StatsBlockedByFilter['all']*100)|string_format:"%.2f"}%">
                <div class="radius progress success small-12"><span class="meter"
                                                                    style="width: {($StatsBlockedByFilter['Firewall_ASNBlacklist_Controller_Filter']/$StatsBlockedByFilter['all']*100)|string_format:"%.2f"}%"></span>
                </div>
            </div>
        {/if}
        <!--
    <div class="small-1 columns text-center">
        <input type="text" name="asn_blacklist_filter[conf_order]" class="radius" value="{$params['asn_blacklist_filter']['order']|escape:'htmlall'}">
    </div>
    -->
    </div>

    <div id="ASNBlacklistDetails" style="display: none;">
        <!--Grid-->
        {include file="file:{$path_modules}/Firewall/ASNBlacklist/Templates/{$config->template}/Grid.tpl"}
        <!-- End Grid -->
        <div class="panel">
            <div class="row">
                <div class="small-2 columns text-left">
                    <label for="asn_blacklist_filter[conf_params][block_reason]" class="right"><span
                                data-tooltip aria-haspopup="true" class="has-tip"
                                title="Text shown for {literal}{BlockReason}{/literal} placeholder in exit message and in data grids">Block Reason</span></label>
                </div>
                <div class="small-6 columns left text-left">
                    <input type="text" name="asn_blacklist_filter[conf_params][block_reason]"
                           value="{$params['asn_blacklist_filter']['block_reason']|escape:'htmlall'}">
                </div>
            </div>
            <div class="row">
                <!--Export-->
                <div class="small-1 columns left">
                            <span data-tooltip aria-haspopup="true" class="has-tip"
                                  title="Exporting a Filter List allows you to re-import it in the future!">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/ASNBlacklist/Export/"
                               class="tiny button radius"><i class="fi-download medium"></i></a>
                                </span>
                </div>
                <!--Import-->
                <div class="small-1 columns left">
                    <div id="ASNBlacklistImportWindow"></div>
                            <span data-tooltip aria-haspopup="true" class="has-tip"
                                  title="Import entries to this filter list!">
                            <a href="#" id="ASNBlacklistImportUndo" class="tiny button radius"
                               onclick="return false;"><i class="fi-upload medium"></i></a>
                            </span>
                    <script>
                        $("#ASNBlacklistImportUndo").click(function () {
                            var url = "{$requestprotocol}://{$linkurl}Firewall/ASNBlacklist/Import";
                            var ASNBlacklistGrid = $("#gridASNBlacklist").data("kendoGrid");
                            var ASNBlacklistImportWindow = $("#ASNBlacklistImportWindow"),
                                    undo = $("#ASNBlacklistImportUndo")
                                            .bind("click", function () {
                                                if (!ASNBlacklistImportWindow.data("kendoWindow")) {
                                                    ASNBlacklistImportWindow.data("kendoWindow").center();
                                                    ASNBlacklistImportWindow.data("kendoWindow").open();
                                                } else {
                                                    ASNBlacklistImportWindow.data("kendoWindow")
                                                            .content("Loading...") // add loading message
                                                            .refresh(url) // request the URL via AJAX
                                                            .center()
                                                            .open(); // open the window
                                                }
                                                undo.hide();
                                            });

                            var onOpen = function () {
                                ASNBlacklistImportWindow.data("kendoWindow").open();
                            }

                            var onClose = function () {
                                ASNBlacklistGrid.dataSource.read();
                                undo.show();
                            }

                            ASNBlacklistImportWindow.kendoWindow({
                                modal: true,
                                width: "615px",
                                height: "300px",
                                title: "Import ASN Blacklist",
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
                            <a href="#" id="btnASNBlacklistReinitialize" class="tiny button radius"
                               onclick="return false;"><i class="fi-trash medium"></i></a>
                             </span>
                    {literal}
                    <script>
                        $("#btnASNBlacklistReinitialize").bind("click", function () {
                            var ASNBlacklistGrid = $("#gridASNBlacklist").data("kendoGrid");
                            var dataSource = ASNBlacklistGrid.dataSource;
                            var totalRecords = dataSource.total();
                            if (totalRecords > 0) {
                                var result = confirm("Are you sure you want to reinitialize the ASN Blacklist?");
                                if (result) {
                                    $.get("{/literal}{$requestprotocol}://{$linkurl}{literal}Firewall/ASNBlacklist/Manage/reinitialize", function (data) {
                                                ASNBlacklistGrid.dataSource.read();
                                            })
                                            .fail(function () {
                                                alertify.alert("Server unreachable or session expired!");
                                            })
                                }
                            }
                        });
                    </script>{/literal}
                    <div id="resASNBlacklistReinitialize"></div>
                </div>
                <!-- End ReInitialize -->
                <div class="small-1 columns left">
                            <span data-tooltip aria-haspopup="true" class="has-tip"
                                  title="Exporting a Plugin allows you to re-import it in the future!">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/Core/Plugins/export?plugin=asn_blacklist_filter"
                               class="tiny button radius"><i class="fi-save medium"></i></a>
                                </span>
                </div>
                <div class="small-1 columns right text-right">
                            <span data-tooltip aria-haspopup="true" class="has-tip"
                                  title="Deletion of a Filter also removes all data related to this filter!">
                            <a href="#" class="tiny button alert radius"
                               onClick="removePlugin('asn_blacklist_filter', 'ASNBlacklist'); return false;"><i
                                        class="fi-skull medium"></i></a>
                            </span>
                </div>
            </div>
            <!-- /New -->
        </div>
    </div>
</div>