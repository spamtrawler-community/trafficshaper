<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" >
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Head.tpl"}
<body>
<!-- Row for list grid -->
        <div id="notifications"></div>
    {literal}
    <script>
        $(document).ready(function () {
            var crudServiceBaseUrl = "{/literal}{$requestprotocol}://{$linkurl}Admin/Notifications/View{literal}",
                    dataSource = new kendo.data.DataSource({
                        transport: {
                            read:  {
                                url: crudServiceBaseUrl + "/get",
                                dataType: "json",
                                type: "POST"
                            },
                            destroy: {
                                url: crudServiceBaseUrl + "/destroy",
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
                        batch: true,
                        pageSize: 500,
                        serverPaging: true,
                        serverFiltering: true,
                        serverSorting: true,
                        sort: { field: "updated", dir: "desc" },
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
                                    id: { editable: false },
                                    subject: { editable: false },
                                    text: { editable: false },
                                    updated: { editable: false }
                                }
                            }
                        }
                    });

            $("#notifications").kendoGrid({
                dataSource: dataSource,
                //scrollable: true,
                groupable: true,
                sortable: true,
                filterable: true,
                /*pageable: true,*/
                pageable: {
                    refresh: true
                    /* pageSizes: [20,70,120],
                     buttonCount: 50 */
                },
                detailTemplate: kendo.template($("#NotificationsDetailsTemplate").html()),
                height: 498,
                columns: [
                    {
                        field: "subject",
                        title: "Subject",
                        filterable: true
                    },
                    {
                        field: "updated",
                        title: "Updated",
                        width: 170
                    },
                    {   command: ["destroy"],
                        title: "&nbsp;",
                        width: 100,
                        attributes:
                        {
                            style:"text-align: center"
                        }
                    }],
                //editable: "popup"
                editable: { mode: "popup"
                    /*template: $("#popup_editor").html()*/
                }
            });
        });

        /* Text Area for comments in popup editor */
        function textareaEditor(container, options) {
            $('<textarea data-bind="value: ' + options.field + '" style="width: 93%" rows="4"></textarea>')
                    .appendTo(container);
        }
    </script>

<!-- popup editor template -->
    <script id="popup_editor" type="text/x-kendo-template">
        <div id="errors">Shows Errors here!</div>
    </script>

    <script type="text/x-kendo-template" id="NotificationsDetailsTemplate">
        <div class="row">
            <textarea style="width: 95%;" rows="5" readonly>${ text }</textarea>
        </div>
    </script>
    {/literal}
<!--Kendo-->
<script src="//{$ressourceurl}/js/kendo/kendo.all.min.js"></script>
<!--/Kendo-->
</body>
</html>