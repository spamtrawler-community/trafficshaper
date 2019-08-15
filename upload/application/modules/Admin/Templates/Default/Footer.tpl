<!-- popup -->
<span id="popupnotification"></span>
{literal}
    <script>
        function showPopUpNotification(message, mode) {
            var notification = $("#popupnotification").kendoNotification({
                //autoHideAfter: 0
                //width: "12em"
            }).data("kendoNotification");
            notification.show(kendo.toString(message), mode);
        }
        ;
    </script>
{/literal}
<!-- /popup -->
</div> <!-- End Content Container  -->
<div id="footer" xmlns="http://www.w3.org/1999/html">
    <div class="row">
        <div class="small-6 columns">
            {$poweredby}
        </div>
        <div class="small-6 columns">{$copyright}</div>
    </div>
</div>

<!-- Loader -->
<div id="loadIndicator"></div>
{literal}
    <script>
        function displayLoading(target) {
            if (typeof target === 'undefined') {
                target = '#loadIndicator';
            }

            $(target).show();

            var element = $(target);
            kendo.ui.progress(element, true);
        }

        function hideLoading(target) {
            if (typeof target === 'undefined') {
                target = '#loadIndicator';
            }

            var element = $(target);
            kendo.ui.progress(element, false);
        }
    </script>
{/literal}
<!-- /Loader -->
<!--
<div id="myModal" class="reveal-modal" data-reveal>
    <h2>Awesome. I have it.</h2>
    <p class="lead">Your couch.  It is mine.</p>
    <p>Im a cool paragraph that lives inside of an even cooler modal. Wins</p>
    <a class="close-reveal-modal">&#215;</a>
</div>
-->
<!--Start Kendo Modal Window -->
<div id="modal"></div>
<!-- End Kendo Modal Window -->

