<hr />
<div class="row">
    <div class="small-4 columns">
        <input type="text" id="query_asnwhitelist" class="radius" value="" placeholder="Search">
    </div>
    <div class="small-2 columns">
        <select id="column_asnwhitelist" class="radius">
            <option value="asn">ASN</option>
            <option value="comment">Comment</option>
        </select>
    </div>
    <div class="small-2 columns">
        <select id="operator_asnwhitelist" class="radius">
            <option value="eq">Is equal to</option>
            <option value="neq">Is not equal to</option>
            <option value="startswith">Starts with</option>
            <option value="contains">Contains</option>
            <option value="endswith">Ends with</option>
        </select>
    </div>
    <div class="small-2 columns">
        <input type="button" id="btnFilter_asnwhitelist" class="k-button" value="Filter">
        <input type="button" id="btnReset_asnwhitelist" class="k-button" value="Reset">
    </div>
</div>

<div id="gridASNWhitelist"></div>

        {literal}
        <script>
            $(document).ready(function () {
                var crudServiceBaseUrl = "{/literal}{$requestprotocol}://{$linkurl}Firewall/ASNWhitelist/Manage{literal}",
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
                                        $("#gridASNWhitelist").data("kendoGrid").dataSource.read();
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
                                        organization: { validation: { required: true }},
                                        comment: { validation: { required: false } },
                                        updated: { editable: false }
                                    }
                                }
                            }
                        });

                $("#gridASNWhitelist").kendoGrid({
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
                    detailTemplate: kendo.template($("#commentTemplateASNWhitelist").html()),
                    toolbar: ["create"],
                    columns: [
                        {
                            field: "asn",
                            title: "ASN"

                        },
                        {
                            field: "filter_mode",
                            title: "Mode",
                            width: 95,
                            editor: filtermodeDropDownEditor,
                            attributes: {
                                style: "text-transform: capitalize"
                            }
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

                $("#btnFilter_asnwhitelist").on("click", function(e) {
                    var ds = $("#gridASNWhitelist").data("kendoGrid").dataSource;
                    ds.filter([{
                        "field": $("#column_asnwhitelist").val(),
                        "operator": $("#operator_asnwhitelist").val(),
                        "value": $("#query_asnwhitelist").val()}
                    ]);
                });

                $("#btnReset_asnwhitelist").on("click", function(e) {
                    $("#query_asnwhitelist").val('');
                    var ds = $("#gridASNWhitelist").data("kendoGrid").dataSource;
                    ds.filter([]);
                });
            });

            /* Drop Down for filter_mode */
            function filtermodeDropDownEditor(container, options){
                var data = [
                    { filter_mode: "choose", filter_descriptive: "Please Choose" },
                    { filter_mode: "exact", filter_descriptive: "Exact" },
                    { filter_mode: "contains", filter_descriptive: "Contains" },
                    { filter_mode: "regex", filter_descriptive: "Regex" }
                ];

                $('<input data-value-primitive="true" data-bind="value:' + options.field + '"/>')
                        .appendTo(container)
                        .kendoDropDownList({
                            dataTextField: "filter_descriptive",
                            dataValueField: "filter_mode",
                            dataSource: data
                        });
            }

            /* Text Area for comments in popup editor */
            function textareaEditor(container, options) {
                $('<textarea data-bind="value: ' + options.field + '" style="width: 93%" rows="4"></textarea>')
                        .appendTo(container);
            }
            </script>

<!-- popup editor template -->
    <script id="popup_editorASNWhitelist" type="text/x-kendo-template">
       <div id="errorsASNWhitelist">Shows Errors here!</div>
    </script>

        <script type="text/x-kendo-template" id="commentTemplateASNWhitelist">
            <div class="row">
                <div>Comment:</div>
                <div><textarea style="width: 93%;" rows="5" readonly>${ comment }</textarea></div>
            </div>
        </script>
        {/literal}