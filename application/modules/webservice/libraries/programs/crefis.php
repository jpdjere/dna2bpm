<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class CreFis {

    public $nombre = 'Credito Fiscal';
    public $tabladest = 'td_crefis';
    public $tablahist = 'th_crefis';
    public $cod = 'CreFis';

    public $where = 4844;
    public $id = 4837;
    public $titulo = 4842;
    public $url = 'RenderEdit/editnew.php?idvista=3133&origen=V&idap=7';
    public $estado = 4970;
    public $self = false; // si es true son empresas si no otra entidad
    public $monto = 5040;
    
     function monto() {  
        return 5040;
    }
    
    

    /* EMPRESA */
    public $cuit_value = 1695;
    public $cuit_table = 'td_empresas';
    public $clanae = 4891;
    public $zip = 1698;
    public $pcia = 4651;
    public $localidad = 1700;


    /**/
    public $idpreg = 4970;
    public $value = 90;

}
