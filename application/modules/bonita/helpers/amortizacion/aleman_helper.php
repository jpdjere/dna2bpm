<?php

/**
 * Cálculo de sistema de amortización alemán (aleman_helper.php)
 *
 * @param $C capital
 * Ej: 1500000.00
 * @param $T tasa
 * Ej: 22
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
                'amortizacion' => 0,
                'remaining' => '1500000.00',
                'intereses' => 21657.529999999999,
                'cuota' => 21657.529999999999,
                'accInt' => 21657.529999999999,
                'bonif' => 6369.8599999999997,
                'periodo' => 1,
                'num_days' => '31',
                'puntos_bon' => 0.050000000000000003,
                'accCap' => 0,
                ),
       )
 *
 */
function aleman($C,$T,$ptosbon,$days,$fecha,$n,$gc,$gi=0,$frec_cap,$frec_int){

    global $debug;

    $p=2;//Cantidad de decimales

    //Calculo del calendario
    $arri= $fecha;
    if($debug) print_r($arri);
    $arri[1]=$days;

//NOTA ANTES TODAS LAS PRESENCIAS DE $gc y $gi estaban con <= y >=

//--------------------- //tenemos gracia capital

//if($gc<>0){
    for($x=1;$x<=$n;$x++){
        $di_as=$arri[$x];
        //$i=($T*$di_as)/36500;
        $i=((($T-$ptosbon))*$di_as)/36500;

        //echo $x." <- x ".$T."<- T  dias->".$di_as." i -> ".$i."<br>";

        //amortizacion o capital
        if($x>($gc/$frec_cap )) $arr[$x]['amortizacion'] = $C/($n-($gc/$frec_cap)); else $arr[$x]['amortizacion']=0;

        //saldo de deuda al inicio - remanente
        if($x==1) $arr[$x]['remaining'] = $C;
        else{
            if($x-1>($gc/$frec_cap )) $arr[$x]['remaining'] = $arr[$x-1]['remaining']-$arr[$x-1]['amortizacion'];
            else $arr[$x]['remaining']=$arr[$x-1]['remaining'];
        }
        //interes
        if($x>$gi) $arr[$x]['intereses'] = round($arr[$x]['remaining']*$i,$p); else $arr[$x]['intereses']=0;

        //cuota
        if($x>$gi)$arr[$x]['cuota'] = round($arr[$x]['amortizacion']+$arr[$x]['intereses'],2); else $arr[$x]['cuota'] = 1;

        //acumulado intereses
        if($x==1) $arr[$x]['accInt'] = $arr[$x]['intereses']; else $arr[$x]['accInt']=$arr[$x-1]['accInt']+$arr[$x]['intereses'];

        //alternativa puntos - bonificacion
        if($x>$gi) $arr[$x]['bonif']=round($arr[$x]['remaining']*$ptosbon*$arri[$x]/36500,2);else $arr[$x]['bonif']=0;

        //extras
        $arr[$x]['periodo']=$x;
        $arr[$x]['num_days']=$di_as;
        $arr[$x]['puntos_bon']=$ptosbon/100;

        //accumulado Capital
        if($x==1) $arr[$x]['accCap'] = $arr[$x]['amortizacion'];
        else $arr[$x]['accCap']=$arr[$x-1]['accCap']+$arr[$x]['amortizacion'];


        // }
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
            ?>
        </table>
    <?php }

    return $arr; }//---end function
?>