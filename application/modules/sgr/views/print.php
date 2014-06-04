<!DOCTYPE html>
<html>
    <head>
        <title>DNA&sup2; | SGR | {sgr_nombre}</title>
        <meta charset="UTF-8" />        
        <link rel="stylesheet" href="{module_url}assets/css/sgr.css" />
        <!-- <link rel="stylesheet"  href="{module_url}assets/css/print.css"> --> 
        <style>

            th, td { padding: .1em; }
            th, thead { background: #fff; color: #000; border: 1px solid #777; font-weight:bold; }  
            td { border: 1px solid #777;  }

            table {border:1px solid #ccc;font-family: Futura, Arial, sans-serif; font-size:7px; text-align: center; width:100%;}
        </style>
    </head>
    <body>
    <body>
        <div class=row" align="center">
            <img src="{logo}">
            <h2>{sgr_nombre} | C.U.I.T.: <span class="text-info">{sgr_cuit}</span></h2>
            <p>[Anexo {anexo_short}] SGR {anexo_title} - Importado por: [{user_print}]</p>
            <p><small>Archivo Procesado: {parameter}</small></p>                               
            <p><span class="">
                    Información correspondiente al período {print_period}
                </span></p>		
        </div>
        {show_table}

        {if show_footer}
        <ul><li>NOTA:<small>{show_footer}</small></li></ul>
        {/if}
    </div>
</body>
</html>

