<div class="row">
    <div class="small-12 columns">
        <h3>Blocked Visitors</h3>
    </div>
    <div class="small-3 columns">
        <input type="text" id="query_blocked" class="radius" value="" placeholder="Search">
    </div>
    <div class="small-3 columns">
        <select id="column_blocked" class="radius">
            <option value="ip">IP</option>
            <option value="host_name">Host Name</option>
            <option value="asn">ASN</option>
            <option value="asn_org">Organization</option>
            <option value="user_agent">Useragent</option>
            <option value="device_type">Device Type</option>
            <option value="country_code">Country Code</option>
            <option value="country_name">Country Name</option>
            <option value="comment">Comment</option>
            <option value="username">Username</option>
            <option value="email">Email</option>
            <option value="url">Page</option>
            <option value="referrer">Referrer</option>
            <option value="block_reason">Block Reason</option>
        </select>
    </div>
    <div class="small-3 columns">
        <select id="operator_blocked" class="radius">
            <option value="eq">Is equal to</option>
            <option value="neq">Is not equal to</option>
            <option value="startswith">Starts with</option>
            <option value="contains">Contains</option>
            <option value="endswith">Ends with</option>
        </select>
    </div>
    <div class="small-2 columns">
        <input type="button" id="btnFilter_Blocked" class="k-button" value="Filter">
        <input type="button" id="btnReset_Blocked" class="k-button" value="Reset">
    </div>
    <div class="small-12 columns" style="min-height: 550px;">
        <span id="visitorsblocked"></span>
    </div>
    <div class="small-12 columns space"></div>
    <hr />
</div>


{literal}
<script>
    $(document).ready(function () {
        var crudServiceBaseUrl = "{/literal}{$requestprotocol}://{$linkurl}Feeds/Visitors/ManageBlocked{literal}",
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

        $("#visitorsblocked").kendoGrid({
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
            detailTemplate: kendo.template($("#BlockedVisitorsDetailsTemplate").html()),
            height: 500,
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
                    field: "block_reason",
                    title: "Block Reason"

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
                {field: "", title: " ", template: "<a id=\'edit${ id }\' class=\'k-button k-button-icontext k-grid-edit\' href=\'\'); return false;\"><span class=\'k-icon k-edit\'></span>Edit</a><a id=\'delete${ id }\' class=\'k-button k-button-icontext k-grid-delete\' href=\'\'); return false;\"><span class=\'k-icon k-delete\'></span>Delete</a>", width: 170, filterable: false}
                ],
            //editable: "popup"
            editable: { mode: "popup"
                /*template: $("#popup_editor").html()*/
            }
        });

        $("#btnFilter_Blocked").on("click", function(e) {
            var ds = $("#visitorsblocked").data("kendoGrid").dataSource;
            ds.filter([{
                "field": $("#column_blocked").val(),
                "operator": $("#operator_blocked").val(),
                "value": $("#query_blocked").val()}
            ]);
        });

        $("#btnReset_Blocked").on("click", function(e) {
            $("#query_blocked").val('');
            var ds = $("#visitorsblocked").data("kendoGrid").dataSource;
            ds.filter([]);
        });
    });

    /* Text Area for comments in popup editor */
    function textareaEditor(container, options) {
        $('<textarea data-bind="value: ' + options.field + '" style="width: 93%" rows="4"></textarea>')
                .appendTo(container);
    }

    /*
     var UnblockedGrid = $("#visitorsblocked").data("kendoGrid");
     var dataSource = UnblockedGrid.dataSource;
     var totalRecords = dataSource.total();
     alert(totalRecords);
     if(totalRecords == 0){
     $("#visitorsblockedgridholder").hide();
     }
     */
