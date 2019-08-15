<div class="sticky">
<nav class="top-bar" data-topbar>
    <ul class="title-area">
        <li class="name">
            <img src="//{$ressourceurl}/img/st_logo.png">
        </li>
        <!-- Remove the class "menu-icon" to get rid of menu icon. Take out "Menu" to just have icon alone -->
        <li class="toggle-topbar menu-icon"><a href="#"><span>Menu</span></a></li>
    </ul>

    <section class="top-bar-section">
        <!-- Right Nav Section -->
        <ul class="right">
            <li class="has-form">
                <a href="#" id="aShowNotifications"><i id="iShowNotifications" class="fi-mail notificationicon notificationinactive"></i></a>
            </li>

            <li class="has-dropdown">
                <a href="#">Account</a>
                <ul class="dropdown">
                    <li><a href="{$requestprotocol}://{$linkurl}Admin/Auth/Logout/logout">Logout</a></li>
                </ul>
            </li>
        </ul>

        <!-- Left Nav Section -->
        <ul class="left">
            <li>
            <a href="{$requestprotocol}://{$linkurl}Admin/Dashboard/View">Dashboard</a>
            </li>

            <li class="has-dropdown">
                <a href="#">Settings</a>
                <ul class="dropdown">
                    <li><a href="{$requestprotocol}://{$linkurl}Admin/Settings/Manage">System</a></li>
                    <li class="has-dropdown">
                        <a href="#">Firewall</a>
                        <ul class="dropdown">
                            <li><a href="{$requestprotocol}://{$linkurl}Firewall/Core/Manage">Settings</a></li>
                            <li><a href="{$requestprotocol}://{$linkurl}Firewall/Core/Manage?tab=automation">Automation & Integration</a></li>
                            <li><a href="{$requestprotocol}://{$linkurl}Firewall/Core/Manage?tab=plugins">Install Plugins</a></li>
                        </ul>
                    </li>
                    <li><a href="{$requestprotocol}://{$linkurl}User/Manage/Manage">Manage User</a></li>
                </ul>
            </li>

            <!-- Maintenance -->
            <li class="has-dropdown">
                <a href="#">Maintenance</a>
                <ul class="dropdown">
                    <li class="has-dropdown">
                        <a href="#">Update</a>
                        <ul class="dropdown">
                            <li><a href="#" onClick="getByAjax('{$requestprotocol}://{$linkurl}Admin/Maintenance/Update/GeoIP', 0); return false;">GeoIP Databases</a></li>
                        </ul>
                    </li>

                    <li class="has-dropdown">
                        <a href="#">Cache</a>
                        <ul class="dropdown">
                            <li><a href="#" onClick="getByAjax('{$requestprotocol}://{$linkurl}Admin/Maintenance/Cache/normalizeCache', 1); return false;">Normalize</a></li>
                            <li><a href="#" onClick="getByAjax('{$requestprotocol}://{$linkurl}Admin/Maintenance/Cache/clearCache', 1); return false;">Re-Initialize</a></li>
                            <li><a href="{$requestprotocol}://{$linkurl}Admin/Maintenance/Cache/Field?service=getUserAgents&field=user_agent&header=User+Agents">User Agents</a></li>
                            <li><a href="{$requestprotocol}://{$linkurl}Admin/Maintenance/Cache/Field?service=getReferrer&field=referrer&header=Referrer">Referrer</a></li>
                            <li><a href="{$requestprotocol}://{$linkurl}Admin/Maintenance/Cache/Field?service=getUrls&field=url&header=URLs">Pages (URLs)</a></li>
                            <li><a href="{$requestprotocol}://{$linkurl}Admin/Maintenance/Cache/Field?service=getEmails&field=email&header=Email+Addresses">Email Addresses</a></li>
                            <li><a href="{$requestprotocol}://{$linkurl}Admin/Maintenance/Cache/Field?service=getUsernames&field=username&header=Usernames">Usernames</a></li>
                            <li><a href="{$requestprotocol}://{$linkurl}Admin/Maintenance/Cache/Field?service=getHostnames&field=host_name&header=Hostnames">Hostnames</a></li>
                            <li><a href="{$requestprotocol}://{$linkurl}Admin/Maintenance/Cache/Field?service=getASN&field=asn&header=ASN">ASN</a></li>
                            <li><a href="{$requestprotocol}://{$linkurl}Admin/Maintenance/Cache/Field?service=getASNOrgs&field=asn_org&header=Organizations">Organizations</a></li>
                        </ul>
                    </li>
                </ul>
            </li>

            <li class="has-dropdown">
                <a href="#">File Integrity</a>
                <ul class="dropdown">
                    <li><a href="{$requestprotocol}://{$linkurl}Filesystem/FindFiles/View">Find Files By Name</a></li>
                    <li><a href="{$requestprotocol}://{$linkurl}Filesystem/FindInFiles/View">Find Files By Content</a></li>
                    <li><a href="{$requestprotocol}://{$linkurl}Filesystem/FindFilesByMTime/View">Find Files By Modification Time</a></li>
                    <li><a href="{$requestprotocol}://{$linkurl}Filesystem/FindFilesByMTime/View">Find Files By Change Time</a></li>
                    <li><a href="{$requestprotocol}://{$linkurl}Filesystem/CheckPermissions/View">Check Permissions</a></li>
                    <li><a href="{$requestprotocol}://{$linkurl}Filesystem/Virusscanner/View">Scanner</a></li>
                    <li><a href="{$requestprotocol}://{$linkurl}Filesystem/Signatures/View">Signatures</a></li>
                </ul>
            </li>
                </ul>
    </section>
</nav>
    </div>
