<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" >
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Head.tpl"}
<body>
<script src="chrome-extension://pfboblefjcgdjicmnffhdgionmgcdmne/u2f-api.js"></script>
<!-- /U2F -->
<script>{$javascript}</script>

<div class="row">
    <div class="small-12 columns">
        <h1>Two Factor Authentication Required!</h1>
       {if $mode eq 'register'}
        <div data-alert class="alert-box secondary radius">
                <h4>In order to use a U2F device, make sure you have the following:</h4>
            <ul>
                <li>
                    U2F USB Dongle (e.g.: <a href="https://store.yubico.com/store/catalog/product_info.php?products_id=112" target="_blank">Yubico Fido U2F Security Key</a> )
                </li>
                <li>
                    A supported browser (Chrome 38 or later)<br />
                    Note: OS X Yosemite users need Chrome 39 beta or later.
                </li>
                <li>
                    The FIDO U2F (Universal 2nd Factor) extension
                </li>
                <li>
                    An available USB port
                </li>
            </ul>
        </div>

        <div data-alert class="alert-box secondary radius">
        <h4>To proceed with the registration of your U2F token please follow the steps below:</h4>
            <ul>
                <li>Insert your U2F USB Dongle into a free USB port</li>
                <li>Click the "Register" button</li>
                <li>Click "Allow" when the browser prompts you to allow access to your security key</li>
                <li>Touch the blue flashing key on your Dongle</li>
                <li>Done!</li>
            </ul>
        </div>
            {else}
                <div data-alert class="alert-box secondary radius">
                    <h4>To proceed with the authentication of your U2F token please follow the steps below:</h4>
                    <ul>
                        <li>Insert your U2F USB Dongle into a free USB port</li>
                        <li>Click the "Authenticate" button</li>
                        <li>Touch the blue flashing key on your Dongle</li>
                        <li>Done!</li>
                    </ul>
                </div>
        {/if}

        <form method="POST" id="form">
            <input type="hidden" name="username" id="username" value="{$username}"/><br/>
            <input type="hidden" name="action" id="action" value="{$mode}"/>
            <input type="hidden" name="register2" id="register2"/>
            <input type="hidden" name="authenticate2" id="authenticate2"/>
                <button type="submit">{$btnCaption}</button>
        </form>
    </div>
</div>
</body>
</html>