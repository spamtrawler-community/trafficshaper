<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" >
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Head.tpl"}
<body>
<div style="display: none; opacity: 1; visibility: hidden;" id="myModal" class="reveal-modal" data-reveal="">
<form>
<div class="row" id="resetpassword">
    <div class="small-4 small-centered columns">
    <fieldset>

        <div class="row collapse">
            <div class="small-12 columns"><input type="text" name="username" id="username" style="width: 100%;" size="100" value="" placeholder="Your Username" autocomplete="off"></div>
            <div class="small-12 columns"><input type="text" autocomplete="off" name="email" id="email" style="width: 100%;" size="100" placeholder="Email Address" value=""></div>
            <div class="small-12 columns"><button type="button" class="tiny button radius right" name="submit" id="submit">Reset Password</button></div>
        </div>
    </fieldset>
        <div class="small-12 columns"><h5 class="text-center"><small>IP Address Logged: {$visitorip}</small></h5></div>
    </div>
</div>
</form>
</div>
{literal}
<script type="text/javascript">
    $('#myModal').data('reveal-init', {
        animation: 'fade',
        animation_speed: 50,
        close_on_background_click: false,
        close_on_esc: false,
        dismiss_modal_class: 'close-reveal-modal',
        bg_class: 'reveal-modal-bg',
        bg : $('.reveal-modal-bg'),
        css : {
            open : {
                'opacity': 0,
                'visibility': 'visible',
                'display' : 'block'
            },
            close : {
                'opacity': 1,
                'visibility': 'hidden',
                'display': 'none'
            }
        }
    });

    $(document).ready(function(){$('#myModal').foundation('reveal', 'open')});
</script>
{/literal}
</body>
</html>