<!-- Templates -->
{literal}
<script type="text/x-kendo-template" id="IpCache">
    <div class="tableRow">
        <div class="tableColumnLeft tableColumnFirst">IP:</div>
        <div class="tableColumnLeft">${ ip }</div>
        <div style="clear: left;"></div>
        <div class="tableColumnRight tableColumnLast">
            <div class="btn-group">
                {literal}
                <button type="button" class="btn btn-small btn-inverse" style="${ IpBlackListReportButton }"
                        onclick="xajax_SpamTrawler_Admin_Ajax_Admin.reportToMasterBlackList('${ formtoken }','${ ip }','1');">
                    Report
                </button>
                <button type="button" class="btn btn-small btn-danger" style="${ IpReportButton }"
                        onclick="xajax_SpamTrawler_Admin_Ajax_Admin.reportToMasterBlackList('${ formtoken }','${ ip }','1');">
                    Report
                </button>
                <button type="button" class="btn btn-small btn-danger" style="${ IpBlackListButton }"
                        onclick="xajax_SpamTrawler_Admin_Ajax_Admin.addIpToBlackList('${ formtoken }','none','${ ip }','0');$('.blacklist${id}').click();">
                    Blacklist
                </button>
                <button type="button" class="btn btn-small btn-warning" style="${ IpGreyListButton }"
                        onclick="xajax_SpamTrawler_Admin_Ajax_Admin.addIpToGreyList('${ formtoken }','none','${ ip }','0');$('.blacklist${id}').click();">
                    Greylist
                </button>
                <button type="button" class="btn btn-small btn-success" style="${ IpWhiteListButton }"
                        onclick="xajax_SpamTrawler_Admin_Ajax_Admin.addIpToWhiteList('${ formtoken }','none','${ ip }','0');$('.blacklist${id}').click();">
                    Whitelist
                </button>
                {/literal}
            </div>
        </div>
        <div style="clear:both;"></div>
    </div>

    <div class="tableRow">
        <div class="tableColumnLeft tableColumnFirst">Country:</div>
        <div class="tableColumnLeft">${ countryName } (${ countrycode })</div>
        <div style="clear: left;"></div>
        <div class="tableColumnRight tableColumnLast">
            <button type="button" id="submit" name="submit" class="btn btn-small btn-primary"
                    onclick="xajax_SpamTrawler_Admin_Ajax_Admin.CountryManager('{token}','window'); return false;">
                Manage
            </button>
        </div>
        <div style="clear:both;"></div>
    </div>

    <div class="tableRow">
        <div class="tableColumnLeft tableColumnFirst">Host Name:</div>
        <div class="tableColumnLeft">${ host }</div>
        <div style="clear: left;"></div>
        <div class="tableColumnRight tableColumnLast">
            #= hostDomain #
            <div class="btn-group">
                <button type="button" class="btn btn-small btn-danger"
                        onclick="xajax_SpamTrawler_Admin_Ajax_Admin.addToHostnameBlackList('${ formtoken }','none','${ host64 }','2');$('.blacklist${id}').click();">
                    Blacklist
                </button>
                <button type="button" class="btn btn-small btn-success"
                        onclick="xajax_SpamTrawler_Admin_Ajax_Admin.addToHostnameWhiteList('${ formtoken }','none','${ host64 }','2');$('.blacklist${id}').click();">
                    Whitelist
                </button>
            </div>
        </div>
        <div style="clear:both;"></div>
    </div>

    <div class="tableRow">
        <div class="tableColumnLeft tableColumnFirst">Organization:</div>
        <div class="tableColumnLeft">${ organization }</div>
        <div style="clear: left;"></div>
        <div class="tableColumnRight tableColumnLast">
            <button type="button" class="btn btn-small btn-danger"
                    onclick="xajax_SpamTrawler_Admin_Ajax_Admin.addToOrganizationBlackList('${ formtoken }','none','${ org64 }','2');$('.blacklist${id}').click();">
                Blacklist
            </button>
        </div>
        <div style="clear:both;"></div>
    </div>

    <div class="tableRow">
        <div class="tableColumnLeft tableColumnFirst">User Agent:</div>
        <div class="tableColumnLeft">${ agent }</div>
        <div style="clear: left;"></div>
        <div class="tableColumnRight tableColumnLast">
            <button type="button" class="btn btn-small btn-danger"
                    onclick="xajax_SpamTrawler_Admin_Ajax_Admin.addToUserAgentBlacklist('${ formtoken }','none','${ agent64 }','2');$('.blacklist${id}').click();">
                Blacklist
            </button>
        </div>
        <div style="clear:both;"></div>
    </div>

    <div class="tableRow">
        <div class="tableColumnLeft tableColumnFirst">Device Type:</div>
        <div class="tableColumnLeft">${ device }</div>
        <div style="clear: left;"></div>
        <div style="clear:both;"></div>
    </div>

    <div class="tableRow">
        <div class="tableColumnLeft tableColumnFirst">Username:</div>
        <div class="tableColumnLeft">${ username }</div>
        <div style="clear: left;"></div>
        <div class="tableColumnRight tableColumnLast">
            #= usernameBlacklistButton #
        </div>
        <div style="clear:both;"></div>
    </div>

    <div class="tableRow">
        <div class="tableColumnLeft tableColumnFirst">Email:</div>
        <div class="tableColumnLeft">${ email }</div>
        <div style="clear: left;"></div>
        <div class="tableColumnRight tableColumnLast">
            <button type="button" class="btn btn-small btn-danger" style="${ emailReportButton }"
                    onclick="xajax_SpamTrawler_Admin_Ajax_Admin.reportToMasterBlackList('${ formtoken }','${ email }','1');">
                Report
            </button>
            #= emailBlacklistButton #
        </div>
        <div style="clear:both;"></div>
    </div>

    <div class="tableRow">
        <div class="tableColumnLeft tableColumnFirst">Referrer:</div>
        <div class="tableColumnLeft">${ referrer }</div>
        <div style="clear: left;"></div>
        <div class="tableColumnRight tableColumnLast">
            <button type="button" class="btn btn-small btn-danger"
                    onclick="xajax_SpamTrawler_Admin_Ajax_Admin.addToReferrerBlacklist('${ formtoken }','none','${ referrer64 }','2');$('.blacklist${id}').click();">
                Blacklist
            </button>
        </div>
        <div style="clear:both;"></div>
    </div>

    <div class="tableRow">
        <div class="tableColumnLeft tableColumnFirst">Page:</div>
        <div class="tableColumnLeft">${ page }</div>
        <div style="clear: left;"></div>
        <div class="tableColumnRight tableColumnLast">&nbsp;</div>
        <div style="clear:both;"></div>
    </div>

    <div class="tableRow">
        <div class="tableColumnLeft tableColumnFirst">Comment:</div>
        <div class="tableColumnLeft tableColumnLast" style="width: 70%;">
            <div style="position: relative;">
                <textarea class="txtComment" style="width: 100%;" name="comment${ id }"
                          id="comment${ id }">${ comment }</textarea>
                <div style="position: absolute; width:50px; cursor: pointer; top: 10px; left: 5px; height: 50px; z-index: 9999;"
                     onclick="$('\#loader').show();xajax_SpamTrawler_Admin_Ajax_Admin.ipCacheUpdate('${ formtoken }','1','${ id }');"></div>
                <div id="status${ id }"
                     style="position: absolute; width: 50px; height: 50px; top: 10px; left: 5px; z-index: 99999; background-image: url('./static/images/success.png'); background-position:35% 15%; background-repeat: no-repeat; display: none;"></div>
            </div>
        </div>
        <div style="clear:both;"></div>
    </div>
    <!-- Additional Lookups -->
    <div class="tableRow" style="margin-top: 5px;">
        <div class="tableColumnLeft" style="margin-right: 20px">
            <span style="background-image:url(./static/images/magnifier.png); background-repeat: no-repeat; padding-bottom: 10px; padding-left: 20px;"><a
                        href="http://www.projecthoneypot.org/ip_${ ip }" target="_blank">Project
                    Honeypot</a></span><br/>
            <span style="background-image:url(./static/images/magnifier.png); background-repeat: no-repeat; padding-bottom: 10px; padding-left: 20px;"><a
                        href="http://www.botscout.com/ipcheck.htm?ip=${ ip }" target="_blank">BotScout</a></span><br/>
            <span style="background-image:url(./static/images/magnifier.png); background-repeat: no-repeat; padding-bottom: 10px; padding-left: 20px;"><a
                        href="http://www.fspamlist.com/?search=${ ip }" target="_blank">FSpamlist</a></span><br/>
        </div>
        <div class="tableColumnLeft" style="margin-right: 20px">
            <span style="background-image:url(./static/images/magnifier.png); background-repeat: no-repeat; padding-bottom: 10px; padding-left: 20px;"><a
                        href="http://hosts-file.net/?s=${ ip }" target="_blank">Hosts-File</a></span><br/>
            <span style="background-image:url(./static/images/magnifier.png); background-repeat: no-repeat; padding-bottom: 10px; padding-left: 20px;"><a
                        href="http://www.stopforumspam.com/ipcheck/${ ip }"
                        target="_blank">StopForumSpam</a></span><br/>
            <span style="background-image:url(./static/images/magnifier.png); background-repeat: no-repeat; padding-bottom: 10px; padding-left: 20px;"><a
                        href="http://www.google.com/search?q=${ ip }" target="_blank">Google</a></span><br/>
        </div>
        <div class="tableColumnLeft" style="margin-right: 20px">
            <span style="background-image:url(./static/images/magnifier.png); background-repeat: no-repeat; padding-bottom: 10px; padding-left: 20px;"><a
                        href="http://groups.google.com/groups?scoring=d&q=${ ip }+group:*abuse*" target="_blank">Google
                    Groups</a></span><br/>
            <span style="background-image:url(./static/images/magnifier.png); background-repeat: no-repeat; padding-bottom: 10px; padding-left: 20px;"><a
                        href="http://www.domaintools.com/${ ip }" target="_blank">DomainTools</a></span><br/>
            <span style="background-image:url(./static/images/magnifier.png); background-repeat: no-repeat; padding-bottom: 10px; padding-left: 20px;"><a
                        href="http://www.spamhaus.org/query/bl?ip=${ ip }" target="_blank">SpamHaus</a></span><br/>
        </div>
        <div class="tableColumnLeft" style="margin-right: 20px">
            <span style="background-image:url(./static/images/magnifier.png); background-repeat: no-repeat; padding-bottom: 10px; padding-left: 20px;"><a
                        href="http://www.spamcop.net/w3m?action=checkblock&ip=${ ip }"
                        target="_blank">SpamCop</a></span><br/>
            <span style="background-image:url(./static/images/magnifier.png); background-repeat: no-repeat; padding-bottom: 10px; padding-left: 20px;"><a
                        href="http://www.spamcop.net/sc?track=${ ip }" target="_blank">Abuse Contacts</a></span>
        </div>
        <div style="clear: left"></div>
    </div>
    <!-- /Additional Lookups -->
