<!-- ==== MSG ==== -->
<div class="alert alert-warning alert-dismissable">
  <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
  <h1 class="error">Errores en la importación del Anexo</h1>
    <div class="alert alert-error" id="{_id}">       
          </p> 
        <h2>Verifique la lista de errores.</h2>
        {if message_header}
            <ol>{message_header}</ol>
        {/if}

        {if message}
            <ol>{message}</ol>
        {/if}
        <hr>
        <p><i class="fa fa-download"></i> <a href=>Descargar Modelo</a> | <i class="fa fa-download"></i> <a href=>Descargar Manual</a>        
        |<!-- <i class="fa fa-backward"></i><a href="../../sgr/"> Volver</a> |--> <i class="fa fa-print"></i> <a href="javascript:window.print()">Imprimir</a></p>
    </div>
</div>
