<?php
header('Cache-Control: no-cache,public,max-age=0,must-revalidate');
/* $offset=0;
$ExpStr = "Expires: " . gmdate("D, d M Y H:i:s", time() + $offset) . " GMT";
header($ExpStr);*/
?>
<!DOCTYPE html>
<html lang="es" manifest="{base_url}genias/manifest/offline.appcache">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>{title}</title>
    <link rel="stylesheet" type="text/css" href="{base_url}genias/assets/css/custom.bootstrap.css" />
    <link rel="stylesheet" type="text/css" href="{base_url}jscript/fontawesome/css/font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="{base_url}jscript/ext/resources/css/ext-all-neptune-debug.css" />
        <!--
        no funcionan los buttons
        <link rel="stylesheet" type="text/css" href="{module_url}assets/css/fix_bootstrap_checkbox.css" />-->
        <link rel="stylesheet" type="text/css" href="{base_url}css/load_mask.css" />
        {css}
        <style>
        select, textarea, input[type="text"], input[type="password"], input[type="datetime"], input[type="datetime-local"], input[type="date"], input[type="month"], input[type="time"], input[type="week"], input[type="number"], input[type="email"], input[type="url"], input[type="search"], input[type="tel"], input[type="color"], .uneditable-input {
            height:24px;
        }
         a {
            color:#FFFF;
        }
        .x-panel-header-default {
         background-color: #1B1B1B;
         background-image: linear-gradient(to bottom, #222222, #111111);
         background-repeat: repeat-x;
         border-color: #252525;
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
            var globals={inline_js};
            </script>
            <script type="text/javascript">document.getElementById('loading-msg').innerHTML += '<br/>Loading Core API...';</script>
            <script type="text/javascript" src="{base_url}jscript/ext/bootstrap.js"></script>
            <script type="text/javascript" src="{base_url}jscript/ext/packages/ext-theme-neptune/build/ext-theme-neptune.js"></script>
            <script type="text/javascript">
            //----prevent ajax to attach dc_4584589 to the end of urls
            //--- this is 4 CodeIgniter smart urls
            //----and make all reads as posts
            Ext.apply(Ext.data.AjaxProxy.prototype,
            {
                noCache:false,
                actionMethods:{
                    read:'POST'
                }
            }
            );
            </script>
            <script type="text/javascript">document.getElementById('loading-msg').innerHTML += '<span class="ok">OK.</span>';</script>        
            {js}
        </body>
        </html>