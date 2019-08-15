<div id="chartTotal"></div>
{literal}
<script>
    function createChartTotal() {
        var valueAxisMax = {/literal}{$maxvisitorshour}{literal};

        $("#chartTotal").kendoChart({
            dataSource: {
                transport: {
                    read: {
                        url: "{/literal}{$requestprotocol}://{$linkurl}Feeds/Visitors/Charts/getStatsToday{literal}",
                        dataType: "json"
                    }
                }
            },
            title: {
                text: "Visitors today"
            },
            legend: {
                position: "top"
            },
            seriesDefaults: {
                type: "line"
            },
            series: [
                {
                    field: "total",
                    name: "Total",
                    type: "area",
                    color: "#007eff"
                },
                {
                    field: "unblocked",
                    name: "Unblocked Visitors",
                    type: "line",
                    color: "#00FF7F"
                },
                {
                    field: "blocked",
                    name: "Blocked Visitors",
                    type: "line",
                    color: "#FF4500"
                },
                {
                    field: "captcha",
                    name: "Captchas Solved",
                    type: "line",
                    color: "#FFFF00"
                }
            ],
            categoryAxis: {
                field: "updated",
                labels: {
                    rotation: -90
                },
                crosshair: {
                    visible: true
                }
            },
            valueAxis: {
                labels: {
                    format: "N0"
                },
                min: 0,
                max: valueAxisMax
                //majorUnit: 10000
            },
            tooltip: {
                visible: true,
                shared: true,
                format: "N0"
            }
        });
    }

    $(document).ready(createChartTotal);
</script>
{/literal}
<!--/Stats Test-->