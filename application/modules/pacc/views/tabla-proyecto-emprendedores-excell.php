<?php   

   /// Exporta el archivo a Excell - En {filename} va armada la tabla a exportar.

    header("Content-Description: File Transfer");
    header("Content-type: application/x-msexcel" ); 
    header("Content-Type: application/force-download");
    header("Content-Disposition: attachment; filename=".$title.".xls");
    header("Content-Description: PHP Generated XLS Data" );                
    header("Content-type: text/html; charset=utf-8" ); 
 ?>  

<table class="table table-bordered table-hover" border="1">
    <thead>
          <tr role="row">
              <th>Provincia</th>
              <th>Cantidad de Incubadoras</th>
              <th>Proyectos Presentados</th>
              <th>Proyectos Pre-Aprobados</th>
              <th>Proyectos Aprobados</th>
              <th>Proyectos Rechazados</th>
              <th>Proyectos Finalizados</th>
              <th>Proyectos Desembolsados</th>
              <th>Desembolsos realizados</th>
          </tr>
    </thead>
          <tbody>
            {proyectos}
              <tr >
                <td>{provincia}</td>
                <td>{incubadoras}</td>
                <td>{presentados cantidad}</td>
                <td>{pre_aprobados cantidad}</td>
                <td>{aprobados cantidad}</td>
                <td>{rechazados cantidad}</td>
                <td>{finalizados cantidad}</td>
                <td>{proyectos desembolsados}</td>
                <td>{desembolso}</td>
              </tr>
            {/proyectos}  
          </tbody>
      </table>
    
                  
                            
