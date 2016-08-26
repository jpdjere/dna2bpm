<?php

/**
 * Cálculo de sistema de amortización alemán (aleman_helper.php)
 *
 * @param $C capital
 * Ej: 1500000.00
 * @param $T tasa
 * Ej: 17
 * @param $ptosbon puntos de bonificacion
 * Ej: 5
 * @param $days dias
 * Ej: 31
 * @param $fecha arreglo con la cantidad de dias
 * Ej:array (
2 => '31', 3 => '31', 4 => '30', 5 => '31', 6 => '30', 7 => '31', 8 => '31', 9 => '28', 10 => '31',
11 => '30', 12 => '31', 13 => '30', 14 => '31', 15 => '31', 16 => '30', 17 => '31', 18 => '30',
19 => '31', 20 => '31', 21 => '28', 22 => '31', 23 => '30', 24 => '31', 25 => '30', 26 => '31',
27 => '31', 28 => '30', 29 => '31', 30 => '30', 31 => '31', 32 => '31', 33 => '28', 34 => '31',
35 => '30', 36 => '31', 37 => '30', 38 => '31', 39 => '31', 40 => '30', 41 => '31', 42 => '30',
43 => '31', 44 => '31', 45 => '29', 46 => '31', 47 => '30', 48 => '31', 49 => '30', 50 => '31',
51 => '31', 52 => '30', 53 => '31', 54 => '30', 55 => '31', 56 => '31', 57 => '28', 58 => '31',
59 => '30', 60 => '31',
)
 * @param $n cantidad de cuotas
 * Ej: 60
 * @param $gc gracia capital
 * Ej: 6
 * @param $gi gracia interes
 * Ej: 0
 * @param $frec_cap frecuencia capital
 * Ej: 1
 * @param $frec_int frecuencia interes
 * Ej: 1
 * @return mixed
 * Ej: array (
            array (
                'intereses' => 21657.529999999999,
                'cuota' => 21657.529999999999,
                'amortizacion' => 0,
                'accInt' => 21657.529999999999,
                'remaining' => '1500000.00',
                'bonif' => 6369.8599999999997,
                'periodo' => 1,
                'num_days' => '31',
                'puntos_bon' => 0.050000000000000003,
                'accCap' => 0,
            ),
        )
 *
 */
