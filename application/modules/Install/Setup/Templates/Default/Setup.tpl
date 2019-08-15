<!DOCTYPE html>
<!--[if IE 9]>
<html class="lt-ie10" lang="en"> <![endif]-->
<html class="no-js" lang="en">
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Head.tpl"}
<body>
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Header.tpl"}
{include file="file:{$path_modules}/Install/Setup/Templates/{$config->template}/Top.tpl"}
<div class="row">
    <div class="panel">
        <div class="row">
            <div id="errors" data-alert="" class="alert-box alert radius text-center" style="display: none;"></div>
        </div>
        <script>
            $(document).ready(function () {
                checkRequirements();
            });

            function checkRequirements() {
                $.get("{$requestprotocol}://{$linkurl}Install/Setup/Steps/checkRequirements", function (data) {
                            if (data == 'ok') {
                                $("#errors").hide();
                                //$("#divLicensekey").show();
                                checkPermissions();
                            } else {
                                $("#errors").html(data + '<br /><span id="btnCheckRequirements" class="k-button" onclick="checkRequirements();">Check Requirements</span>').show();
                            }
                        })
                        .fail(function () {
                            $("#errors").html('Server unreachable or session expired!').show().delay(5000).fadeOut("slow");
                        })
            }

            function checkPermissions() {
                $.get("{$requestprotocol}://{$linkurl}Install/Setup/Steps/checkPermissions", function (data) {
                            if (data == 'ok') {
                                $("#errors").hide();
                                $("#dbDetails").show();
                            } else {
                                $("#errors").html(data + '<br /><span id="btnCheckPermissions" class="k-button" onclick="checkPermissions();">Check Permissions</span>').show();
                            }
                        })
                        .fail(function () {
                            $("#errors").html('Server unreachable or session expired!').show().delay(5000).fadeOut("slow");
                        })
            }

        </script>
        <!-- Database Details -->
        <form id="frmDbDetails" action="{$requestprotocol}://{$linkurl}Install/Setup/Steps/checkDatabaseCredentials"
              method="POST">
            <div id="dbDetails" style="display: none;">
                <div class="row">
                    <div class="small-3 columns">
                        <label for="dbname" class="right"><span title="Type in the name of the database to be used by SpamTrawler">Database Name</span></label>
                    </div>
                    <div class="small-8 columns left">
                        <input type="text" id="dbname" name="dbname" value="">
                    </div>
                </div>
                <div class="row">
                    <div class="small-3 columns">
                        <label for="dbuser" class="right"><span title="Type in the username for the database to be used by SpamTrawler">Database Username</span></label>
                    </div>
                    <div class="small-8 columns left">
                        <input type="text" id="dbuser" name="dbuser" value="">
                    </div>
                </div>
                <div class="row">
                    <div class="small-3 columns">
                        <label for="dbpass" class="right"><span title="Type in the password for the database to be used by SpamTrawler">Database Password</span></label>
                    </div>
                    <div class="small-8 columns left">
                        <input type="text" id="dbpass" name="dbpass" value="">
                    </div>
                </div>
                <div class="row">
                    <div class="small-3 columns">
                        <label for="dbhost" class="right"><span title="Type in the hostname for the database server to be used by SpamTrawler">Database Host</span></label>
                    </div>
                    <div class="small-8 columns left">
                        <input type="text" id="dbhost" name="dbhost" value="localhost">
                    </div>
                </div>
                <div class="row">
                    <div class="small-3 columns">
                        <label for="tblprefix" class="right"><span title="Prefix to be used for database tables">Table Prefix</span></label>
                    </div>
                    <div class="small-8 columns left">
                        <input type="text" id="tblprefix" name="tblprefix" value="spamtrawler">
                    </div>
                </div>

                <div class="row">
                    <div class="small-3 columns">
                        <label for="dbadapter" class="right"><span title="Database driver to be used">Adapter</span></label>
                    </div>
                    <div class="small-8 columns left">
                        <select id="dbadapter" name="dbadapter">
                            {foreach from=$DbDrivers key=k item=v}
                                <option value="{$v[0]}">{$v[1]}</option>
                            {/foreach}
                        </select>
                    </div>
                </div>
                <div class="row">
                    <div class="small-3 columns">
                        <label for="dbport" class="right"><span title="Port on which the database server is operating">Port</span></label>
                    </div>
                    <div class="small-8 columns left">
                        <input type="text" id="dbport" name="dbport" value="3306">
                    </div>
                </div>
                <!-- Check License Key -->
                <div class="row">
                    <div class="small-11 columns text-right">
                        <span id="btnCheckDatabaseAccess" class="k-button">Create Database Structure</span>
                    </div>
        </form>

        {literal}
            <script>
                // Attach a submit handler to the form
                $("#btnCheckDatabaseAccess").bind("click", function (event) {

                    // Stop form from submitting normally
                    event.preventDefault();

                    //Display Loading Mask
                    displayLoading();

                    var $form = $("#frmDbDetails"),
                            url = $form.attr("action");

                    // Send the data using post
                    var posting = $.post(url, $($form).serialize());

                    // Put the results in a div
                    posting.done(function (data) {
                        if (data !== 'ok') {
                            alertify.alert(data);
                        } else {
                            $("#dbDetails").hide();
                            $("#adminDetails").show();
                        }

                        //Hide Loading Mask
                        hideLoading();
                    });
                });
            </script>
        {/literal}
        <!-- End Check License Key -->
    </div>