</script>

    <!-- popup editor template -->
    <script id="popup_editor" type="text/x-kendo-template">
        <div id="errors">Shows Errors here!</div>
    </script>

    <script type="text/x-kendo-template" id="BlockedVisitorsDetailsTemplate">
        <div class="panel">
            <div class="row griddetailrow">
                <div class="small-2 columns"><strong>IP:</strong></div>
                <div class="small-7 left">${ ip }</div>
                <div class="small-2 columns text-right">
                    <span class="radius alert label label-blacklist" onClick="getByAjax('{/literal}{$requestprotocol}://{$linkurl}{literal}Firewall/AntiWebSpam/Report?val=${ ip }', 0); return false;"><i class="fi-upload-cloud" title="Report to AntiWebSpam"></i></span>
                    <span class="radius alert label label-blacklist" onClick="getByAjax('{/literal}{$requestprotocol}://{$linkurl}{literal}Firewall/IPBlacklist/Manage/create?ip=${ ip }', 0);$('\#delete${ id }').click(); return false;"><i class="fi-prohibited" title="Blacklist"></i></span>
                    <span class="radius success label label-whitelist" onClick="getByAjax('{/literal}{$requestprotocol}://{$linkurl}{literal}Firewall/IPWhitelist/Manage/create?ip=${ ip }', 0);$('\#delete${ id }').click(); return false;"><i class="fi-like" title="Whitelist"></i></span></div>
            </div>
            <div class="row griddetailrow">
                <div class="small-2 columns"><strong>Country Name:</strong></div>
                <div class="small-7 left">${ country_name }</div>
                <div class="small-2 columns text-right">
                    <span class="radius alert label label-blacklist" onClick="getByAjax('{/literal}{$requestprotocol}://{$linkurl}{literal}Firewall/CountryBlacklist/Manage/create?iso=${ country_code }', 0);$('\#delete${ id }').click(); return false;"><i class="fi-prohibited" title="Blacklist"></i></span>
                    <span class="radius success label label-whitelist" style="visibility: hidden;"><i class="fi-like" title="Whitelist"></i></span>
                </div>
            </div>
            # if (host_name !== ''){ #
            <div class="row griddetailrow">
                <div class="small-2 columns"><strong>Hostname:</strong></div>
                <div class="small-7 left"><textarea class="detailtextarea" cols="200">${ host_name }</textarea></div>
                <div class="small-2 columns text-right">
                    <span class="radius alert label label-blacklist" onClick="getByAjax('{/literal}{$requestprotocol}://{$linkurl}{literal}Firewall/HostnameBlacklist/Manage/create?hostname=${ host_name }', 0);$('\#delete${ id }').click(); return false;"><i class="fi-prohibited" title="Blacklist"></i></span>
                    <span class="radius success label label-whitelist" onClick="getByAjax('{/literal}{$requestprotocol}://{$linkurl}{literal}Firewall/HostnameWhitelist/Manage/create?hostname=${ host_name }', 0);$('\#delete${ id }').click(); return false;"><i class="fi-like" title="Whitelist"></i></span>
                </div>
            </div>
            # } #
            # if (asn !== ''){ #
            <div class="row griddetailrow">
                <div class="small-2 columns"><strong>ASN:</strong></div>
                <div class="small-7 left">${ asn }</div>
                <div class="small-2 columns text-right">
                    <span class="radius alert label label-blacklist" onClick="getByAjax('{/literal}{$requestprotocol}://{$linkurl}{literal}Firewall/ASNBlacklist/Manage/create?asn=${ asn }', 0);$('\#delete${ id }').click(); return false;"><i class="fi-prohibited" title="Blacklist"></i></span>
                    <span class="radius success label label-whitelist" onClick="getByAjax('{/literal}{$requestprotocol}://{$linkurl}{literal}Firewall/ASNWhitelist/Manage/create?asn=${ asn }', 0);$('\#delete${ id }').click(); return false;"><i class="fi-like" title="Whitelist"></i></span>
                </div>
            </div>
            # } #
            # if (asn_org !== ''){ #
            <div class="row griddetailrow">
                <div class="small-2 columns"><strong>ASN Organization:</strong></div>
                <div class="small-7 left">${ asn_org }</div>
                <div class="small-2 columns text-right">
                    <span class="radius alert label label-blacklist" onClick="getByAjax('{/literal}{$requestprotocol}://{$linkurl}{literal}Firewall/OrganizationBlacklist/Manage/create?organization=${ asn_org }', 0);$('\#delete${ id }').click(); return false;"><i class="fi-prohibited" title="Blacklist"></i></span>
                    <span class="radius success label label-whitelist" onClick="getByAjax('{/literal}{$requestprotocol}://{$linkurl}{literal}Firewall/OrganizationWhitelist/Manage/create?organization=${ asn_org }', 0);$('\#delete${ id }').click(); return false;"><i class="fi-like" title="Whitelist"></i></span>
                </div>
            </div>
            # } #
            # if (user_agent !== ''){ #
            <div class="row griddetailrow">
                <div class="small-2 columns"><strong>User Agent:</strong></div>
                <div class="small-7 left"><textarea class="detailtextarea" cols="200">${ user_agent }</textarea></div>
                <div class="small-2 columns text-right">
                    <span class="radius alert label label-blacklist" onClick="getByAjax('{/literal}{$requestprotocol}://{$linkurl}{literal}Firewall/UseragentBlacklist/Manage/create?useragent=${ user_agent }', 0);$('\#delete${ id }').click(); return false;"><i class="fi-prohibited" title="Blacklist"></i></span>
                    <span class="radius success label label-whitelist" style="visibility: hidden;"><i class="fi-like" title="Whitelist"></i></span>
                </div>
            </div>
            # } #
            <div class="row griddetailrow">
                <div class="small-2 columns"><strong>Device Type:</strong></div>
                <div class="small-7 left">${ device_type }</div>
                <div class="small-2 columns text-right">
                    <span class="radius alert label label-whitelist" style="visibility: hidden;"><i class="fi-prohibited" title="Blacklist"></i></span>
                    <span class="radius success label label-whitelist" style="visibility: hidden;"><i class="fi-like" title="Whitelist"></i></span>
                </div>
            </div>
            # if (username !== '--'){ #
            <div class="row griddetailrow">
                <div class="small-2 columns"><strong>Username:</strong></div>
                <div class="small-7 left">${ username }</div>
                <div class="small-2 columns text-right">
                    <span class="radius alert label label-blacklist" onClick="getByAjax('{/literal}{$requestprotocol}://{$linkurl}{literal}Firewall/UsernameBlacklist/Manage/create?username=${ username }', 0);$('\#delete${ id }').click(); return false;"><i class="fi-prohibited" title="Blacklist"></i></span>
                    <span class="radius success label label-whitelist" style="visibility: hidden;"><i class="fi-like" title="Whitelist"></i></span>
                </div>
            </div>
            # } #
            # if (email !== '--'){ #
            <div class="row griddetailrow">
                <div class="small-2 columns"><strong>Email:</strong></div>
                <div class="small-7 left">${ email }</div>
                <div class="small-2 columns text-right">
                    <span class="radius alert label label-blacklist" onClick="getByAjax('{/literal}{$requestprotocol}://{$linkurl}{literal}Firewall/AntiWebSpam/Report?val=${ email }', 0); return false;"><i class="fi-upload-cloud" title="Report to AntiWebSpam"></i></span>
                    <span class="radius alert label label-blacklist" onClick="getByAjax('{/literal}{$requestprotocol}://{$linkurl}{literal}Firewall/EmailBlacklist/Manage/create?email=${ email }', 0);$('\#delete${ id }').click(); return false;"><i class="fi-prohibited" title="Blacklist"></i></span>
                    <span class="radius success label label-whitelist" onClick="getByAjax('{/literal}{$requestprotocol}://{$linkurl}{literal}Firewall/EmailWhitelist/Manage/create?email=${ email }', 0);$('\#delete${ id }').click(); return false;"><i class="fi-like" title="Whitelist"></i></span>
                </div>
            </div>
            # } #
            <div class="row griddetailrow">
                <div class="small-2 columns"><strong>URL:</strong></div>
                <div class="small-7 left"><textarea class="detailtextarea" cols="200">${ url }</textarea></div>
                <div class="small-2 columns text-right">
                    <span class="radius alert label label-blacklist" style="visibility: hidden;"><i class="fi-prohibited" title="Blacklist"></i></span>
                    <span class="radius success label label-whitelist" onClick="getByAjax('{/literal}{$requestprotocol}://{$linkurl}{literal}Firewall/URLWhitelist/Manage/create?url=${ url }', 0);$('\#delete${ id }').click(); return false;"><i class="fi-like" title="Whitelist"></i></span>
                </div>
            </div>
            # if (referrer !== ''){ #
            <div class="row griddetailrow">
                <div class="small-2 columns"><strong>Referrer:</strong></div>
                <div class="small-7 left"><textarea class="detailtextarea" cols="200">${ referrer }</textarea></div>
                <div class="small-2 columns text-right">
                    <span class="radius alert label label-blacklist" onClick="getByAjax('{/literal}{$requestprotocol}://{$linkurl}{literal}Firewall/ReferrerBlacklist/Manage/create?referrer=${ referrer }', 0);$('\#delete${ id }').click(); return false;"><i class="fi-prohibited" title="Blacklist"></i></span>
                    <span class="radius success label label-whitelist" style="visibility: hidden;"><i class="fi-like" title="Whitelist"></i></span>
                </div>
            </div>
            # } #
            <div class="row griddetailrow">
                <div class="small-2 columns"><strong>Comment:</strong></div>
                <div class="small-9 columns left"><textarea style="width: 93%;" rows="2" readonly>${ comment }</textarea></div>
            </div>
        </div>
    </script>
{/literal}