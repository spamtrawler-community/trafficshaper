<head>
    <meta name="robots" content="noindex">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SpamTrawler Admin Panel</title>

    <link href="//{$ressourceurl}/css/kendo/kendo.common.min.css" rel="stylesheet"/>


    <!-- Foundation -->
    <!-- If you are using CSS version, only link these 2 files, you may add app.css to use for your overrides if you like. -->
    <link rel="stylesheet" href="//{$ressourceurl}/css/foundation.min.css">
    <link rel="stylesheet" href="//{$ressourceurl}/css/foundation-icons/foundation-icons.css">
    <!-- End Foundation -->

    <link href="//{$ressourceurl}/css/kendo/kendo.flat.min.css" rel="stylesheet"/>

    <link rel="stylesheet" href="//{$ressourceurl}/css/alertify/alertify.core.css">
    <link rel="stylesheet" href="//{$ressourceurl}/css/alertify/alertify.default.css">
    <!-- Additional CSS -->
    <link rel="stylesheet" href="//{$ressourceurl}/css/additional.css">

    <script src="//{$ressourceurl}/js/vendor/modernizr.js"></script>
    <script type="text/javascript" src="//{$ressourceurl}/js/jquery-1.12.3.min.js"></script>
    <script type="text/javascript" src="//{$ressourceurl}/js/alertify/alertify.min.js"></script>
    <script type="text/javascript" src="//{$ressourceurl}/js/md5.js"></script>

    <script src="//{$ressourceurl}/js/foundation.min.js"></script>
    <!--<script src="//{$ressourceurl}/js/foundation/foundation.tooltip.js"></script>-->

    {literal}
        <script type="application/javascript">
            // Shorthand for $( document ).ready()
            $(function () {
                $(document).foundation();
            });
        </script>
    {/literal}
    <!-- Get URL by AJAX -->
    {literal}
        <script>
            function getByAjax(url, reload) {
                $.get(url, function (data) {
                    showPopUpNotification(data, 'message');
                    //alertify.alert(data);
                    if(reload === 1) {
                        location.reload(true);
                    }
                });
            }
        </script>
    {/literal}


</head>