</script>

<!-- Hookdetails -->
<script type="text/x-kendo-template" id="HookDetails">
    <form id="frm_${ buttonid }" name="frm_${ buttonid }" action="javascript:void(null);">
        <div class="tableRow">
            <div class="tableColumnLeft tableColumnFirst">Hook Status:</div>
            <div class="tableColumnLeft" id="installButton_${ buttonid }">
                #= installButton #
            </div>
            <div style="clear:both;"></div>
        </div>

        <!-- Block Reason -->
        <div class="tableRow">
            <div class="tableColumnLeft tableColumnFirst">Block Reason</div>
            <div class="tableColumnLeft">
                #= blockreasonfield #
            </div>
            <div class="tableColumnRight"><img src="./static/images/information.png"
                                               title="Allowed characters: 0-9 a-z A-Z ! ?" border="0"/></div>
            <div style="clear:both;"></div>
        </div>
        <!-- /Block Reason -->

        <div class="tableRow">
            <div class="tableColumnLeft tableColumnFirst">No Captcha:</div>
            <div class="tableColumnLeft">
                #= nocaptchabox #
            </div>
            <div style="clear:both;"></div>
        </div>

        <div class="tableRow">
            <div class="tableColumnLeft tableColumnFirst">Description:</div>
            <div class="tableColumnLeft">
                #= description #
            </div>
            <div style="clear:both;"></div>
        </div>
    </form>
