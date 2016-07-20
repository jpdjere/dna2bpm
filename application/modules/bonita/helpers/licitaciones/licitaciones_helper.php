<?php

function calcular_total_ofrecido($datos_entidades){
    $total_ofrecido=0;
    foreach($datos_entidades as $entidad){
        $total_ofrecido=$total_ofrecido+$entidad['monto'];
    }
    return $total_ofrecido;
}

function calcular_asignacion_primaria($datos_entidades, $maxeeff, $cmax, $total_ofrecido){
    $asignacion_primaria=array();
    $i=0;
    foreach($datos_entidades as $entidad){
        $monto_ofrecido=$entidad['monto'];
        $porc_oferta_total=$entidad['monto']/$total_ofrecido;
        $asignacion_primaria['porcentaje'][$i]=$porc_oferta_total;
        if($porc_oferta_total*$cmax>=$maxeeff){
            $asignacion_primaria['asignacion'][$i]=round($maxeeff);
        }else if($porc_oferta_total*$cmax>$monto_ofrecido){
            $asignacion_primaria['asignacion'][$i]=round($monto_ofrecido);
        }else{
            $asignacion_primaria['asignacion'][$i]=round($porc_oferta_total*$cmax);
        }
        $i+=1;
    }
    return $asignacion_primaria;
}

function calcular_asignacion_generica($cmax, $maxeeff, $total_asignacion_anterior, $datos_entidades, $asignacion_anterior){
    
    //calculos generales
    $faltante_asignacion=$cmax-$total_asignacion_anterior;
    
    if($faltante_asignacion==0){return;}
    
    //calculos particulares por entidad
    $total_ofertado=0;
    
    $i=0;
    foreach($datos_entidades as $entidad){
        if($asignacion_anterior[$i]==$maxeeff){
            $datos_entidades[$i]['ofertado']=0;
        }else{
            $datos_entidades[$i]['ofertado']=$entidad['monto'];
            $total_ofertado+=$entidad['monto'];
        }
        $i+=1;
    }
    
    $asignacion_generica=array();
    $i=0;
    
    foreach($datos_entidades as $entidad){
        if($asignacion_anterior[$i]==$maxeeff){
            $asignacion_generica[]=round($asignacion_anterior[$i]);
        }else{
            $porc_faltante=$datos_entidades[$i]['ofertado']/$total_ofertado;
            $pot_asignacion=$porc_faltante*$faltante_asignacion;
            
            if($asignacion_anterior[$i]+$pot_asignacion>$entidad['monto']){
                if($entidad['monto']<$maxeeff){
                    $asignacion_generica[]=round($entidad['monto']);
                }else{
                    $asignacion_generica[]=round($maxeeff);
                }
            }else{
                if($asignacion_anterior[$i]+$pot_asignacion>$maxeeff){
                    $asignacion_generica[]=round($maxeeff);
                }else{
                    $asignacion_generica[]=round($asignacion_anterior[$i]+$pot_asignacion);
                }
            }
        }
        $i+=1;
    }
    
    $i=0;
    foreach($datos_entidades as $entidad){
        if($asignacion_anterior[$i]<$maxeeff){
            $nueva_oferta_total+=$entidad['monto'];
        }
        $i+=1;
    }
    
    $i=0;
    $total_asignacion_actual=array_sum($asignacion_generica);
    $nuevo_faltante_asignacion=$cmax-$total_asignacion_actual;
    
    return $asignacion_generica;
}

function calcular_ultima_asignacion($cmax, $maxeeff, $total_asignacion_anterior, $datos_entidades, $asignacion_anterior, $todas_asignaciones){
    $nuevo_faltante_asignacion=$cmax-$total_asignacion_anterior;
    $i=0;
    $oferta_total=0;
    foreach($datos_entidades as $entidad){
        $nueva_oferta=0;
        if($asignacion_anterior[$i]<$entidad['monto']){
            $nueva_oferta=$entidad['monto'];
        }
        $oferta_total+=$nueva_oferta;
        $i+=1;
    }
    
    $i=0;
    foreach($datos_entidades as $entidad){
        //Oferta
        $nueva_oferta=0;
        $nuevo_porc_faltante=0;
        $nuevo_pot_asignacion=0;
        if($asignacion_anterior[$i]<$entidad['monto']){
            $nueva_oferta=$entidad['monto'];
        }
        //echo $nueva_oferta;exit;
        //Porcentaje faltante
        if($nueva_oferta!=0){
            $nuevo_porc_faltante=$nueva_oferta/$oferta_total;
        }
        
        //Potencial Asignacion
        if($nuevo_porc_faltante!=0){
            $nuevo_pot_asignacion=$nuevo_porc_faltante*$nuevo_faltante_asignacion;
        }

        //Asignacion final
        if($nuevo_pot_asignacion!=0){
            $asignacion_generica[]=$asignacion_anterior[$i]+$nuevo_pot_asignacion;
        }else{
            $asignacion_generica[]=$asignacion_anterior[$i];
        }
        $i+=1;
    }
    return $asignacion_generica;
}

function get_nombres_asignacion(){
    $numeros=explode(" ","PRIMARIA SECUNDARIA TERCIARIA CUATERNARIA QUINARIA");
    $nombres=array();
    foreach($numeros as $numero){
        $nombres[]="ASIGNACIÓN ".$numero;
    }
    return $nombres;
}

function get_file_name($nombre){
    return $nombre.'-'.date("y-m-d").rand(1000, 5000) .'.xls';
}