function frances($C,$T,$ptosbon,$days,$fecha,$n,$gc,$gi=0,$frec_cap,$frec_int){

    global $debug;

    $p=2;//Cantidad de decimales
//-------------------Calculo del calendario
    $arri= $fecha;
    //$n=count($arri); tomamos el que se pasa por parametro
    $arri[1]=$days;

//NOTA ANTES TODAS LAS PRESENCIAS DE $gc y $gi estaban con <= y >=
//---------------------
    if($gc<>0){ //tenemos gracia capital

        for($x=1;$x<=$n;$x++){
            $di_as=$arri[$x];
            $i=($T*$di_as)/36500;
            //echo $x." <- x dias en for del else: ".$gc."<br>";

            if($x>($gi/$frec_cap))$CUOTA=$C/((pow(1+$i,$n-($gc/$frec_cap))-1)/($i*pow((1+$i),$n-($gc/$frec_cap)))); else $CUOTA=0;
            $CUOTA=round($CUOTA,2);

            if($x<=$gc/$frec_cap){
                //$arr[$x]['cuota']=$CUOTA;

                //intereses
                if($x>$gi)$intereses=round($C*$i,$p);else $intereses=0;
                $arr[$x]['intereses']=$intereses;
                $arr[$x]['cuota'] = $intereses;
                //amortizacion o capital
                if($x> $gc/$frec_cap ) $arr[$x]['amortizacion']=$CUOTA - $intereses; else $arr[$x]['amortizacion']=0;
                //acumulado interes
                if($x==1)$arr[$x]['accInt']=$intereses; else $arr[$x]['accInt']=$arr[$x-1]['accInt']+$intereses;
                //remanente
                $arr[$x]['remaining']=$C;
                //bonificacion o alternativa de puntos
                if($x>$gi ) $arr[$x]['bonif']=round($C*$ptosbon*$arri[$x]/36500,2);else $arr[$x]['bonif']=0;

                //extras
                $arr[$x]['periodo']=$x;
                $arr[$x]['num_days']=$arri[$x];
                $arr[$x]['puntos_bon']=$ptosbon/100;

                //accumulado Capital
                if($x==1) $arr[$x]['accCap'] = $arr[$x]['amortizacion'];
                else $arr[$x]['accCap']=$arr[$x-1]['accCap']+$arr[$x]['amortizacion'];

            }else{
                $arr[$x]['cuota']=$CUOTA;

                //remanente
                if($x-1>$gc/$frec_cap) $arr[$x]['remaining']=$arr[$x-1]['remaining']-$arr[$x-1]['amortizacion']; else $arr[$x]['remaining']=$arr[$x-1]['remaining'];
                //interes
                $intereses=round($arr[$x]['remaining']*$i,$p);
                $arr[$x]['intereses']=$intereses;

                //amortizacion o capital
                $arr[$x]['amortizacion']=$CUOTA - $intereses;

                $arr[$x]['accInt']=$arr[$x-1]['accInt']+$intereses;
                //alternativa puntos - bonificacion
                if($x>$gi) $arr[$x]['bonif']=round(($arr[$x-1]['remaining']-$arr[$x-1]['amortizacion'])*$ptosbon*$di_as/36500,2); else $arr[$x]['bonif']=0;

                //extras
                $arr[$x]['periodo']=$x;
                $arr[$x]['num_days']=$di_as;
                $arr[$x]['puntos_bon']=$ptosbon/100;

                //accumulado Capital
                if($x==1) $arr[$x]['accCap'] = $arr[$x]['amortizacion'];
                else $arr[$x]['accCap']=$arr[$x-1]['accCap']+$arr[$x]['amortizacion'];

            }
        }
    }
//---------------------calculo normal--------------------------------
    else{
        for($x=1;$x<=$n;$x++){
            $di_as=$arri[$x];
            $i=($T*$di_as)/36500;
            //echo "<br>x: ".$x." T:".$T."*".$di_as."<br>";
            //$CUOTA=$C/((pow(1+$i,$n-$gc)-1)/($i*pow((1+$i),$n-$gc)));
            //echo "<br>".$C."/((pow(1+".$i.",".$n."-".$gc.")-1)/(".$i."*pow((1+".$i."),".$n."-".$gc.")))";

//$C$2/((1-(1+B34)^-($C$4-$C$5))/B34),0)
            //capital /((1-(1+interes)' - cuotas- gracia))/interes o 0
//$C$2/((1-(1+B34)^-($C$4-$C$5))/B34);0)



            if($x>$gi)$CUOTA=$C/((pow(1+$i,$n-$gc)-1)/($i*pow((1+$i),$n-$gc))); else $CUOTA=0;
            //$CUOTA=$C/((pow(1+$i,$n-$gc)-1)/$i);
            $CUOTA=round($CUOTA,2);

            //echo $CUOTA."<br>";
            if($x==1){
                $arr[1]['cuota']=$CUOTA;
                //interes
                if($x>$gi)$intereses=$C*$i ;else $intereses=0;
                $arr[1]['intereses']=$intereses;
                $arr[1]['accInt']=$intereses;
                //echo "<br>".$x." <-- x cuota: ".$CUOTA." - ".$intereses."<br>";
                //amortizacion o capital
                $arr[1]['amortizacion']=$CUOTA - $intereses;
                //accumulado Capital
                $arr[$x]['accCap'] = $arr[$x]['amortizacion'];

                //remanente
                $arr[1]['remaining']=$C;

                //alternativa puntos - bonificacion
                if($x>$gi) $arr[1]['bonif']=round($C*$ptosbon*$days/36500,2);
                else $arr[1]['bonif']=0;

                //extras
                $arr[1]['periodo']=1;
                $arr[1]['num_days']=$days;
                $arr[1]['puntos_bon']=$ptosbon/100;

            }else{
                $arr[$x]['cuota']=$CUOTA;

                //remanente o saldo
                $arr[$x]['remaining']=$arr[$x-1]['remaining']-$arr[$x-1]['amortizacion'];
                //interes
                if($x>$gi) $intereses=round($arr[$x]['remaining']*$i,$p); else $intereses=0;
                $arr[$x]['intereses']=$intereses;
                $arr[$x]['accInt']=$arr[$x-1]['accInt']+$intereses;

                //echo "<br>".$x." <-- x cuota: ".$CUOTA." - ".$intereses."<br>";
                //amortizacion o capital
                $arr[$x]['amortizacion']=$CUOTA - $intereses;//para mi esto es el cap-el $arr[acccap]   ($capital-$arr[$i]['accCap'])
                //accumulado Capital
                $arr[$x]['accCap']=$arr[$x-1]['accCap']+$arr[$x]['amortizacion'];

                //extras
                $arr[$x]['num_days']=$di_as;
                $arr[$x]['puntos_bon']=$ptosbon/100;
                $arr[$x]['periodo']=$x;

                //alternativa puntos - bonificacion
                if($x>$gi) $arr[$x]['bonif']=round(($arr[$x-1]['remaining']-$arr[$x-1]['amortizacion'])*$ptosbon*$di_as/36500,2);
                else $arr[$x]['bonif']=0;
            }
        }
    }
    if($debug){
        ?>
        <table border='1' cellpadding="2" cellspacing="0">
            <tr><th>periodo</th><th>Cuota</th><th>Intereses</th><th>Amortizacion</th><th>AccInt</th><th>Restante</th></tr>
            <?php foreach($arr as $cuota){
                echo "<td>".$cuota['periodo']."</td>";
                echo "<td>$".number_format($cuota[cuota],$p)."</td>";
                echo "<td>$".number_format($cuota[intereses],$p)."</td>";
                echo "<td>$".number_format($cuota[amortizacion],$p)."</td>";
                echo "<td>$".number_format($cuota[accInt],$p)."</td>";
                echo "<td>$".number_format($cuota[remaining],$p)."</td>";
                echo "</tr>\n";
            }
            ?></table>
    <?php }
    return $arr;}//---end function
?>