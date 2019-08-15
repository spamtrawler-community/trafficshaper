<!DOCTYPE html>
<!--[if IE 9]>
<html class="lt-ie10" lang="en"> <![endif]-->
<html class="no-js" lang="en">
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Head.tpl"}
<body>
<div class="row">
    <div class="small-3 columns">
        <input type="text" id="query_asnblacklist" class="radius" value="" placeholder="Search">
    </div>
    <div class="small-3 columns">
        <select id="column_unblocked" class="radius">
            <option value="asn">ASN</option>
            <option value="comment">Comment</option>
        </select>
    </div>
    <div class="small-3 columns">
        <select id="operator_unblocked" class="radius">
            <option value="eq">Is equal to</option>
            <option value="neq">Is not equal to</option>
            <option value="startswith">Starts with</option>
            <option value="contains">Contains</option>
            <option value="endswith">Ends with</option>
        </select>
    </div>
    <div class="small-2 columns">
        <input type="button" id="btnFilter_Unblocked" class="k-button" value="Filter">
        <input type="button" id="btnReset_Unblocked" class="k-button" value="Reset">
    </div>
</div>
<div id="grid"></div>

{literal}
<script>
    $(document).ready(function () {
        var crudServiceBaseUrl = "{/literal}{$requestprotocol}://{$linkurl}Firewall/ASNBlacklist/Manage{literal}",
                dataSource = new kendo.data.DataSource({
                    error: function (e) {
                        if (e.errors !== false) {

                            //alert("Error: " + e.errors);
                            $(".k-edit-form-container").prepend("<p id='errorMessage' class='alert-box alert'>" + "Error: " + e.errors + "</p>");
                            $("#errorMessage").delay(3000).fadeOut(1600);

                            // This will keep the popup open
                            grid.one("dataBinding", function (e) {
                                e.preventDefault();   // cancel grid rebind
                            });
                        }
                    },
                    transport: {
                        read: {
                            url: crudServiceBaseUrl + "/get",
                            dataType: "json",
                            type: "POST"
                        },
                        update: {
                            url: crudServiceBaseUrl + "/update",
                            dataType: "json",
                            type: "POST"
                        },
                        destroy: {
                            url: crudServiceBaseUrl + "/destroy",
                            dataType: "json",
                            type: "POST"
                        },
                        create: {
                            url: crudServiceBaseUrl + "/create",
                            dataType: "json",
                            type: "POST"
                        },

                        parameterMap: function (data, operation) {
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
                    sort: {field: "updated", dir: "desc"},
                    schema: {
                        errors: function (response) {
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
                                id: {editable: false},
                                organization: {validation: {required: true}},
                                comment: {validation: {required: false}},
                                isregex: {type: "boolean"},
                                updated: {editable: false}
                            }
                        }
                    }
                });

        $("#grid").kendoGrid({
            dataSource: dataSource,
            scrollable: true,
            groupable: true,
            sortable: true,
            filterable: true,
            /*pageable: true,*/
            pageable: {
                refresh: true,
                /* pageSizes: [20,70,120],
                 buttonCount: 50 */
            },
            detailTemplate: kendo.template($("#commentTemplate").html()),
            height: 600,
            toolbar: ["create"],
            columns: [
                {
                    field: "asn",
                    title: "ASN"

                },
                {
                    field: "comment",
                    title: "Comment",
                    hidden: true,
                    editor: textareaEditor
                },
                {
                    field: "isregex",
                    title: "Regex",
                    width: 95
                },
                {
                    command: ["edit", "destroy"],
                    title: "&nbsp;",
                    width: 170,
                    attributes: {
                        style: "text-align: center"
                    }
                }],
            //editable: "popup"
            editable: {
                mode: "popup"
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

    <script type="text/x-kendo-template" id="commentTemplate">
        <div class="row">
            <div>Comment:</div>
            <div><textarea style="width: 93%;" rows="5" readonly>${ comment }</textarea></div>
        </div>
    </script>
{/literal}
</body>
</html>