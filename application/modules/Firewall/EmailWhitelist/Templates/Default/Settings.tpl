<div id="EmailWhitelist">
    <div class="row">
        <div class="small-4 columns">
            <input type="hidden" name="email_whitelist_filter[conf_class_name]"
                   value="{$params['email_whitelist_filter']['conf_class_name']}">
            <a href="#"
               class="handle button expand text-left {if $params['email_whitelist_filter']['status'] eq  '1'} success {/if} radius"
               onclick="$( '#EmailWhitelistDetails' ).toggle( 'slow' );return false;" id="EmailwhitelistButtonExpand">
                <i class="fi-list dragicon"></i>
                Email Addresses
            </a>
            <script>
                var EmailWhitelistToggle = false;
                $( "#EmailwhitelistButtonExpand" ).click(function() {
                    if(EmailWhitelistToggle === false){
                        EmailWhitelistToggle = true;
                        $("#gridEmailWhitelist").data("kendoGrid").dataSource.read();
                    } else {
                        EmailWhitelistToggle = false;
                    }
                });
            </script>
        </div>
        <div class="small-2 columns text-center">
            <select name="email_whitelist_filter[conf_status]" class="radius">
                <option value="1" {if $params['email_whitelist_filter']['status'] eq  '1'} selected {/if} >Active
                </option>
                <option value="0" {if $params['email_whitelist_filter']['status'] eq  '0'} selected {/if}>Inactive
                </option>
            </select>
        </div>
        <div class="small-2 columns text-center">
            &nbsp;
        </div>
        <!--
    <div class="small-1 columns text-center">
        <input type="text" class="radius" name="email_whitelist_filter[conf_order]" value="{$params['email_whitelist_filter']['order']|escape:'htmlall'}">
    </div>
    -->
    </div>

    <div id="EmailWhitelistDetails" style="display: none;">
        <!--Grid-->
        {include file="file:{$path_modules}/Firewall/EmailWhitelist/Templates/{$config->template}/Grid.tpl"}
        <!-- End Grid -->
        <div class="panel">
            <div class="row">
                <!--Export-->
                <div class="small-1 columns left">
                            <span data-tooltip aria-haspopup="true" class="has-tip"
                                  title="Exporting a Filter List allows you to re-import it in the future!">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/EmailWhitelist/Export/"
                               class="tiny button radius"><i class="fi-download medium"></i></a>
                                </span>
                </div>
                <!--Import-->
                <div class="small-1 columns left">
                            <span title="Import entries to this filter list!">
                            <a href="#" id="EmailWhitelistImportUndo" class="tiny button radius"
                               onclick="return false;"><i class="fi-upload medium"></i></a>
                            </span>
                    <div id="EmailWhitelistImportWindow"></div>

                    <script>
                        $("#EmailWhitelistImportUndo").click(function () {
                            var url = "{$requestprotocol}://{$linkurl}Firewall/EmailWhitelist/Import";
                            var EmailWhitelistGrid = $("#gridEmailWhitelist").data("kendoGrid");
                            var EmailWhitelistImportWindow = $("#EmailWhitelistImportWindow"),
                                    undo = $("#EmailWhitelistImportUndo")
                                            .bind("click", function () {
                                                if (!EmailWhitelistImportWindow.data("kendoWindow")) {
                                                    EmailWhitelistImportWindow.data("kendoWindow").center();
                                                    EmailWhitelistImportWindow.data("kendoWindow").open();
                                                } else {
                                                    EmailWhitelistImportWindow.data("kendoWindow")
                                                            .content("Loading...") // add loading message
                                                            .refresh(url) // request the URL via AJAX
                                                            .center()
                                                            .open(); // open the window
                                                }
                                                undo.hide();
                                            });

                            var onOpen = function () {
                                EmailWhitelistImportWindow.data("kendoWindow").open();
                            }

                            var onClose = function () {
                                EmailWhitelistGrid.dataSource.read();
                                undo.show();
                            }

                            EmailWhitelistImportWindow.kendoWindow({
                                modal: true,
                                width: "615px",
                                height: "300px",
                                title: "Import Email Whitelist",
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
                            <a href="#" id="btnEmailWhitelistReinitialize" class="tiny button radius"
                               onclick="return false;"><i class="fi-trash medium"></i></a>
                             </span>
                    {literal}
                    <script>
                        $("#btnEmailWhitelistReinitialize").bind("click", function () {
                            var EmailWhitelistGrid = $("#gridEmailWhitelist").data("kendoGrid");
                            var dataSource = EmailWhitelistGrid.dataSource;
                            var totalRecords = dataSource.total();
                            if (totalRecords > 0) {
                                var result = confirm("Are you sure you want to reinitialize the Email Whitelist?");
                                if (result) {
                                    $.get("{/literal}{$requestprotocol}://{$linkurl}{literal}Firewall/EmailWhitelist/Manage/reinitialize", function (data) {
                                                EmailWhitelistGrid.dataSource.read();
                                            })
                                            .fail(function () {
                                                alertify.alert("Server unreachable or session expired!");
                                            })
                                }
                            }
                        });
                    </script>{/literal}
                    <div id="resEmailWhitelistReinitialize"></div>
                </div>
                <!-- End ReInitialize -->
                <div class="small-1 columns left">
                            <span title="Exporting a Plugin allows you to re-import it in the future!">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/Core/Plugins/export?plugin=email_whitelist_filter"
                               class="tiny button radius"><i class="fi-download medium"></i></a>
                                </span>
                </div>
                <div class="small-4 columns right text-right">
                            <span title="Deletion of a Filter also removes all data related to this filter!">
                            <a href="#" class="tiny button alert radius"
                               onClick="removePlugin('email_whitelist_filter', 'EmailWhitelist'); return false;"><i
                                        class="fi-skull medium"></i></a>
                            </span>
                </div>
            </div>
        </div>
    </div>
</div>