<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class PACC3 {

    public $nombre = 'PACC 1.3';
    public $tabladest = 'td_pacc';
    public $tablahist = 'th_pacc';
    public $cod = 'PACC3';
    public $where = 6065;
    public $id = 5691;
    public $titulo = 5673;
    public $url = '/frontcustom/248/pacc1.externo.print.php';
    public $estado = 5689;
    public $self = false; // si es true son empresas si no otra entidad
    public $monto = 7048;
    
    
    function monto() {  
        return 7048;
    }


    /* EMPRESA */
    public $cuit_value = 1695;
    public $cuit_table = 'td_empresas';
    public $clanae = 4891;
    public $zip = 1698;
    public $pcia = 4651;
    public $localidad = 1700;


    /**/
    public $idpreg = 5689;
    public $value = 120;

}
