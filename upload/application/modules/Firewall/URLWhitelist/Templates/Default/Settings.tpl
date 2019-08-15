<div id="URLWhitelist">
    <div class="row">
        <div class="small-4 columns">
            <input type="hidden" name="url_whitelist_filter[conf_class_name]"
                   value="{$params['url_whitelist_filter']['conf_class_name']}">
            <a href="#"
               class="handle button expand text-left {if $params['url_whitelist_filter']['status'] eq  '1'} success {/if} radius"
               onclick="$( '#URLWhitelistDetails' ).toggle( 'slow' );return false;" id="URLwhitelistButtonExpand">
                <i class="fi-list dragicon"></i>
                URLs
            </a>
            <script>
                var URLWhitelistToggle = false;
                $( "#URLwhitelistButtonExpand" ).click(function() {
                    if(URLWhitelistToggle === false){
                        URLWhitelistToggle = true;
                        $("#gridURLWhitelist").data("kendoGrid").dataSource.read();
                    } else {
                        URLWhitelistToggle = false;
                    }
                });
            </script>
        </div>
        <div class="small-2 columns text-center">
            <select name="url_whitelist_filter[conf_status]" class="radius">
                <option value="1" {if $params['url_whitelist_filter']['status'] eq  '1'} selected {/if} >Active</option>
                <option value="0" {if $params['url_whitelist_filter']['status'] eq  '0'} selected {/if}>Inactive
                </option>
            </select>
        </div>
        <div class="small-2 columns text-center">
            &nbsp;
        </div>
        <!--
    <div class="small-1 columns text-center">
        <input type="text" class="radius" name="url_whitelist_filter[conf_order]" value="{$params['url_whitelist_filter']['order']|escape:'htmlall'}">
    </div>
    -->
    </div>

    <div id="URLWhitelistDetails" style="display: none;">
        <!--Grid-->
        {include file="file:{$path_modules}/Firewall/URLWhitelist/Templates/{$config->template}/Grid.tpl"}
        <!-- End Grid -->
        <div class="panel">
            <!--
            <div class="row">
                <div class="small-11 columns">
                    <div class="panel">
                        <p>Whitelisted URLs are excluded from the Firewall process.</p>
                        Allowed formats are:
                        <ul>
                            <li>Exact URL</li>
                            <li>String contained in URL</li>
                            <li>Regular Expression</li>
                        </ul>
                    </div>
                </div>
            </div>
            <hr/>
            -->
            <div class="row">
                <!--Export-->
                <div class="small-1 columns left">
                            <span data-tooltip aria-haspopup="true" class="has-tip"
                                  title="Exporting a Filter List allows you to re-import it in the future!">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/URLWhitelist/Export/"
                               class="tiny button radius"><i class="fi-download medium"></i></a>
                                </span>
                </div>
                <!--Import-->
                <div class="small-1 columns left">
                    <div id="URLWhitelistImportWindow"></div>

                            <span title="Import entries to this filter list!">
                            <a href="#" id="URLWhitelistImportUndo" class="tiny button radius"
                               onclick="return false;"><i class="fi-upload medium"></i></a>
                            </span>
                    <script>
                        $("#URLWhitelistImportUndo").click(function () {
                            var url = "{$requestprotocol}://{$linkurl}Firewall/URLWhitelist/Import";
                            var URLWhitelistGrid = $("#gridURLWhitelist").data("kendoGrid");
                            var URLWhitelistImportWindow = $("#URLWhitelistImportWindow"),
                                    undo = $("#URLWhitelistImportUndo")
                                            .bind("click", function () {
                                                if (!URLWhitelistImportWindow.data("kendoWindow")) {
                                                    URLWhitelistImportWindow.data("kendoWindow").center();
                                                    URLWhitelistImportWindow.data("kendoWindow").open();
                                                } else {
                                                    URLWhitelistImportWindow.data("kendoWindow")
                                                            .content("Loading...") // add loading message
                                                            .refresh(url) // request the URL via AJAX
                                                            .center()
                                                            .open(); // open the window
                                                }
                                                undo.hide();
                                            });

                            var onOpen = function () {
                                URLWhitelistImportWindow.data("kendoWindow").open();
                            }

                            var onClose = function () {
                                URLWhitelistGrid.dataSource.read();
                                undo.show();
                            }

                            URLWhitelistImportWindow.kendoWindow({
                                modal: true,
                                width: "615px",
                                height: "300px",
                                title: "Import URL Whitelist",
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
                            <a href="#" id="btnURLWhitelistReinitialize" class="tiny button radius"
                               onclick="return false;"><i class="fi-trash medium"></i></a>
                             </span>
                    {literal}
                    <script>
                        $("#btnURLWhitelistReinitialize").bind("click", function () {
                            var URLWhitelistGrid = $("#gridURLWhitelist").data("kendoGrid");
                            var dataSource = URLWhitelistGrid.dataSource;
                            var totalRecords = dataSource.total();
                            if (totalRecords > 0) {
                                var result = confirm("Are you sure you want to reinitialize the URL Whitelist?");
                                if (result) {
                                    $.get("{/literal}{$requestprotocol}://{$linkurl}{literal}Firewall/URLWhitelist/Manage/reinitialize", function (data) {
                                                URLWhitelistGrid.dataSource.read();
                                            })
                                            .fail(function () {
                                                alertify.alert("Server unreachable or session expired!");
                                            })
                                }
                            }
                        });
                    </script>{/literal}
                    <div id="resURLWhitelistReinitialize"></div>
                </div>
                <!-- End ReInitialize -->
                <div class="small-1 columns left">
                            <span title="Exporting a Plugin allows you to re-import it in the future!">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/Core/Plugins/export?plugin=url_whitelist_filter"
                               class="tiny button radius"><i class="fi-save medium"></i></a>
                                </span>
                </div>
                <div class="small-4 columns right text-right">
                            <span title="Deletion of a Filter also removes all data related to this filter!">
                            <a href="#" class="tiny button alert radius"
                               onClick="removePlugin('url_whitelist_filter', 'URLWhitelist'); return false;"><i
                                        class="fi-skull medium"></i></a>
                            </span>
                </div>
            </div>
        </div>
    </div>
</div>