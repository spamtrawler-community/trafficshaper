<form action="{$requestprotocol}://{$linkurl}Filesystem/Virusscanner/View/scan" id="frmVirusscanner" method="POST">
<fieldset><legend>Options</legend>

            <input id="chkInfected" name="options[copyinfected]" type="checkbox" value="1"><label for="chkInfected">Copy Infected</label>
            <input id="chkEncrypted" name="options[markencrypted]" type="checkbox" value="1"><label for="chkEncrypted">Mark Encrypted</label>
            <input id="chkBroken" name="options[markbroken]" type="checkbox" value="1"><label for="chkBroken">Mark Broken</label>
            <input id="chkPUA" name="options[detectpua]" type="checkbox" value="1"><label for="chkPUA">Detect PUA</label>
            <label for="scanPath">Scan Path</label>
            <input id="scanPath" name="options[scanpath]" type="text" value="{$scanpath}">
        <input type="submit" id="btnScan" class="button success radius" value="Start Scan">
        <div id="preloader" class="small-8 columns right text-left preloader"></div>
</fieldset>
</form>
<fieldset id="fsetScanresult" style="display: none;">
    <legend>Scan Result</legend>
    <div id="scanresult"></div>

</fieldset>

{literal}
<script>
    // Attach a submit handler to the form
    $( "#frmVirusscanner" ).submit(function( event ) {
        // Stop form from submitting normally
        event.preventDefault();
        //$('#scanresult').html().hide();

        // Get some values from elements on the page:
        var $form = $( this ),
                url = $form.attr( "action" );

        // Send the data using post
        var posting = $.post( url, $( this ).serialize() );

        $('#btnScan').val("Scan in progress...").attr("disabled", true);
        $('#preloader').show();
        $('#fsetScanresult').hide();
        $('#scanresult').html();

        // Put the results in a div
        posting.done(function( data ) {
            $('#fsetScanresult').show();
            $('#scanresult').html(data);
            $('#preloader').hide();
            $('#btnScan').val("Start Scan").attr("disabled", false);
        });
    });
</script>
{/literal}