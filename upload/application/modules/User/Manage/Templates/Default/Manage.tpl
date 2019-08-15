<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" >
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Head.tpl"}
<body>
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Header.tpl"}
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/MainNav.tpl"}
<!-- Row for list grid -->
<div class="row space">
    <div class="small-12 columns">
        <div id="grid"></div>
    </div>
    {literal}
    <script>
        $(document).ready(function () {
            var crudServiceBaseUrl = "{/literal}{$requestprotocol}://{$linkurl}User/Manage/Manage{literal}",
                    dataSource = new kendo.data.DataSource({
                        error : function (e) {
                            if (e.errors !== false) {

                                //alert("Error: " + e.errors);
                                $( ".k-edit-form-container" ).prepend( "<div id='errorMessage' class='alert-box alert'>" + e.errors + "</div>" );
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
                                    username: { editable: true},
                                    email: {editable: true},
                                    password: { validation: { required: false } },
                                    comment: { validation: { required: false } },
                                    updated: { editable: false },
                                    twofactor: { type: "boolean" }
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
                    refresh: true
                    /* pageSizes: [20,70,120],
                     buttonCount: 50 */
                },
                detailTemplate: kendo.template($("#DetailsTemplate").html()),
                toolbar: ["create"],
                height: 600,
                columns: [
                    {
                        field: "username",
                        title: "Username",
                        width: 50,
                        groupable: false
                    },
                    {
                        field: "password",
                        title: "Password",
                        hidden: true
                    },
                    {
                        field: "twofactor",
                        title: "TwoFactor Auth",
                        hidden: true
                    },
                    {
                        field: "group_name",
                        title: "Group",
                        width: 50,
                        editor: usergroupDropDownEditor
                    },
                    {
                        field: "email",
                        title: "Email",
                        width: 80
                    },
                    {
                        field: "comment",
                        title: "Comment",
                        hidden: true,
                        editor: textareaEditor
                    },
                    /*
                    {
                        field: "updated",
                        title: "Last Updated",
                        width: 60,
                        template: "#= kendo.toString(kendo.parseDate(updated, 'yyyy-MM-dd HH:mm'), 'MMM d yyyy hh:mm tt') #",
                        groupable: false
                    },
                    */

                    {   command: ["edit", "destroy"],
                        title: "&nbsp;",
                        width: 80,
                        attributes:
                        {
                            style:"text-align: right"
                        }
                    }],
                editable: {
                    mode: "popup",
                    //template: $("#EditorTemplate").html(),
                    update: true,
                    add:true,
                    destroy: true,

                    confirmation: "Are you sure you want to remove ?"
                }
                //editable: "popup",
                //template: $("#popup_editor").html()
                //editable: { mode: "popup"
                        /* template: $("#popup_editor").html()*/
                //}
            });
        });

        /* Drop Down for usergroup */
        function usergroupDropDownEditor(container, options){
            var data = {/literal}{$GroupDataSource}{literal};

            $('<input data-value-primitive="true" data-bind="value:' + options.field + '"/>')
                    .appendTo(container)
                    .kendoDropDownList({
                       dataTextField: "group_name",
                        dataValueField: "group_name",
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
    <script id="popup_editorgrid" type="text/x-kendo-template">
        <div id="errors">Shows Errors here!</div>
    </script>

    <script type="text/x-kendo-template" id="EditorTemplate">
        <div class="row">
            <div class="small-2 columns">User Group:</div>
            <div class="small-6 columns left"><select name="group_name"></select></div>
        </div>
        <div class="row">
            <div class="small-2 columns">Username:</div>
            <div class="small-6 columns left"><input type="text" name="username" value="${ username }"></div>
        </div>
        <div class="row">
            <div class="small-2 columns">Email:</div>
            <div class="small-6 columns left"><input type="text" name="email" value="${ email }"></div>
        </div>
        <div class="row">
            <div class="small-2 columns">Password:</div>
            <div class="small-6 columns left"><input type="text" name="password" value=""></div>
        </div>
        <div class="row">
            <div class="small-2 columns">Comment:</div>
            <div class="small-9 columns left"><textarea name="comment" style="width: 93%;" rows="5">${ comment }</textarea></div>
        </div>
    </script>

    <script type="text/x-kendo-template" id="DetailsTemplate">
        <div class="row">
            <div class="small-2 columns">Username:</div>
            <div class="small-9 columns left">${ username }</div>
        </div>
        <div class="row">
            <div class="small-2 columns">Email:</div>
            <div class="small-9 columns left">${ email }</div>
        </div>
        <div class="row">
            <div class="small-2 columns">Comment:</div>
            <div class="small-9 columns left"><textarea style="width: 93%;" rows="5">${ comment }</textarea></div>
        </div>
    </script>

    <script type="text/x-kendo-template" id="commentTemplate">
        <div class="row">
            <div>Comment:</div>
            <div><textarea style="width: 93%;" rows="5">${ comment }</textarea></div>
        </div>
    </script>
    {/literal}
</div>



{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Footer.tpl"}
</body>
</html>