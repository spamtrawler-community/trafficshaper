<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>You have been blocked!</title>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/1.11.1/jquery.js"></script>
    <link rel="stylesheet" href="//{$ressourceurl}/css/foundation.min.css">
    <link rel="stylesheet" href="//{$ressourceurl}/css/foundation-icons/foundation-icons.css">
    <script src="//{$ressourceurl}/js/foundation.min.js"></script>
{literal}
    <script type="application/javascript">        $(document).foundation();
        $(document).ready(function () {
            $('#myModal').foundation('reveal', 'open')
        });</script>{/literal}</head>
<body>
<div class="row">    <!--  Modal -->
    <div style="display: none; opacity: 1; visibility: hidden;" id="myModal" class="reveal-modal" data-reveal="">
        <div class="row" id="loginform">
            <div class="small-12  small-centered columns">                {$ExitMessage}
                <div class="small-12 columns">
                    <h5 class="text-center">
                        <small>IP Address Logged: {$visitorip}</small>
                    </h5>
                </div>
            </div>
        </div>
    </div>{literal}
    <script type="text/javascript">    $('#myModal').data('reveal-init', {
            animation: 'fade',
            animation_speed: 50,
            close_on_background_click: false,
            close_on_esc: false,
            dismiss_modal_class: 'close-reveal-modal',
            bg_class: 'reveal-modal-bg',
            bg: $('.reveal-modal-bg'),
            css: {
                open: {'opacity': 0, 'visibility': 'visible', 'display': 'block'},
                close: {'opacity': 1, 'visibility': 'hidden', 'display': 'none'}
            }
        });</script>{/literal}
    <!--  End Modal -->
</div>
</body>
</html>