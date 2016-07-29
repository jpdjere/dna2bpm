<?php

function control1($data){
	//echo "INSERTAMOS:".$insertamos."<BR>";
	$verificar= $_REQUEST['verificar'];
	//Validamos la integridad de la informacion que se va a insertar 
	for($i=4;$i<=$data->sheets[0]['numRows'];$i++){
		$row=$data->sheets[0]['cells'][$i];
                
                //la res Res.202/2012 y 290/2013 tiene que permitir subir varias veces el mismo prestamo para aceptar el calculo de cuotas
                if($row[42]!="Res.202/2012" && $row[42]!="Res.290/2013"){
                //Verificar que no este repetido en el excel antes de ingresarlo a la base			
                $SQL= "SELECT COUNT(id) as cantidad FROM `".$tb_temp_bon."` WHERE  `efi` =  '".$efi."' and `dispo` =  '".$row[42]."' and `prestamo` =  '".$row[21]."'";
                //echo $SQL."<br />";
                $rsSocio=$importar->Execute($SQL) or DIE ($importar->ErrorMsg()."<br />$SQL<br />Line:".__LINE__.'<br/>');	
                if($rsSocio->RecordCount()){							
                        if($rsSocio->fields('cantidad')>1){
                                $proc=0;	
                                $showError[] = '<li>Es posible que el nro de prestamo: '.$row[21].' de la dispo: '.$row[42].' en la fila '.$i.' este repetida dentro de el excel.</li>';	
                        }
                }
                
		//verificamos siempre que el prestamo a subir no este ya ingresado en la base si el "verificar" esta en true
		if($verificar=="true"){
			$dispo 	= 	str_replace('/','.',$row[42]);
			$SQL="SELECT COUNT(*) as cantidad FROM `bonita_prestamos` WHERE  `efi` =  '".$efi."' and `dispo` =  '".$dispo."' and `nro` =  '".$row[21]."'";//echo $SQL."<br />";
				$rsSocio=$importar->Execute($SQL) or DIE ($importar->ErrorMsg()."<br />$SQL<br />Line:".__LINE__.'<br/>');	
				if($rsSocio->RecordCount()){							
					if($rsSocio->fields('cantidad')>=1){
						$showError[] = '<li>El nro de prestamo: '.$row[21].' de la dispo: '.$row[42].' en la fila '.$i.' ya fue ingresado a la base de datos.</li>';
						$proc=0;	
					}
				}
		}
                }//if de la Res.202/2012 o 290/2013
		
		//verificamos que en la res.163.2011 se cumpla que el plazo en meses sea de 12,24,36 o 48 para el correcto calculo de los puntos
		if($row[42]=="Res.163/2011"){
			if($row[25]==12 || $row[25]==24 || $row[25]==36 || $row[25]==48){
			  // echo '<b class="ok">Res.163.2011 verificacion de plazo por mes fila '.$i.': OK!</b><br /><br />'; 
			}else{
				$proc=0;	
				$showError[] = '<li>El "Plazo total del prestamo en meses": '.$row[25].' en la fila '.$i.' no es correcto para la Res.163.2011 que solo admite 12, 24, 36 o 48 meses.</li>';	
			}
		}
		
		//verificamos que si dice "Garantia SGR" SI este ingresada la "SGR involucrada" 
		if($row[38]=="SI"){
			if($row[39]==''){
				$proc=0;	
				$showError[] = '<li>La SGR involucrada no puede ser vacia si la Garantia SGR es igual a SI, fila '.$i.'.</li>';	
			}
		}

	}//for
/////
if ($proc==1 && count($err)==0){
	//como esta todo ok, enviamos el nombre del archivo
	$jsondata['archivito'] = urlencode($myFile);

}else{//no proc
	$proc=0;
//	echo count($err)."<br>";
	if(count($err)){
		$tit_error = "<b>SE ENCONTRARON ERRORES EN LOS DATOS DEL EXCEL</b><br />";
		$proc=0;
			foreach($err as $error)
				$lista_error.= $error;
	}
//	print_r($lista_error);
	if(!empty($showError)){
		$lista_error.= "Detalle de Errores:";
		$resultError = @array_unique($showError); 		
		$lista_error.= "<div id=errorPre><ol>";
		
		foreach($resultError as $showResults)
			$lista_error.= $showResults;
		
		$lista_error.= "</ol></div>";
	}

	//como los archivos pueden ser muchos vamos a generar un archivo con los errores y luego abrimos el mismo 
	$archivoErrores=fopen("errores/errores_".$bonitaIDU.".txt" ,"w+");
	if ($archivoErrores)
		fwrite($archivoErrores, $checkheader.$tit_error.$lista_error);
	fclose ($archivoErrores);
	

}//else
$jsondata['cabecera']=urlencode($bonitaIDU);
$jsondata['validado'] = $proc;
echo json_encode($jsondata);

//borramos la tabla temporal de los cedentes
	$SQL="DROP TABLE IF EXISTS `".$tb_temp_bon."`";
	$rs=$importar->Execute($SQL) or DIE ($importar->ErrorMsg()."<br />$SQL<br />Line:".__LINE__.'<br/>');
        
}