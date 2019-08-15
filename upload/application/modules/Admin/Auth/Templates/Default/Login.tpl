<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" >
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Head.tpl"}
<body>
<div style="display: none; opacity: 1; visibility: hidden;" id="myModal" class="reveal-modal full" data-reveal="">
    <form action="{$requestprotocol}://{$linkurl}Admin/Auth/Login/login" method="post">
        <div class="row" id="loginform">
            <div class="small-10 large-4 small-centered columns">
                <fieldset>
                    <div class="row collapse">
                    {if isset($error)}
                        {literal}<script>$(function() { $('#divError').delay(5000).fadeOut('slow') });</script>{/literal}
                        <div class="small-12 columns" id="divError">
                            {foreach from=$error item=message}
                                <div data-alert="" class="alert-box alert">
                                    {$message}
                                    <a href="" class="close">×</a>
                                </div>
                            {/foreach}

                        </div>
                    {/if}
                        <div class="small-12 columns"><input type="text" name="username" id="username" style="width: 100%;" size="100" value="" placeholder="Your Username" autocomplete="off"></div>
                        <div class="small-12 columns"><input type="password" autocomplete="off" name="password" id="password" style="width: 100%;" size="100" placeholder="Your Password" value=""><input type="hidden" name="token" value="{$token}"></div>
                        <div class="small-12 columns"><input type="submit" class="tiny button radius right" name="submit" id="submit" value="Login"></div>
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