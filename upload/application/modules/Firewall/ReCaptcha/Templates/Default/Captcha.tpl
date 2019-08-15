<form action="{$url}" method="post">
        <div class="g-recaptcha" data-sitekey="{$sitekey}"></div>
        <script type="text/javascript"
                src="https://www.google.com/recaptcha/api.js?hl={$captchalang}">
        </script>
        <p><input type="submit" value="Submit" /></p>
</form>