    
    <?php   

   /// Exporta el archivo a Excell - En {filename} va armada la tabla a exportar.

    header("Content-Description: File Transfer");
    header("Content-type: application/x-msexcel" ); 
    header("Content-Type: application/force-download");
    header("Content-Disposition: attachment; filename=".$new_filename);
    header("Content-Description: PHP Generated XLS Data" );                
    header("Content-type: text/html; charset=utf-8" ); 
 ?>
 
<H3 colspan="5">REPORTES LICITACIONES CERRADAS: ANEXO I</H3>
    <table>
        <tr>
            <td>RESOLUCI&#211;N:</td>
            <td>FECHA DE LICITACI&#211;N:</td>
            <td>FECHA DE CIERRE:</td>
            <td>CUPO M&#193;XIMO:</td>
            <td>M&#193;XIMO POR ENTIDAD FINANCIERA:</td>
        </tr>
        {datos_licitacion}
    </table>
    </br>
    <table>
        <tr>
            <td>N&#186;:</td>
            <td>ENTDIDAD FINANCIERA:</td>
            <td>MONTO OFRECIODO (En Millones de $):</td>
            <td>MONTO OFRECIODO:</td>
            <td>% SOBRE OFERTA TOTAL:</td>
        </tr>
        {lista_ofertas}
    </table>