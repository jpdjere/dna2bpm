<!DOCTYPE html>
<html lang="en" manifest="{base_url}genias/manifest/offline.appcache">
    <head>
        <title>DNA&sup2; Admin</title>
        <link rel="stylesheet" type="text/css" href="{base_url}css/load_mask.css" />
        <link rel="stylesheet" href="{base_url}jscript/bootstrap/css/bootstrap.min.css" />
    </head>
    <body>
        <h1>TEST MANIFEST</h1>
        <div id="content"></div>
        <div id="loading-mask" style=""></div>
        <div id="loading">
            <div class="loading-indicator">
                <img src="{module_url}assets/images/loader18.gif" style="margin-right:8px;float:left;vertical-align:top;"/>
                <div style="float: left;">
                    {title}<br/>
                    <span id="loading-msg">
                        Loading Engine Items...
                    </span>
                </div>
            </div>
        </div>
    </body>
</html>