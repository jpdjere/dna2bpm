<H3>REPORTES LICITACIONES CERRADAS</H3>
    </br>
    <table class="table">
        <tr>
            <td>RESOLUCIÓN:</td>
            <td>FECHA DE LICITACIÓN:</td>
            <td>FECHA DE CIERRE:</td>
            <td>CUPO MÁXIMO:</td>
            <td>MÁXIMO POR ENTIDAD FINANCIERA:</td>
            <td colspan="2">ANEXOS:</td>
        </tr>
        {datos_licitacion}
    </table>
    <table class="table">
        <tr>
            <td>N°:</td>
            <td>ENTDIDAD FINANCIERA:</td>
            <td>MONTO OFRECIODO (En Millones de $):</td>
            <td>MONTO OFRECIDO:</td>
            <td>% SOBRE OFERTA TOTAL:</td>
        </tr>
        {lista_ofertas}
    </table>
        <a id="eexportar" class="btn btn-primary btn-xs" target="_blank" method="POST" href="{base_url}bonita/Bonita_licitaciones/descarga_anexoI?id=<?php echo $_GET['id'];?>"/>
        Exportar</a>
