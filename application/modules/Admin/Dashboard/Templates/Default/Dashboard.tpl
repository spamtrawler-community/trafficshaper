<!DOCTYPE html>
<!--[if IE 9]>
<html class="lt-ie10" lang="en"> <![endif]-->
<html class="no-js" lang="en">
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Head.tpl"}
<body>
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Header.tpl"}
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/MainNav.tpl"}

{if isset($serverload) }
    <div class="row">
        <div class="small-12 columns text-right">
            <span class="radius secondary label">Server Load Average: {$serverload}</span>
        </div>
    </div>
{/if}

{if $maxvisitorshour neq 0}
    <div class="row">
        <div class="small-12 columns">
            <div id="tabstrip" class="chart-wrapper">
                <ul>
                    <li class="k-state-active">
                        {$language['Admin_Dashboard']['Total_Today']}
                    </li>
                    <li>
                        {$language['Admin_Dashboard']['By_Country']}
                    </li>
                    <li>
                        Device
                    </li>
                </ul>
                <div>
                    <!--Row for Unblocked visitors grid Feeds module-->
                    {include file="file:{$path_modules}/Feeds/Visitors/Templates/ChartTotalToday.tpl"}
                </div>
                <div>
                    <!--Row for Unblocked visitors grid Feeds module-->
                    {include file="file:{$path_modules}/Feeds/Visitors/Templates/ChartByCountry.tpl"}
                </div>
                <div>
                    <!--Row for Unblocked visitors grid Feeds module-->
                    {include file="file:{$path_modules}/Feeds/Visitors/Templates/ChartByDevice.tpl"}
                </div>
            </div>
            <script>
                $(window).on("resize", function () {
                    kendo.resize($(".chart-wrapper"));
                });
            </script>

            <style scoped>
                #tabstrip {
                    width: 100%;
                    margin: 30px auto;
                }

                #tabstrip h2 {
                    font-weight: lighter;
                    font-size: 5em;
                    padding: 0;
                    margin: 0;
                }

                #tabstrip h2 span {
                    background: none;
                    padding-left: 5px;
                    font-size: .5em;
                    vertical-align: top;
                }

                #tabstrip p {
                    margin: 0;
                    padding: 0;
                }
            </style>

            {literal}
                <script>
                    $(document).ready(function () {
                        $("#tabstrip").kendoTabStrip({
                            activate: function () {
                                var chartCountry = $("#chartByCountry").data("kendoChart");
                                var chartDevice = $("#chartByDevice").data("kendoChart");
                                chartCountry.redraw();
                                chartDevice.redraw();
                            },
                            animation: {
                                open: {
                                    effects: "fadeIn"
                                }
                            }
                        });
                    });
                </script>
            {/literal}
        </div>
        <hr>
    </div>
{/if}

{if $totalvisitors neq 0}
    <!--Row for Unblocked visitors grid Feeds module-->
    {include file="file:{$path_modules}/Feeds/Visitors/Templates/GridUnblocked.tpl"}

    <!--Row for Blocked visitors grid Feeds module-->
    {include file="file:{$path_modules}/Feeds/Visitors/Templates/GridBlocked.tpl"}

    <!--Row for visitors who solved captcha grid Feeds module-->
    {*{include file="file:{$path_modules}/Feeds/Visitors/Templates/GridCaptchaSolved.tpl"}*}
{else}
    <div class="row">
        <div class="panel">
            <div data-alert="" class="alert-box alert radius">
                No Data Collected yet!
            </div>
        </div>
    </div>
{/if}
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Footer.tpl"}
</body>
</html>