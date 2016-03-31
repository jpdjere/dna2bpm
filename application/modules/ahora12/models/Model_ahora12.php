<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Description of Ahora12
 *
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 */
class Model_Ahora12 extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->ahora12_db=$this->load->database('ahora12',true);
    }

    function current_range(){
        $SQL="select DATE_FORMAT(MAX(CORTE),'%d/%m/%Y') AS CORTE FROM xProvincia";
        $rs=$this->ahora12_db->query($SQL)->row();
        $corte=$rs->CORTE;
        return $corte;
    }
    
    function rango_rubro(){
        $SQL="select DATE_FORMAT(MAX(CORTE),'%d/%m/%Y') AS CORTE FROM xRubro";
        $rs=$this->ahora12_db->query($SQL)->row();
        $corte=$rs->CORTE;
        return $corte;
    }

    function xTarjeta(){
        $SQL="select MAX(CORTE) AS CORTE FROM sumas";
        $rs=$this->ahora12_db->query($SQL)->row();
        $corte=$rs->CORTE;
        $SQL="SELECT * FROM sumas WHERE CORTE='$corte'";
    return $this->ahora12_db->query($SQL)->result_array();
    }

    function get_periodos(){
        $SQL="SELECT DISTINCT(CORTE) as periodo FROM sumas";
        return $this->ahora12_db->query($SQL)->result_array();
    }
    function ultimo_xProvincia(){
        $SQL="
    SELECT 
    CORTE,
    SUM(`CUITS`) AS `CUITS`,
    SUM(`LOCALES`) AS `LOCALES`,
    SUM(`MONTO_VENTAS`) AS `MONTO_VENTAS`,
    SUM(`OPERACIONES`) AS `OPERACIONES`,
    `ahora13`.`xProvincia`.`id_prov` AS `id_prov`,
    `ahora13`.`xProvincia`.`detalle_prov` AS `detalle_prov`
    FROM ahora13.xProvincia
    WHERE CORTE=(SELECT date(MAX(STR_TO_DATE(`Fecha_Movimiento`, '%d/%m/%Y'))) FROM visa)
    GROUP BY id_prov;";
    $rs=$this->ahora12_db->query($SQL)->result_array();
    return $rs;
    }
    function ultimo_xRubro(){
        $SQL="
SELECT 
    CORTE,
    SUM(`CUITS`) AS `CUITS`,
    SUM(`LOCALES`) AS `LOCALES`,
    SUM(`MONTO_VENTAS`) AS `MONTO_VENTAS`,
    SUM(`OPERACIONES`) AS `OPERACIONES`,RUBRO
    FROM ahora13.xRubro
    WHERE CORTE=(SELECT date(MAX(STR_TO_DATE(`Fecha_Movimiento`, '%d/%m/%Y'))) FROM visa)
    GROUP BY Rubro";
         $rs=$this->ahora12_db->query($SQL)->result_array();
        // $rs=array();
        return $rs;
    }

    function xProvincia($corte){
        $SQL="SELECT date(MAX(STR_TO_DATE(`Fecha_Movimiento`, '%d/%m/%Y'))) FROM visa INTO @corte;
SELECT
    CORTE,
    SUM(`CUITS`) AS `CUITS`,
    SUM(`LOCALES`) AS `LOCALES`,
    SUM(`MONTO_VENTAS`) AS `MONTO_VENTAS`,
    SUM(`OPERACIONES`) AS `OPERACIONES`,
    `ahora13`.`xProvincia`.`id_prov` AS `id_prov`,
    `ahora13`.`xProvincia`.`detalle_prov` AS `detalle_prov`

    FROM ahora13.xProvincia
    WHERE CORTE=@corte
    GROUP BY id_prov";
        $rs=$this->ahora12_db->query($SQL)->result_array();
        return $rs;
    }
    
    function montos_x_corte(){
        $SQL="SELECT CORTE AS FECHA,SUM(MONTO_VENTAS) AS MONTO_VENTAS FROM sumas GROUP BY CORTE";
        $rs=$this->ahora12_db->query($SQL)->result_array();
        return $rs;
    }
}
