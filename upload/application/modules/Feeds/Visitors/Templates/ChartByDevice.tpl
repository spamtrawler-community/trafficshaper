<div id="chartByDevice"></div>
{literal}
<script>
    function createChartTotal() {
        var valueAxisMax = {/literal}{$maxvisitorshour}{literal};

        $("#chartByDevice").kendoChart({
            dataSource: {
                transport: {
                    read: {
                        url: "{/literal}{$requestprotocol}://{$linkurl}Feeds/Visitors/Charts/getStatsByDevice{literal}",
                        dataType: "json"
                    }
                }
            },
            title: {
                text: "Visitors By Device"
            },
            legend: {
                position: "top"
            },
            seriesDefaults: {
                type: "column"
            },
            series: [
                {
                    field: "used",
                    name: "Total",
                    color: "#007eff"
                },
                {
                    field: "passed_count",
                    name: "Unblocked Visitors",
                    color: "#00FF7F"
                },
                {
                    field: "blocked_count",
                    name: "Blocked Visitors",
                    color: "#FF4500"
                }
            ],
            valueAxis: {
                labels: {
                    format: "N0"
                },
                majorUnit: 100,
                max: valueAxisMax
            },
            categoryAxis: {
                field: "device",
                labels: {
                    rotation: -90
                },
                crosshair: {
                    visible: true
                }
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