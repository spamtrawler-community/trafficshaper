<div class="panel">
    <div class="row">
        <div class="small-12 columns">
            <div class="row">
                <div class="small-2 columns">
                    <input type="hidden" name="firewall_core[conf_class_name]"
                           value="{$params['firewall_core']['conf_class_name']}">
                    <label for="firewall_core[conf_status]" class="right"><span>{$language['Firewall']['Status']}</span></label>
                </div>
                <div class="small-6 columns left">
                    <select name="firewall_core[conf_status]" class="radius">
                        <option value="1" {if $params['firewall_core']['status'] eq  '1'} selected {/if} >{$language['Global']['Active']}</option>
                        <option value="0" {if $params['firewall_core']['status'] eq  '0'} selected {/if}>{$language['Global']['Inactive']}</option>
                    </select>
                </div>
                <div class="small-3 columns text-left">
                    {if $params['firewall_core']['status'] eq  '1'}
                        <i class="fi-lightbulb icon-large icon-active"></i>
                    {/if}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <div class="row">
                <div class="small-2 columns">
                    <label for="firewall_core[conf_params][mode]" class="right"><span>{$language['Firewall']['Core_Mode']}</span></label>
                </div>
                <div class="small-6 columns left">
                    <select id="selFirewallCoreMode" name="firewall_core[conf_params][mode]" class="radius">
                        <option value="integrated" {if $params['firewall_core']['mode'] eq  'integrated'} selected {/if} >{$language['Firewall']['Core_Integrated']}</option>
                        <option value="server" {if $params['firewall_core']['mode'] eq  'server'} selected {/if} >{$language['Firewall']['Core_Server']}</option>
                    </select>
                </div>
                <div class="small-3 columns text-left">
                    {if $params['firewall_core']['mode'] eq  'server'}<i class="arrows-out"></i>{/if}
                    {if $params['firewall_core']['mode'] eq  'integrated'}<i class="arrows-in icon-large"></i>{/if}
                </div>
            </div>
        </div>
    </div>
    <script>
        var selFirewallCoreMode = $("#selFirewallCoreMode");
        selFirewallCoreMode.on("change", function (e) {
            if (selFirewallCoreMode.val() != 'server') {
                $('#rowFirewallCoreApiKey').hide();
                $('#IntegratedSettings').show();
            } else {
                $('#rowFirewallCoreApiKey').show();
                $('#IntegratedSettings').hide();

                var apikey = $('#firewallCoreApiKey').val();
                if (!apikey) {
                    generateApiKey();
                }
            }
        });
    </script>


    <div class="row" id="rowFirewallCoreApiKey"
         {if $params['firewall_core']['mode'] neq  'server'}style="display:none;"{/if}">
    <div class="small-12 columns">
        <div class="row">
            <div class="small-2 columns">
                <label for="firewall_core[conf_params][apikey]" class="right"><span>{$language['Firewall']['Core_ApiKey']}</span></label>
            </div>
            <div class="small-6 columns left">
                <input type="text" class="radius" id="firewallCoreApiKey" name="firewall_core[conf_params][apikey]"
                       value="{if strlen($params['firewall_core']['apikey']) != 0}{$params['firewall_core']['apikey']|escape:'htmlall'}{/if}"
                       readonly>
            </div>
            <div class="small-3 columns text-left clickable">
                <i class="fi-loop medium" id="iGenerateApikey"></i>
                <script>
                    $("#iGenerateApikey").bind("click", function () {
                        generateApiKey();
                    });

                    function generateApiKey() {
                        $.get("{$requestprotocol}://{$linkurl}Install/Setup/Steps/generatePassword", function (data) {
                                    $("#firewallCoreApiKey").val(window.md5(data));
                                })
                                .fail(function () {
                                    $("#errors").html('Server unreachable or session expired!').show().delay(5000).fadeOut("slow");
                                })
                    }
                </script>
            </div>
        </div>
    </div>
</div>
<hr/>

