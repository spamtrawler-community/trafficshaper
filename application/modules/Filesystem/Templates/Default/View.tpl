<!DOCTYPE html>
<!--[if IE 9]><html class="lt-ie10" lang="en" > <![endif]-->
<html class="no-js" lang="en" >
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Head.tpl"}
<body>
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Header.tpl"}
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/MainNav.tpl"}

<div class="row">
    <div id="tabstrip">
        <ul>
            <li class="k-state-active">
                Virus Scanner
            </li>
            <li>
                Malware Scanner
            </li>
        </ul>

        <!-- Virus Scanner Tab -->
        <div>
            {include file="file:{$path_modules}/Filesystem/Virusscanner/Templates/{$core_template}/View.tpl"}
        </div>

        <!-- Malware Scanner -->
        <div>
            {include file="file:{$path_modules}/Filesystem/Malwarescanner/Templates/{$core_template}/View.tpl"}
        </div>

        <script>
            $(document).ready(function() {
                $("#tabstrip").kendoTabStrip({
                    animation:  {
                        open: {
                            effects: "fadeIn"
                        }
                    }
                });
            });
        </script>
    </div>
</div>
{include file="file:{$path_modules}/Admin/Templates/{$core_template}/Footer.tpl"}
</body>
</html>