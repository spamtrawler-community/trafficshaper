<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" >
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Head.tpl"}
<body>
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Header.tpl"}
<form action="{$requestprotocol}://{$linkurl}Firewall/OrganizationWhitelist/Import/import" id="frmImport" method="post" enctype="multipart/form-data">
    <div class="row">
        <div id="importresult" class="small-11 columns"></div>
        <div class="small-11 columns">
            <input type="file" id="importfile" name="importfile">
        </div>
    </div>
    <div class="row">
        <div class="small-11 columns">
            <input type="submit" class="button success radius" value="Import">
        </div>
    </div>
</form>
{literal}
    <script>
        // Attach a submit handler to the form
        $( "#frmImport" ).submit(function( event ) {

            // Stop form from submitting normally
            event.preventDefault();

            // Get some values from elements on the page:
            var $form = $( this ),
                    url = $form.attr( "action" );
            var formData = new FormData(this);
            var $importresult = $('#importresult');

            $.ajax({
                url: url,
                type: 'POST',
                data: formData,
                mimeType:"multipart/form-data",
                contentType: false,
                cache: false,
                processData:false,
                success: function(data, textStatus, jqXHR)
                {
                    $($form).trigger('reset');

                    if( data == 'ok'){
                        $importresult.html( '<div class="alert-box success"> Import Successfull! </div>').show();
                        $importresult.fadeOut( 5000, complete );
                    } else {
                        $importresult.html( '<div class="alert-box alert"> ' + data + ' </div>').show();
                        //$importresult.fadeOut( 10000, complete );
                    }

                    function complete() {
                        $importresult.html( '' );
                    }
                },
                error: function(jqXHR, textStatus, errorThrown)
                {
                    $importresult.html( '<div class="alert-box alert"> ' + errorThrown + ' </div>').show();
                }
            });
        });
    </script>
{/literal}
</body>
</html>