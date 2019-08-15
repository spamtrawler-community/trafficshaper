<!-- Row for list grid -->
<div class="row space">
    <div class="small-12 columns">
        <span>
            <strong>Captcha Solved</strong>
        </span>

        <div id="visitorscaptchasolved"></div>
    </div>

    {literal}
    <script>
        $(document).ready(function () {
            var crudServiceBaseUrl = "{/literal}{$requestprotocol}://{$linkurl}Feeds/Visitors/ManageCaptchaSolved{literal}",
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
                                    country_code: { editable: false },
                                    ip: { editable: false },
                                    host_name: { editable: false },
                                    comment: { validation: { required: false } },
                                    updated: { editable: false }
                                }
                            }
                        }
                    });

            $("#visitorscaptchasolved").kendoGrid({
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
                detailTemplate: kendo.template($("#CaptchaVisitorsDetailsTemplate").html()),
                height: 600,
                columns: [
                    {
                        field: "country_code",
                        title: "&nbsp;",
                        template: "<div class='flag flag-${ country_code }' title='${ country_name } (${ country_code })'><span>${ country_code }</span></div>",
                        width: 30,
                        filterable: false
                    },
                    {
                        field: "ip",
                        title: "IP"

                    },
                    {
                        field: "host_name",
                        title: "Hostname"

                    },
                    {
                        field: "updated",
                        title: "Last Logged",
                        width: 170
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

    <script type="text/x-kendo-template" id="CaptchaVisitorsDetailsTemplate">
        <div class="row">
            <div class="small-2 columns">Country Name:</div>
            <div class="small-9 columns left">${ country_name }</div>
        </div>
        <div class="row">
            <div class="small-2 columns">ASN:</div>
            <div class="small-9 columns left">${ asn }</div>
        </div>
        <div class="row">
            <div class="small-2 columns">ASN Organization:</div>
            <div class="small-9 columns left">${ asn_org }</div>
        </div>
        <div class="row">
            <div class="small-2 columns">User Agent:</div>
            <div class="small-9 columns left">${ user_agent }</div>
        </div>
        <div class="row">
            <div class="small-2 columns">Device Type:</div>
            <div class="small-9 columns left">${ device_type }</div>
        </div>
        <div class="row">
            <div class="small-2 columns">Username:</div>
            <div class="small-9 columns left">${ username }</div>
        </div>
        <div class="row">
            <div class="small-2 columns">Email:</div>
            <div class="small-9 columns left">${ email }</div>
        </div>
        <div class="row">
            <div class="small-2 columns">URL:</div>
            <div class="small-9 columns left">${ url }</div>
        </div>
        <div class="row">
            <div class="small-2 columns">Comment:</div>
            <div class="small-9 columns left"><textarea style="width: 93%;" rows="5" readonly>${ comment }</textarea></div>
        </div>
    </script>
    {/literal}
</div>
