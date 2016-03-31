<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class PACC1 {

    public $nombre = 'PACC 1.1';
    public $tabladest = 'td_pacc_1';
    public $tablahist = 'th_pacc_1';
    public $cod = 'PACC1';

    public $where = 6223;
    public $id = 6390;
    public $titulo = 5673;
    public $url = 'RenderEdit/editnew.php?idvista=3133&origen=V&idap=7';
    public $estado = 6225;
    public $self = false; // si es true son empresas si no otra entidad
    public $monto = 6385;
    
    public function monto() {  
        return 6385;
    }
    
    

    /* EMPRESA */
    public $cuit_value = 1695;
    public $cuit_table = 'td_empresas';
    public $clanae = 4891;
    public $zip = 1698;
    public $pcia = 4651;
    public $localidad = 1700;


    /**/
    public $idpreg = 6225;
    public $value = 120;

}