<div class="row">
    <div class="small-12 columns">
        <div class="row">
            <div class="small-2 columns">
                <label for="firewall_core[conf_params][visitorcache_status]" class="right"><span>{$language['Firewall']['Core_CacheVisitors']}</span></label>
            </div>
            <div class="small-6 columns left">
                <select name="firewall_core[conf_params][visitorcache_status]" class="radius">
                    <option value="1" {if $params['firewall_core']['visitorcache_status'] eq  '1'} selected {/if} >{$language['Global']['Active']}</option>
                    <option value="0" {if $params['firewall_core']['visitorcache_status'] eq  '0'} selected {/if}>{$language['Global']['Inactive']}</option>
                </select>
            </div>
            <div class="small-3 columns text-left">
                {if $params['firewall_core']['visitorcache_status'] eq  '1'}
                    <i class="fi-lightbulb icon-large icon-active"></i>
                {/if}
            </div>
        </div>
    </div>
</div>

<hr/>

<div id="IntegratedSettings" {if $params['firewall_core']['mode'] eq  'server'}style="display:none;"{/if}>
    <div class="row">
        <div class="small-12 columns">
            <div class="row">
                <div class="small-2 columns">
                    <label for="firewall_core[conf_params][filter_post_only]" class="right"><span>Filter Mode</span></label>
                </div>
                <div class="small-6 columns left">
                    <select name="firewall_core[conf_params][filter_post_only]" class="radius">
                        <option value="1" {if $params['firewall_core']['filter_post_only'] eq  '1'} selected {/if} >Filter POST traffic only (Recommended)</option>
                        <option value="0" {if $params['firewall_core']['filter_post_only'] eq  '0'} selected {/if}>Filter ALL traffic (May increase System Load)</option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <div class="row">
                <div class="small-2 columns">
                    <label for="firewall_core[conf_params][cookie_status]" class="right"><span>{$language['Firewall']['Core_CookieStatus']}</span></label>
                </div>
                <div class="small-6 columns left">
                    <select name="firewall_core[conf_params][cookie_status]" class="radius">
                        <option value="1" {if $params['firewall_core']['cookie_status'] eq  '1'} selected {/if} >{$language['Firewall']['Core_UseCookies']}</option>
                        <option value="0" {if $params['firewall_core']['cookie_status'] eq  '0'} selected {/if}>{$language['Firewall']['Core_DoNotUseCookies']}</option>
                    </select>
                </div>
                <div class="small-3 columns text-left">
                    {if $params['firewall_core']['cookie_status'] eq  '1'}
                        <i class="fi-lightbulb icon-large icon-active"></i>
                    {/if}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <div class="row">
                <div class="small-2 columns">
                    <label for="firewall_core[conf_params][cookie_domain]" class="right"><span>{$language['Firewall']['Core_CookieName']}</span></label>
                </div>
                <div class="small-6 columns left">
                    <input type="text" class="radius" name="firewall_core[conf_params][cookie_name]"
                           value="{if strlen($params['firewall_core']['cookie_name']) != 0}{$params['firewall_core']['cookie_name']|escape:'htmlall'}{else}SpamTrawler{/if}">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <div class="row">
                <div class="small-2 columns">
                    <label for="firewall_core[conf_params][cookie_blocked_value]" class="right"><span>{$language['Firewall']['Core_BlockedCookieValue']}</span></label>
                </div>
                <div class="small-6 columns left">
                    <input type="text" class="radius" name="firewall_core[conf_params][cookie_blocked_value]"
                           value="{if strlen($params['firewall_core']['cookie_blocked_value']) != 0}{$params['firewall_core']['cookie_blocked_value']|escape:'htmlall'}{else}Blocked{/if}">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <div class="row">
                <div class="small-2 columns">
                    <label for="firewall_core[conf_params][cookie_permitted_value]" class="right"><span>{$language['Firewall']['Core_PermittedCookieValue']}</span></label>
                </div>
                <div class="small-6 columns left">
                    <input type="text" class="radius" name="firewall_core[conf_params][cookie_permitted_value]"
                           value="{if strlen($params['firewall_core']['cookie_permitted_value']) != 0}{$params['firewall_core']['cookie_permitted_value']|escape:'htmlall'}{else}Permitted{/if}">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <div class="row">
                <div class="small-2 columns">
                    <label for="firewall_core[conf_params][cookie_expiry]" class="right"><span>{$language['Firewall']['Core_CookieExpiry']}</span></label>
                </div>
                <div class="small-4 columns left">
                    <input type="number" class="radius" min="1" name="firewall_core[conf_params][cookie_expiry]"
                           value="{if strlen($params['firewall_core']['cookie_expiry']) != 0}{$params['firewall_core']['cookie_expiry']}{else}24{/if}">
                </div>
                <div class="small-2 columns left">
                    {$language['Global']['Hours']}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <div class="row">
                <div class="small-2 columns">
                    <label for="firewall_core[conf_params][cookie_domain]" class="right"><span>{$language['Firewall']['Core_CookieDomain']}</span></label>
                </div>
                <div class="small-6 columns left">
                    <input type="text" class="radius" name="firewall_core[conf_params][cookie_domain]"
                           value="{$params['firewall_core']['cookie_domain']|escape:'htmlall'}">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <div class="row">
                <div class="small-2 columns">
                    <label for="firewall_core[conf_params][cookie_path]" class="right"><span>{$language['Firewall']['Core_CookiePath']}</span></label>
                </div>
                <div class="small-6 columns left">
                    <input type="text" class="radius" name="firewall_core[conf_params][cookie_path]"
                           value="{$params['firewall_core']['cookie_path']|escape:'htmlall'}">
                </div>
            </div>
        </div>
    </div>

    <hr/>
    <!-- Cookies End here -->

    <div class="row">
        <div class="small-12 columns">
            <div class="row">
                <div class="small-2 columns">
                    <label for="firewall_core[conf_params][block_action]" class="right"><span>{$language['Firewall']['Core_BlockAction']}</span></label>
                </div>
                <div class="small-6 columns left">
                    <select name="firewall_core[conf_params][block_action]" class="radius">
                        <option value="accessdenied" {if $params['firewall_core']['block_action'] eq  'accessdenied'} selected {/if} >{$language['Firewall']['Core_BlockActionAccessDenied']}</option>
                        <option value="redirect" {if $params['firewall_core']['block_action'] eq  'redirect'} selected {/if}>{$language['Firewall']['Core_BlockActionRedirect']}</option>
                        <option value="returntosender" {if $params['firewall_core']['block_action'] eq  'returntosender'} selected {/if}>{$language['Firewall']['Core_BlockActionReturnToSender']}</option>
                        <option value="exitmessage" {if $params['firewall_core']['block_action'] eq  'exitmessage'} selected {/if}>{$language['Firewall']['Core_BlockActionShowExitMessage']}</option>
                    </select>
                </div>
                <div class="small-3 columns text-left">
                    {if $params['firewall_core']['block_action'] eq  'exitmessage'}
                        <i class="fi-clipboard-notes icon-large"></i>
                    {/if}
                    {if $params['firewall_core']['block_action'] eq  'accessdenied'}
                        <i class="fi-skull icon-large"></i>
                    {/if}
                    {if $params['firewall_core']['block_action'] eq  'redirect'}
                        <i class="fi-web icon-large"></i>
                    {/if}
                    {if $params['firewall_core']['block_action'] eq  'returntosender'}
                        <i class="fi-web icon-large"></i>
                    {/if}
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <div class="row">
                <div class="small-2 columns">
                    <label for="firewall_core[conf_params][redirection_target]" class="right"><span>{$language['Firewall']['Core_RedirectionTarget']}</span></label>
                </div>
                <div class="small-6 columns left">
                    <input type="text" class="radius" name="firewall_core[conf_params][redirection_target]"
                           value="{$params['firewall_core']['redirection_target']|escape:'htmlall'}">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <div class="row">
                <div class="small-2 columns">
                    <label for="firewall_core[conf_params][exit_message]" class="right"><span>{$language['Firewall']['Core_ExitMessage']}</span></label>
                </div>
                <div class="small-12 columns left">
                    <textarea
                            name="firewall_core[conf_params][exit_message]">{$params['firewall_core']['exit_message']|escape:'htmlall'}</textarea>
                </div>
            </div>
        </div>
    </div>

    <hr/>

    <div class="row">
        <div class="small-12 columns">
            <div class="row">
                <div class="small-2 columns">
                    <label for="firewall_core[conf_params][recaptcha_sitekey]" class="right"><span>{$language['Firewall']['Core_ReCaptchaSiteKey']}</span></label>
                </div>
                <div class="small-6 columns left">
                    <input type="text" class="radius" name="firewall_core[conf_params][recaptcha_sitekey]"
                           value="{$params['firewall_core']['recaptcha_sitekey']|escape:'htmlall'}">
                </div>
                <div class="small-2 columns left">
                    <a href="http://www.google.com/recaptcha/intro/index.html" target="_blank">Get Key</a>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <div class="row">
                <div class="small-2 columns">
                    <label for="firewall_core[conf_params][recaptcha_secret]" class="right"><span>{$language['Firewall']['Core_ReCaptchaSecret']}</span></label>
                </div>
                <div class="small-6 columns left">
                    <input type="text" class="radius" name="firewall_core[conf_params][recaptcha_secret]"
                           value="{$params['firewall_core']['recaptcha_secret']|escape:'htmlall'}">
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <div class="row">
                <div class="small-2 columns">
                    <label for="firewall_core[conf_params][recaptcha_language]" class="right"><span>{$language['Firewall']['Core_ReCaptchaLanguage']}</span></label>
                </div>
                <div class="small-6 columns left">
                    <select name="firewall_core[conf_params][recaptcha_language]" class="radius">
                        <option value="auto" {if $params['firewall_core']['recaptcha_language'] eq  'auto'} selected {/if} >
                            Automatic (Beta)
                        </option>
                        <option value="ar" {if $params['firewall_core']['recaptcha_language'] eq  'ar'} selected {/if} >
                            Arabic
                        </option>
                        <option value="bg" {if $params['firewall_core']['recaptcha_language'] eq  'bg'} selected {/if} >
                            Bulgarian
                        </option>
                        <option value="ca" {if $params['firewall_core']['recaptcha_language'] eq  'ca'} selected {/if} >
                            Catalan
                        </option>
                        <option value="zh-CN" {if $params['firewall_core']['recaptcha_language'] eq  'zh-CN'} selected {/if} >
                            Chinese (Simplified)
                        </option>
                        <option value="zh-TW" {if $params['firewall_core']['recaptcha_language'] eq  'zh-TW'} selected {/if} >
                            Chinese (Traditional)
                        </option>
                        <option value="cs" {if $params['firewall_core']['recaptcha_language'] eq  'cs'} selected {/if} >
                            Croatian
                        </option>
                        <option value="da" {if $params['firewall_core']['recaptcha_language'] eq  'da'} selected {/if} >
                            Danish
                        </option>
                        <option value="nl" {if $params['firewall_core']['recaptcha_language'] eq  'nl'} selected {/if} >
                            Dutch
                        </option>
                        <option value="en-GB" {if $params['firewall_core']['recaptcha_language'] eq  'en-GB'} selected {/if} >
                            English (UK)
                        </option>
                        <option value="en" {if $params['firewall_core']['recaptcha_language'] eq  'en'} selected {/if} >
                            English (US)
                        </option>
                        <option value="fil" {if $params['firewall_core']['recaptcha_language'] eq  'fil'} selected {/if} >
                            Filipino
                        </option>
                        <option value="fi" {if $params['firewall_core']['recaptcha_language'] eq  'fi'} selected {/if} >
                            Finnish
                        </option>
                        <option value="fr" {if $params['firewall_core']['recaptcha_language'] eq  'fr'} selected {/if} >
                            French
                        </option>
                        <option value="fr-CA" {if $params['firewall_core']['recaptcha_language'] eq  'fr-CA'} selected {/if} >
                            French (Canadian)
                        </option>
                        <option value="de" {if $params['firewall_core']['recaptcha_language'] eq  'de'} selected {/if} >
                            German
                        </option>
                        <option value="de-AT" {if $params['firewall_core']['recaptcha_language'] eq  'de-AT'} selected {/if} >
                            German (Austria)
                        </option>
                        <option value="de-CH" {if $params['firewall_core']['recaptcha_language'] eq  'de-CH'} selected {/if} >
                            German (Switzerland)
                        </option>
                        <option value="el" {if $params['firewall_core']['recaptcha_language'] eq  'el'} selected {/if} >
                            Greek
                        </option>
                        <option value="iw" {if $params['firewall_core']['recaptcha_language'] eq  'iw'} selected {/if} >
                            Hebrew
                        </option>
                        <option value="hi" {if $params['firewall_core']['recaptcha_language'] eq  'hi'} selected {/if} >
                            Hindi
                        </option>
                        <option value="hu" {if $params['firewall_core']['recaptcha_language'] eq  'hu'} selected {/if} >
                            Hungarian
                        </option>
                        <option value="id" {if $params['firewall_core']['recaptcha_language'] eq  'id'} selected {/if} >
                            Indonesian
                        </option>
                        <option value="it" {if $params['firewall_core']['recaptcha_language'] eq  'it'} selected {/if} >
                            Italian
                        </option>
                        <option value="ja" {if $params['firewall_core']['recaptcha_language'] eq  'ja'} selected {/if} >
                            Japanese
                        </option>
                        <option value="ko" {if $params['firewall_core']['recaptcha_language'] eq  'ko'} selected {/if} >
                            Korean
                        </option>
                        <option value="lv" {if $params['firewall_core']['recaptcha_language'] eq  'lv'} selected {/if} >
                            Latvian
                        </option>
                        <option value="lt" {if $params['firewall_core']['recaptcha_language'] eq  'lt'} selected {/if} >
                            Lithuanian
                        </option>
                        <option value="no" {if $params['firewall_core']['recaptcha_language'] eq  'no'} selected {/if} >
                            Norwegian
                        </option>
                        <option value="fa" {if $params['firewall_core']['recaptcha_language'] eq  'fa'} selected {/if} >
                            Persian
                        </option>
                        <option value="pl" {if $params['firewall_core']['recaptcha_language'] eq  'pl'} selected {/if} >
                            Polish
                        </option>
                        <option value="pt" {if $params['firewall_core']['recaptcha_language'] eq  'pt'} selected {/if} >
                            Portuguese
                        </option>
                        <option value="pt-BR" {if $params['firewall_core']['recaptcha_language'] eq  'pt-BR'} selected {/if} >
                            Portuguese (Brazil)
                        </option>
                        <option value="pt-PT" {if $params['firewall_core']['recaptcha_language'] eq  'pt-PT'} selected {/if} >
                            Portuguese (Portugal)
                        </option>
                        <option value="ro" {if $params['firewall_core']['recaptcha_language'] eq  'ro'} selected {/if} >
                            Romanian
                        </option>
                        <option value="ru" {if $params['firewall_core']['recaptcha_language'] eq  'ru'} selected {/if} >
                            Russian
                        </option>
                        <option value="sr" {if $params['firewall_core']['recaptcha_language'] eq  'sr'} selected {/if} >
                            Serbian
                        </option>
                        <option value="sk" {if $params['firewall_core']['recaptcha_language'] eq  'sk'} selected {/if} >
                            Slovak
                        </option>
                        <option value="sl" {if $params['firewall_core']['recaptcha_language'] eq  'sl'} selected {/if} >
                            Slovenian
                        </option>
                        <option value="es" {if $params['firewall_core']['recaptcha_language'] eq  'es'} selected {/if} >
                            Spanish
                        </option>
                        <option value="es-419" {if $params['firewall_core']['recaptcha_language'] eq  'es-419'} selected {/if} >
                            Spanish (Latin America)
                        </option>
                        <option value="sv" {if $params['firewall_core']['recaptcha_language'] eq  'sv'} selected {/if} >
                            Swedish
                        </option>
                        <option value="th" {if $params['firewall_core']['recaptcha_language'] eq  'th'} selected {/if} >
                            Thai
                        </option>
                        <option value="tr" {if $params['firewall_core']['recaptcha_language'] eq  'tr'} selected {/if} >
                            Turkish
                        </option>
                        <option value="uk" {if $params['firewall_core']['recaptcha_language'] eq  'uk'} selected {/if} >
                            Ukrainian
                        </option>
                        <option value="vi" {if $params['firewall_core']['recaptcha_language'] eq  'vi'} selected {/if} >
                            Vietnamese
                        </option>
                    </select>
                </div>
            </div>
        </div>
    </div>

    <hr/>

    <div class="row">
        <div class="small-12 columns">
            <div class="row">
                <div class="small-2 columns">
                    <label for="firewall_core[conf_params][usernamefields]" class="right"><span>{$language['Firewall']['Core_UsernameFields']}</span></label>
                </div>
                <div class="small-6 columns left">
                    <input type="text" class="radius" id="usernamefields" name="firewall_core[conf_params][usernamefields]"
                           value="{$params['firewall_core']['usernamefields']|escape:'htmlall'}">
                </div>
                <div class="small-2 columns left">
                    <select class="radius" id="username_preset">
                        <option value="">Presets</option>
                        <option value="username,email">SocialEngine</option>
                        <option value="val.login">PhpFox</option>
                    </select>
                    <!-- Username Field Presets -->
                    {literal}
                        <script>
                            var usernamepresetselect = $( "#username_preset" );
                            var usernamefieldstext = $( "#usernamefields" );
                            usernamepresetselect.change(function() {

                                if(usernamepresetselect.val() !== ''){
                                    var usernamepresetvalues = null;
                                    if(usernamefieldstext.val() === ''){
                                        usernamepresetvalues = usernamepresetselect.val();
                                    } else {
                                        usernamepresetvalues = ',' + usernamepresetselect.val();
                                    }

                                    var tmpusernamefields = usernamefieldstext.val() + usernamepresetvalues;
                                    tmpusernamefields = tmpusernamefields.replace(/\s+/g, '');
                                    var uniqueusernamefields = [];

                                    $.each(tmpusernamefields.split(","), function(i, el){
                                        if($.inArray(el, uniqueusernamefields) === -1) uniqueusernamefields.push(el);
                                    });

                                    usernamefieldstext.val(uniqueusernamefields.toString());
                                }
                            });
                        </script>
                    {/literal}
                    <!-- /Username Field Presets -->
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="small-12 columns">
            <div class="row">
                <div class="small-2 columns">
                    <label for="firewall_core[conf_params][emailfields]" class="right"><span>{$language['Firewall']['Core_EmailFields']}</span></label>
                </div>
                <div class="small-6 columns left">
                    <input type="text" class="radius" id="emailfields" name="firewall_core[conf_params][emailfields]"
                           value="{$params['firewall_core']['emailfields']|escape:'htmlall'}">
                </div>
                <div class="small-2 columns left">
                    <select class="radius" id="email_preset">
                        <option value="">Presets</option>
                        <option value="email">SocialEngine</option>
                        <option value="val.email">PhpFox</option>
                    </select>
                    <!-- Email Field Presets -->
                    {literal}
                        <script>
                            var emailpresetselect = $( "#email_preset" );
                            var emailfieldstext = $( "#emailfields" );
                            emailpresetselect.change(function() {

                                if(emailpresetselect.val() !== ''){
                                        var emailpresetvalues = null;
                                        if(emailfieldstext.val() === ''){
                                            emailpresetvalues = emailpresetselect.val();
                                        } else {
                                            emailpresetvalues = ',' + emailpresetselect.val();
                                        }
                                        //emailfieldstext.val( emailfieldstext.val() + emailpresetvalues);

                                        var tmpemailfields = emailfieldstext.val() + emailpresetvalues;
                                            tmpemailfields = tmpemailfields.replace(/\s+/g, '');
                                        var uniqueemailfields = [];

                                        $.each(tmpemailfields.split(","), function(i, el){
                                            if($.inArray(el, uniqueemailfields) === -1) uniqueemailfields.push(el);
                                        });

                                        emailfieldstext.val(uniqueemailfields.toString());
                                }
                            });
                        </script>
                    {/literal}
                    <!-- /Email Field Presets -->
            </div>
        </div>
    </div>
</div>
</div>