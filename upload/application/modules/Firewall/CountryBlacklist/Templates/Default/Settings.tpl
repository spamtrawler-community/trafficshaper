<div id="CountryBlacklist">
    <div class="row">
        <div class="small-4 columns">
            <input type="hidden" name="country_blacklist_filter[conf_class_name]"
                   value="{$params['country_blacklist_filter']['conf_class_name']}">
            <a href="#"
               class="handle button expand text-left {if $params['country_blacklist_filter']['status'] eq  '1'} success {/if} radius"
               onclick="$( '#CountryBlacklistDetails' ).toggle( 'slow' );return false;" id="CountryblacklistButtonExpand">
                <i class="fi-list dragicon"></i>
                Countries
            </a>
            <script>
                var CountryBlacklistToggle = false;
                $( "#CountryblacklistButtonExpand" ).click(function() {
                    if(CountryBlacklistToggle === false){
                        CountryBlacklistToggle = true;
                        $("#gridCountryBlacklist").data("kendoGrid").dataSource.read();
                    } else {
                        CountryBlacklistToggle = false;
                    }
                });
            </script>
        </div>
        <div class="small-2 columns text-center">
            <select name="country_blacklist_filter[conf_status]" class="radius">
                <option value="1" {if $params['country_blacklist_filter']['status'] eq  '1'} selected {/if} >Active
                </option>
                <option value="0" {if $params['country_blacklist_filter']['status'] eq  '0'} selected {/if}>Inactive
                </option>
            </select>
        </div>
        <div class="small-2 columns text-center left">
            <select name="country_blacklist_filter[conf_params][allowcaptcha]" class="radius">
                <option value="1" {if $params['country_blacklist_filter']['allowcaptcha'] eq  '1'} selected {/if} >
                    Challenge
                </option>
                <option value="0" {if $params['country_blacklist_filter']['allowcaptcha'] eq  '0'} selected {/if}>Don't
                    Challenge
                </option>
            </select>
        </div>
        {if isset($StatsBlockedByFilter['Firewall_CountryBlacklist_Controller_Filter']) }
            <div class="small-1 large-2 columns left" title="{($StatsBlockedByFilter['Firewall_CountryBlacklist_Controller_Filter']/$StatsBlockedByFilter['all']*100)|string_format:"%.2f"}%">
                <div class="radius progress success small-12"><span class="meter"
                                                                    style="width: {($StatsBlockedByFilter['Firewall_CountryBlacklist_Controller_Filter']/$StatsBlockedByFilter['all']*100)|string_format:"%.2f"}%"></span>
                </div>
            </div>
        {/if}
        <!--
    <div class="small-1 columns text-center">
        <input type="text" class="radius" name="country_blacklist_filter[conf_order]" value="{$params['country_blacklist_filter']['order']|escape:'htmlall'}">
    </div>
    -->
    </div>

    <div id="CountryBlacklistDetails" style="display: none;">
        <!--Grid-->
        {include file="file:{$path_modules}/Firewall/CountryBlacklist/Templates/{$config->template}/Grid.tpl"}
        <!-- End Grid -->
        <div class="panel">
            <div class="row">
                <div class="small-2 columns">
                    <label for="continents" class="right">Block Continent</label>
                </div>
                <div class="small-6 columns left">
                    <select id="continents" class="radius">
                        <option value="AF" selected>Africa</option>
                        <option value="AN">Antarctica</option>
                        <option value="AS">Asia</option>
                        <option value="OC">Australia (Oceania)</option>
                        <option value="EU">Europe</option>
                        <option value="NA">North America</option>
                        <option value="SA">South America</option>
                    </select>
                </div>
                <div class="small-2 columns left">
                    <button type="button" id="btnBlockContinent" class="btn btn-warning radius">Blacklist</button>
                </div>
                {literal}
                <script>
                    $( "#btnBlockContinent" ).click(function() {
                        var continent = $('#continents').val();
                        $.ajax({
                            type: 'POST',
                            url: '{/literal}{$requestprotocol}://{$linkurl}{literal}Firewall/CountryBlacklist/Manage/addContinent',
                            data: {continentISO: continent},
                            dataType: 'html',
                            success: function (data) {
                                $('#gridCountryBlacklist').data('kendoGrid').dataSource.read();
                                $('#gridCountryBlacklist').data('kendoGrid').refresh();
                            },
                            async: true
                        });
                    });
                </script>
                {/literal}
            </div>
            <div class="row">
                <div class="small-2 columns">
                    <label for="country_blacklist_filter[conf_params][block_reason]" class="right">
                        <span title="Text shown for {literal}{BlockReason}{/literal} placeholder in exit message and in data grids">Block Reason</span></label>
                </div>
                <div class="small-6 columns left">
                    <input type="text" name="country_blacklist_filter[conf_params][block_reason]"
                           value="{$params['country_blacklist_filter']['block_reason']|escape:'htmlall'}">
                </div>
            </div>
            <hr/>
            <div class="row">
                <!--Export-->
                <div class="small-1 columns left">
                            <span data-tooltip aria-haspopup="true" class="has-tip"
                                  title="Exporting a Filter List allows you to re-import it in the future!">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/CountryBlacklist/Export/"
                               class="tiny button radius"><i class="fi-download medium"></i></a>
                                </span>
                </div>
                <!--Import-->
                <div class="small-1 columns left">
                    <div id="CountryBlacklistImportWindow"></div>

                    <span data-tooltip aria-haspopup="true" class="has-tip"
                          title="Import entries to this filter list!">
                            <a href="#" id="CountryBlacklistImportUndo" class="tiny button radius"
                               onclick="return false;"><i class="fi-upload medium"></i></a>
                            </span>

                    <script>
                        $("#CountryBlacklistImportUndo").click(function () {
                            var url = "{$requestprotocol}://{$linkurl}Firewall/CountryBlacklist/Import";
                            var CountryBlacklistGrid = $("#gridCountryBlacklist").data("kendoGrid");
                            var CountryBlacklistImportWindow = $("#CountryBlacklistImportWindow"),
                                undo = $("#CountryBlacklistImportUndo")
                                    .bind("click", function () {
                                        if (!CountryBlacklistImportWindow.data("kendoWindow")) {
                                            CountryBlacklistImportWindow.data("kendoWindow").center();
                                            CountryBlacklistImportWindow.data("kendoWindow").open();
                                        } else {
                                            CountryBlacklistImportWindow.data("kendoWindow")
                                                .content("Loading...") // add loading message
                                                .refresh(url) // request the URL via AJAX
                                                .center()
                                                .open(); // open the window
                                        }
                                        undo.hide();
                                    });

                            var onOpen = function () {
                                CountryBlacklistImportWindow.data("kendoWindow").open();
                            }

                            var onClose = function () {
                                CountryBlacklistGrid.dataSource.read();
                                undo.show();
                            }

                            CountryBlacklistImportWindow.kendoWindow({
                                modal: true,
                                width: "615px",
                                height: "300px",
                                title: "Import Country Blacklist",
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
                            <a href="#" id="btnCountryBlacklistReinitialize" class="tiny button radius"
                               onclick="return false;"><i class="fi-trash medium"></i></a>
                             </span>
                    {literal}
                    <script>
                        $("#btnCountryBlacklistReinitialize").bind("click", function () {
                            var CountryBlacklistGrid = $("#gridCountryBlacklist").data("kendoGrid");
                            var dataSource = CountryBlacklistGrid.dataSource;
                            var totalRecords = dataSource.total();
                            if (totalRecords > 0) {
                                var result = confirm("Are you sure you want to reinitialize the Country Blacklist?");
                                if (result) {
                                    $.get("{/literal}{$requestprotocol}://{$linkurl}{literal}Firewall/CountryBlacklist/Manage/reinitialize", function (data) {
                                                CountryBlacklistGrid.dataSource.read();
                                            })
                                            .fail(function () {
                                                alertify.alert("Server unreachable or session expired!");
                                            })
                                }
                            }
                        });
                    </script>{/literal}
                    <div id="resCountryBlacklistReinitialize"></div>
                </div>
                <!-- End ReInitialize -->
                <div class="small-1 columns left">
                            <span title="Exporting a Plugin allows you to re-import it in the future!">
                            <a href="{$requestprotocol}://{$linkurl}Firewall/Core/Plugins/export?plugin=country_blacklist_filter"
                               class="tiny button radius"><i class="fi-save medium"></i></a>
                                </span>
                </div>
                <div class="small-4 columns right text-right">
                            <span title="Deletion of a Filter also removes all data related to this filter!">
                            <a href="#" class="tiny button alert radius"
                               onClick="removePlugin('country_blacklist_filter', 'CountryBlacklist'); return false;"><i
                                        class="fi-skull medium"></i></a>
                            </span>
                </div>
            </div>
        </div>
    </div>
</div>