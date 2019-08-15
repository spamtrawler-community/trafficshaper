<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" >
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Head.tpl"}
<body>
<script src="//{$ressourceurl}/js/DuoSecurity/Duo-Web-v1.min.js"></script>
<script src="//{$ressourceurl}/js/DuoSecurity/Duo-Init.js"></script>
{literal}
<script>
    Duo.init({
        'host': '{/literal}{$smarty.const.HOST}{literal}',
        'sig_request': '{/literal}{$DuoSigRequest}{literal}',
        'post_action': '{/literal}{$requestprotocol}://{$linkurl}Admin/Auth/DuoSec{literal}'
    });
</script>
{/literal}
<div class="row">
    <div class="small-12 columns">
        <iframe id="duo_iframe" width="620" height="500" frameborder="0" allowtransparency="true" style="background: transparent;"></iframe>
    </div>
</div>
</body>
</html>