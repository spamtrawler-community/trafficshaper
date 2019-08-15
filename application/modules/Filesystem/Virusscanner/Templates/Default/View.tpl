<form action="{$requestprotocol}://{$linkurl}Filesystem/Virusscanner/View/scan" id="frmVirusscanner" method="POST">
            <input id="chkInfected" name="options[copyinfected]" type="checkbox" value="1"><label for="chkInfected">Copy Infected</label>
            <input id="chkEncrypted" name="options[markencrypted]" type="checkbox" value="1"><label for="chkEncrypted">Mark Encrypted</label>
            <input id="chkBroken" name="options[markbroken]" type="checkbox" value="1"><label for="chkBroken">Mark Broken</label>
            <input id="chkPUA" name="options[detectpua]" type="checkbox" value="1"><label for="chkPUA">Detect PUA</label>
            <label for="scanPath">Scan Path <small>(optional)</small></label>
            <input id="scanPath" name="options[scanpath]" type="text" value="{$scanpath}" class="radius">
        <input type="submit" id="btnScan" class="button success radius" value="Start Scan">
        <div id="preloader" class="small-8 columns right text-left preloader"></div>
</form>
<fieldset id="fsetScanresult" style="display: none;">
    <legend>Info</legend>
    <div id="scanresult"></div>

</fieldset>

{literal}
<script>
    $(function () {
        var frm = $('#frmVirusscanner');

        frm.submit(function (ev) {
            $('#btnScan').val("Scan in progress...").attr("disabled", true);
            $('#preloader').show();

            $.ajax({
                type: frm.attr('method'),
                url: frm.attr('action'),
                data: frm.serialize(),
                success: function (data) {
                    $('#preloader').hide();
                    $('#btnScan').val("Start Scan").attr("disabled", false);
                    alertify.alert(data);
                }
            });
            ev.preventDefault();
        });
    });
</script>
{/literal}