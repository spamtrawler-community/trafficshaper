<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" >
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Head.tpl"}
<body>
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Header.tpl"}
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/MainNav.tpl"}

<div class="row panel">
    <div class="small-12 columns">
        <div class="small-3 columns">
            <select id="extension" name="extension" class="radius">
                <option value="all">All Files</option>
                <option value="php">PHP Files</option>
                <option value="js">JavaScript Files</option>
                <option value="html">HTML Files</option>
                <option value="py">Python Files</option>
                <option value="pl">Perl Files</option>
                <option value="cgi">CGI Files</option>
                <option value="txt">Text Files</option>
                <option value="Directory">Directories</option>
            </select>
        </div>
        <div class="small-2 columns left">
            <input type="button" id="btnCheckPermissions" class="button tiny radius" value="Check">
        </div>
    </div>
    <div class="small-12 columns">
        <div class="row collapse">
            <div class="small-12 columns" id="resultCheckPermissions"></div>
        </div>
    </div>
</div>

{literal}
<script>
    $(function () {
        var btn = $('#btnCheckPermissions');

        btn.click(function () {
            var extension = $('#extension');
            if(!extension){
             alertify.alert('Please choose an extension!');
            } else {
                $('#btnFindFiles').val("Permission check in progress...");
                $('#ResultFindFiles').html('');
                showGrid(extension.val());
            }
        });
    });
</script>

    <script>
        function showGrid ( extension ) {
            var crudServiceBaseUrl = "{/literal}{$requestprotocol}://{$linkurl}Filesystem/CheckPermissions/View{literal}",
                    dataSource = new kendo.data.DataSource({
                        transport: {
                            read:  {
                                url: crudServiceBaseUrl + "/find{/literal}{$urlparameterglue}{literal}extension=" + extension,
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
                        sort: { field: "updated", dir: "desc" },
                        schema: {
                            errors: function(response) {
                                if (response.Errors && response.Errors !== "OK") {
                                    alert(response.Errors);
                                    //return response.Errors;
                                }
                                return false;
                            },
                            data: "data",
                            total: "total",
                            model: {
                                id: "id",
                                fields: {
                                    id: { editable: false },
                                    fullPath: { editable: false },
                                    filename: { editable: false },
                                    extension: { editable: false },
                                    lastModified: { editable: false },
                                    ago: { editable: false },
                                    permissions: { editable: false }
                                }
                            }
                        }
                    });

            $("#resultCheckPermissions").kendoGrid({
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
                detailTemplate: kendo.template($("#DetailsTemplate").html()),
                height: 498,
                columns: [
                    {
                        field: "id",
                        title: "",
                        hidden: true
                    },
                    {
                        field: "fullPath",
                        title: "Path",
                        hidden: true
                    },
                    {
                        field: "filename",
                        title: "Name"
                    },
                    {
                        field: "extension",
                        title: "Type",
                        width: 100
                    },
                    {
                        field: "lastModified",
                        title: "Modified",
                        width: 160
                    },
                    {
                        field: "ago",
                        title: "Ago",
                        width: 130
                    },
                    {
                        field: "permissions",
                        title: "CHMOD",
                        width: 100
                    }
                ]
            });

            $('#btnFindFiles').val("Find");
        }
    </script>
<!-- Details -->
    <script type="text/x-kendo-template" id="DetailsTemplate">
        <div class="row collapse">
            <div class="small-12 columns text-left">
                <div class="small-1 columns">Filetype:</div>
                <div class="small-10 columns left">${ extension }</div>
            </div>
            <div class="small-12 columns text-left">
                <div class="small-1 columns">Owner:</div>
                <div class="small-10 columns left">${ owner }</div>
            </div>
            <div class="small-12 columns text-left">
                <div class="small-1 columns">Group:</div>
                <div class="small-10 columns left">${ group }</div>
            </div>
            <div class="small-12 columns text-left">
                <div class="small-1 columns">Inode:</div>
                <div class="small-10 columns left">${ inode }</div>
            </div>
            <div class="small-12 columns text-left">
                <div class="small-1 columns">Size:</div>
                <div class="small-10 columns left">${ size }</div>
            </div>
            <div class="small-12 columns text-left">
                <div class="small-1 columns"><span data-tooltip aria-haspopup="true" class="has-tip" title="Time at which content has been modified last">Modified:</span></div>
                <div class="small-10 columns left">${ lastModified }</div>
            </div>
            <div class="small-12 columns text-left">
                <div class="small-1 columns"><span data-tooltip aria-haspopup="true" class="has-tip" title="Time at which file has been created/copied/moved on local file system last">Changed:</span></div>
                <div class="small-10 columns left">${ lastChanged }</div>
            </div>
            <div class="small-12 columns text-left">
                <div class="small-1 columns">Path:</div>
                <div class="small-9 columns left">
                    <textarea id="txt${ id }" rows="1" readonly>${ fullPath }</textarea>
                </div>
            </div>
        </div>
    </script>
    {/literal}
    {include file="file:{$path_modules}/Admin/Templates/{$core_template}/Footer.tpl"}
</body>
</html>