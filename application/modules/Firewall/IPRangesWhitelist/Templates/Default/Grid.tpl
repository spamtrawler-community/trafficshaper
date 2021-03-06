<hr />
<div class="row">
    <div class="small-4 columns">
        <input type="text" id="query_iprangeswhitelist" class="radius" value="" placeholder="Search">
    </div>
    <div class="small-2 columns">
        <select id="column_iprangeswhitelist" class="radius">
            <option value="range_start">Range Start</option>
            <option value="range_end">Range End</option>
            <option value="comment">Comment</option>
        </select>
    </div>
    <div class="small-2 columns">
        <select id="operator_iprangeswhitelist" class="radius">
            <option value="eq">Is equal to</option>
            <option value="neq">Is not equal to</option>
            <option value="startswith">Starts with</option>
            <option value="contains">Contains</option>
            <option value="endswith">Ends with</option>
        </select>
    </div>
    <div class="small-2 columns">
        <input type="button" id="btnFilter_iprangeswhitelist" class="k-button" value="Filter">
        <input type="button" id="btnReset_iprangeswhitelist" class="k-button" value="Reset">
    </div>
</div>

<div id="gridIPRangesWhitelist"></div>

        {literal}
        <script>
            $(document).ready(function () {
                var crudServiceBaseUrl = "{/literal}{$requestprotocol}://{$linkurl}Firewall/IPRangesWhitelist/Manage{literal}",
                        dataSource = new kendo.data.DataSource({
                            error : function (e) {
                                if (e.errors !== false) {

                                    //alert("Error: " + e.errors);
                                    $( ".k-edit-form-container" ).prepend( "<p id='errorMessage' class='alert-box alert'>" + "Error: " + e.errors + "</p>" );
                                    $( "#errorMessage" ).delay(3000) .fadeOut( 1600);

                                    // This will keep the popup open
                                    grid.one("dataBinding", function (e) {
                                        e.preventDefault();   // cancel grid rebind
                                    });
                                }
                            },
                            transport: {
                                read:  {
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
                                    type: "POST",
                                    complete: function (e) {
                                        $("#gridIPRangesWhitelist").data("kendoGrid").dataSource.read();
                                    }
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
                            pageSize: {/literal}{$config->gridsize}{literal},
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
                                        range_start: { validation: { required: true }},
                                        range_end: { validation: { required: true }},
                                        comment: { validation: { required: false } },
                                        updated: { editable: false }
                                    }
                                }
                            }
                        });

                $("#gridIPRangesWhitelist").kendoGrid({
                    dataSource: dataSource,
                    autoBind: false,
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
                    detailTemplate: kendo.template($("#commentTemplateIPRangesWhitelist").html()),
                    toolbar: ["create"],
                    columns: [
                        {
                            field: "range_start",
                            title: "Start IP"

                        },{
                            field: "range_end",
                            title: "End IP"

                        },
                        {
                            field: "comment",
                            title: "Comment",
                            hidden: true,
                            editor: textareaEditor
                        },
                        {   command: ["edit", "destroy"],
                            title: "&nbsp;",
                            width: 170,
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

                $("#btnFilter_iprangeswhitelist").on("click", function(e) {
                    var ds = $("#gridIPRangesWhitelist").data("kendoGrid").dataSource;
                    ds.filter([{
                        "field": $("#column_iprangeswhitelist").val(),
                        "operator": $("#operator_iprangeswhitelist").val(),
                        "value": $("#query_iprangeswhitelist").val()}
                    ]);
                });

                $("#btnReset_iprangeswhitelist").on("click", function(e) {
                    $("#query_iprangeswhitelist").val('');
                    var ds = $("#gridIPRangesWhitelist").data("kendoGrid").dataSource;
                    ds.filter([]);
                });
            });

            /* Text Area for comments in popup editor */
            function textareaEditor(container, options) {
                $('<textarea data-bind="value: ' + options.field + '" style="width: 93%" rows="4"></textarea>')
                        .appendTo(container);
            }
            </script>

<!-- popup editor template -->
    <script id="popup_editorIPRangesWhitelist" type="text/x-kendo-template">
       <div id="errorsIPRangesWhitelist">Shows Errors here!</div>
    </script>

        <script type="text/x-kendo-template" id="commentTemplateIPRangesWhitelist">
            <div class="row">
                <div>Comment:</div>
                <div><textarea style="width: 93%;" rows="5" readonly>${ comment }</textarea></div>
            </div>
        </script>
        {/literal}