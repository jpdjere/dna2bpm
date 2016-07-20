
<?php   

   /// Exporta el archivo a Excell - En {filename} va armada la tabla a exportar.

    header("Content-Description: File Transfer");
    header("Content-type: application/x-msexcel" ); 
    header("Content-Type: application/force-download");
    header("Content-Disposition: attachment; filename=".$filename);
    header("Content-Description: PHP Generated XLS Data" );                
    header("Content-type: text/html; charset=utf-8" ); 
 ?>  
      
        <div class="table table-striped">Fecha desde: {desde} - / - Fecha hasta: {hasta}</div>
            <table  border="1" > 
                
                    <tr>
                        <th>Región</th><TH>Pymes Atendidas</TH><TH>Monto Otorgado</TH><TH>Cant de Pymes(Norm / 1000 pymes)</TH><TH>Monto desembolsado(Norm / 1000 pymes)</TH><TH>Rel: monto desembolsado por región y monto promedio país(Norm / 1000)</TH>
                    </tr>
               
                               
                    {tabla}
                
            </table>    
                 
                    
        
    
        
              
            
                
    

    