</div>

<!-- Admin Details -->
<form id="frmAdminDetails" action="{$requestprotocol}://{$linkurl}Install/Setup/Steps/addAdminUser" method="POST">
    <div id="adminDetails" style="display: none;">
        <div class="row">
            <div class="small-3 columns">
                <label for="adminuser" class="right"><span title="Type in the username for the administrative account">Admin Username</span></label>
            </div>
            <div class="small-8 columns left">
                <input type="text" id="adminuser" name="adminuser" value="">
            </div>
        </div>
        <div class="row">
            <div class="small-3 columns">
                <label for="adminpass" class="right"><span title="Type in the password for the administrative account">Admin Password</span></label>
            </div>
            <div class="small-8 columns left">
                <input type="text" id="adminpass" name="adminpass" value="">
            </div>
            <div class="small-1 columns left">
                <i class="fi-loop medium" id="iGenerateAdminpass"></i>
                <script>
                    $("#iGenerateAdminpass").bind("click", function () {
                        $.get("{$requestprotocol}://{$linkurl}Install/Setup/Steps/generatePassword", function (data) {
                                    $("#adminpass").val(data);
                                })
                                .fail(function () {
                                    $("#errors").html('Server unreachable or session expired!').show().delay(5000).fadeOut("slow");
                                })
                    });
                </script>
            </div>
        </div>
        <div class="row">
            <div class="small-3 columns">
                <label for="adminemail" class="right"><span title="Type in the email address for the administrative account">Admin Email</span></label>
            </div>
            <div class="small-8 columns left">
                <input type="text" id="adminemail" name="adminemail" value="">
            </div>
        </div>
        <div class="row">
            <div class="small-11 columns text-right">
                <span id="btnCreateAdmin" class="k-button">Create Admin User</span>
                {literal}
                    <script>
                        // Attach a submit handler to the form
                        $("#btnCreateAdmin").bind("click", function (event) {

                            // Stop form from submitting normally
                            event.preventDefault();

                            //Display Loading Mask
                            displayLoading();

                            // Get some values from elements on the page:
                            var $form = $("#frmAdminDetails"),
                                    url = $form.attr("action");

                            // Send the data using post
                            var posting = $.post(url, $($form).serialize());

                            // Put the results in a div
                            posting.done(function (data) {
                                if (data !== 'ok') {
                                    alertify.alert(data);
                                } else {
                                    $("#adminDetails").hide();
                                    $("#loginadminpanel").show();
                                }

                                //Hide Loading Mask
                                hideLoading();
                            });
                        });
                    </script>
                {/literal}
            </div>

        </div>
    </div>
</form>
<!-- End AdminDetails -->
<!-- Go to admin panel -->
<div id="loginadminpanel" style="display: none;">
    <div class="row">
        <div class="small-12 columns left text-center">
            <a href="{$requestprotocol}://{$linkurl}Admin/Dashboard/View" class="button success radius">Login to Admin
                Panel</a>
        </div>
    </div>
</div>
<!-- End go to admin panel -->
</div>
</div>
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Footer.tpl"}
</body>
</html>
