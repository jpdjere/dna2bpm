<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>{title}</title>

        <link rel="stylesheet" type="text/css" href="{base_url}jscript/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="{base_url}jscript/bootstrap-wysihtml5/css/font-awesome.min.css" />
        <link rel="stylesheet" type="text/css" href="{base_url}jscript/bootstrap-wysihtml5/css/ionicons.min.css" />
        <link rel="stylesheet" type="text/css" href="{base_url}jscript/ext/resources/css/ext-all-neptune-debug.css" />
        <link rel="stylesheet" type="text/css" href="{base_url}css/load_mask.css" />
        <link rel="stylesheet" type="text/css" href="{base_url}jscript/ext/src/ux/statusbar/css/statusbar.css" />
        <link rel="stylesheet" type="text/css" href="{base_url}jscript/ext/src/ux/css/CheckHeader.css" />
        {css}
        <style type="text/css">
            .x-tree-checkbox{
                width: none !important;   
            }
        </style>
    </head>
    <body>
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

        <!-- Boot -->
        <script type="text/javascript">
            //-----declare global vars
            var globals = {inline_js};
        </script>
        <script type="text/javascript">document.getElementById('loading-msg').innerHTML += '<br/>Loading Core API...';</script>
        <script type="text/javascript" src="{base_url}jscript/ext/bootstrap.js"></script>
        <script type="text/javascript" src="{base_url}jscript/ext/packages/ext-theme-neptune/build/ext-theme-neptune.js"></script>
        <script type="text/javascript">document.getElementById('loading-msg').innerHTML += '<span class="ok">OK.</span>';</script>
        {js}
    </body>
</html>