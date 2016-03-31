<?php   

   /// Exporta el archivo a Excell - En {filename} va armada la tabla a exportar.

    header("Content-Description: File Transfer");
    header("Content-type: application/x-msexcel" ); 
    header("Content-Type: application/force-download");
    header("Content-Disposition: attachment; filename=Incubadoras.xls");
    header("Content-Description: PHP Generated XLS Data" );                
    header("Content-type: text/html; charset=utf-8" ); 
 ?>  
<table class="table table-bordered table-hover" id='tabla-incubadoras' border="1">
    <tbody id="tabla-body">
    <thead>
          <tr role="row">
              <th>Nombre</th>
              <th>Proyectos Presentados</th>
              <th>Proyectos Pre-Aprobados</th>
              <th>Proyectos Aprobados</th>
              <th>Proyectos Rechazados</th>
              <th>Proyectos Finalizados</th>
              <th>Proyectos Desembolsados</th>
              <th>Desembolsos Realizados</th>
          </tr>
    </thead>


{data}
                <tr>
                <td>{nombre}</td>
                <td>{presentados}</td>
                <td>{pre_aprobados}</td>
                <td>{aprobados}</td>
                <td>{rechazados}</td>
                <td>{finalizados}</td>
                <td>{proyectos_desembolsados}</td>
                <td>{desembolso}</td>
                </tr>
{/data}                
</tbody>
       </table>