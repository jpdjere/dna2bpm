<!DOCTYPE html>
<html>
    <head>
        <title>{title}</title>
        <meta charset="UTF-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <link rel="stylesheet" href="{base_url}jscript/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" href="{base_url}jscript/bootstrap/css/bootstrap-responsive.min.css" />
        <link rel="stylesheet" href="{base_url}jscript/fontawesome/css/font-awesome.min.css" />
        <link rel="stylesheet" href="{base_url}dna2/assets/css/unicorn.main.css" />
        <link rel="stylesheet" href="{base_url}dna2/assets/css/unicorn.grey.css" class="skin-color" />
        <!-- CSS -->
        {css}

        <link href="{base_url}jscript/bootstrap/css/bootstrap-responsive.min.css" rel="stylesheet">
    </head>
    <body>
        <div class="widget-box">
            <div class="widget-title">
                <span class="icon"><i class="icon-list"></i></span>
                <h5>
                    List ::{kpi title}
                </h5>
                <span class="label label-warning tip-left" title="" data-original-title="Page">pag: {page} / {pages}</span>

            </div>
            {content}
            <div id="pagination">
                {lang records} {start} {lang to} {top} {lang of} {total} <br/> {pagination}
            </div>
        </div>

        <div id="loading-msg"></div>
        <!-- Boot -->
        <script type="text/javascript">
            //-----declare global vars
            var globals = {inline_js};
        </script>
        <script type="text/javascript" src="{base_url}jscript/jquery/jquery.min.js"></script>
        <script type="text/javascript" src="{base_url}jscript/bootstrap/js/bootstrap.min.js"></script>
        <script src="{base_url}dna2/assets/jscript/unicorn.js"></script>
        {js}
    </body>
</html>