</script>
{/literal}
<!-- /Hookdetails -->

<!-- /Templates -->
<!-- Popup Window -->
<div id="ManageList"></div>
{literal}
    <script>
        function ManageList(title, content) {
            var win = $("#ManageList").kendoWindow({
                title: title,
                visible: false,
                width: "1000px",
                height: "603px",
                scrollable: false,

                modal: true,
                content: content
            }).data("kendoWindow");

            win.title(title);
            win.center();

            $('#ManageList').closest(".k-window").css({
                position: 'fixed',
                margin: 'auto',
                top: '5%'
            });

            win.open();
        }
        ;
    </script>
{/literal}
<!-- End Popup Window -->

<!-- Notifications Window -->
<div id="winNotifications" style="overflow: hidden;"></div>
<script>
    //Check for new notifications
    $(document).ready(function () {
        checkNewNotifications();
        var myVar = setInterval(function () {
            checkNewNotifications();
        }, 60000); //1 minute = 60000
    });

    function checkNewNotifications() {
        var iShowNotifications = $("#iShowNotifications");
        $.ajax({
            type: "get",
            url: "{$requestprotocol}://{$linkurl}Admin/Notifications/View/checkNew",
            data: "",
            success: function (data) {
                if (!isNaN(data) && data > 0) {
                    iShowNotifications.show();
                    iShowNotifications.css("opacity", "0.9");
                    iShowNotifications.html('<small>' + data + '</small>');
                } else {
                    iShowNotifications.hide();
                    iShowNotifications.html('');
                    iShowNotifications.css("opacity", "0.3");
                }
            },
            async: true
        })
    }

    //Show Notifications
    $("#aShowNotifications").click(function () {
        var accessWindow = $("#winNotifications").kendoWindow({
            actions: ["Close"],
            draggable: true,
            height: "500px",
            modal: true,
            resizable: false,
            title: "Notifications",
            width: "800px",
            visible: false, /*don't show it yet*/
            iframe: true,
            content: "{$requestprotocol}://{$linkurl}Admin/Notifications/View",
            close: checkNewNotifications
        }).data("kendoWindow").center().open();
    });
</script>
<!--Kendo-->
<script src="//{$ressourceurl}/js/kendo/kendo.all.min.js"></script>
<!--/Kendo-->