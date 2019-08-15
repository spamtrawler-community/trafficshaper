<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" >
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Head.tpl"}
<body>
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Header.tpl"}
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/MainNav.tpl"}
<div class="row">
    <div class="small-12 columns">
        <form action="{$requestprotocol}://{$linkurl}Filesystem/Signatures/View/create" id="frmCreateSignatures" method="POST">
            <br />
            <input type="submit" id="btnCreateSignatures" class="button success radius" value="Create Signatures">
            <div id="preloaderCreateSignatures" class="small-8 columns right text-left preloader"></div>
        </form>

        {literal}
            <script>
                $(function () {
                    var frm = $('#frmCreateSignatures');

                    frm.submit(function (ev) {
                        $('#btnCreateSignatures').val("Signature creation in process...").attr("disabled", true);
                        $('#preloaderCreateSignatures').show();

                        $.ajax({
                            type: frm.attr('method'),
                            url: frm.attr('action'),
                            data: frm.serialize(),
                            success: function (data) {
                                $('#preloaderCreateSignatures').hide();
                                $('#btnCreateSignatures').val("Create Signatures").attr("disabled", false);
                                alertify.alert(data);
                            }
                        });
                        ev.preventDefault();
                    });
                });
            </script>
        {/literal}
    </div>
</div>
<div class="row">
    <div class="small-12 columns">
        <form action="{$requestprotocol}://{$linkurl}Filesystem/Signatures/View/compare" id="frmCompareSignatures" method="POST">
            <br />
            <input type="submit" id="btnCompareSignatures" class="button success radius" value="Compare Signatures">
            <div id="preloaderCompareSignatures" class="small-8 columns right text-left preloader"></div>
        </form>

        {literal}
            <script>
                $(function () {
                    var frm = $('#frmCompareSignatures');

                    frm.submit(function (ev) {
                        $('#btnCompareSignatures').val("Signature omparison in process...").attr("disabled", true);
                        $('#preloaderCompareSignatures').show();

                        $.ajax({
                            type: frm.attr('method'),
                            url: frm.attr('action'),
                            data: frm.serialize(),
                            success: function (data) {
                                $('#preloaderCompareSignatures').hide();
                                $('#btnCompareSignatures').val("Compare Signatures").attr("disabled", false);
                                alertify.alert(data);
                            }
                        });
                        ev.preventDefault();
                    });
                });
            </script>
        {/literal}
    </div>
</div>
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Footer.tpl"}
</body>
</html>