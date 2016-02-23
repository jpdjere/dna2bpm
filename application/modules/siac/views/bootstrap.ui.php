<!DOCTYPE html>
<html>
    <head>
        <title>{title}</title>
        <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta name="description" content="">
        <meta name="author" content="">
        <!-- Latest compiled and minified CSS -->
        <link href="{base_url}jscript/bootstrap-wysihtml5/css/bootstrap.min.css" rel="stylesheet">
        <link href="{base_url}siac/assets/css/siac.css" rel="stylesheet">
        <link href="{base_url}siac/assets/css/bootstrap-tour.css" rel="stylesheet">
        <link rel="stylesheet" href="{base_url}jscript/font-awesome-4.5.0/css/font-awesome.min.css" />
        <!-- CSS -->
        {css}

        <!--<link href="{base_url}jscript/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">-->
    </head>
    <body>
        {content}
        <!-- Boot -->
        <script type="text/javascript">
            //-----declare global vars
            var globals={inline_js};
        </script>
        <script type="text/javascript" src="{base_url}jscript/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="{base_url}jscript/bootstrap-wysihtml5/js/bootstrap.min.js"></script>
        <script type="text/javascript" src="{base_url}siac/assets/jscript/bootstrap-tour.js"></script>
        <script type="text/javascript" src="{base_url}siac/assets/jscript/custom-tour.js"></script>
        {js}
    </body>
</html>