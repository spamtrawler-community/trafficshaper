<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" >
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Head.tpl"}
<body>
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Header.tpl"}
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/MainNav.tpl"}

<div class="row">
    <div class="small-12 columns">
        <h3>User Agents</h3>
    </div>

    <div class="small-12 columns" style="min-height: 550px;">
        <span id="gridUserAgents"></span>
    </div>
    <hr />
</div>


{literal}
<script>
    $(document).ready(function () {
        var crudServiceBaseUrl = "{/literal}{$requestprotocol}://{$linkurl}Admin/Maintenance/Cache/getUseragents{literal}",
                dataSource = new kendo.data.DataSource({
                    transport: {
                        read:  {
                            url: crudServiceBaseUrl + "/get",
                            dataType: "json",
                            type: "POST"
                        },

                        parameterMap: function(data, operation) {
                            if (operation !== "read" && data.models) {
                                return {models: kendo.stringify(data.models)};
                            } else {
                                return {fields: kendo.stringify(data)};
                            }
                        }
                    },
                    pageSize: 500,
                    sort: { field: "user_agent", dir: "desc" },
                    schema: {
                        errors: function(response) {
                            if (response.Errors && response.Errors !== "OK") {
                                return response.Errors;
                            }
                            return false;
                        },
                        data: "data",
                        total: "total",
                        model: {
                            id: "id",
                            fields: {
                                user_agent: { validation: { editable: false } },
                                numused: { editable: false }
                            }
                        }
                    }
                });

        $("#gridUserAgents").kendoGrid({
            dataSource: dataSource,
            scrollable: true,
            groupable: true,
            sortable: true,
            filterable: true,
            /*pageable: true,*/
            pageable: {
                refresh: true
                /* pageSizes: [20,70,120],
                 buttonCount: 50 */
            },
            height: 500,
            columns: [
                {
                    field: "user_agent",
                    title: "User Agent",
                    width: 800,
                    filterable: true
                },
                {
                    field: "numused",
                    title: "Used"

                }
            ]
        });
    });
</script>
{/literal}

{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Footer.tpl"}
</body>
</html>