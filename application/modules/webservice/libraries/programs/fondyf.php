<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class FonDyF {

    public $nombre = 'Fondo Nacional Desarrollo y Fortalecimiento de las Micro, Pequeñas y Medianas Empresas';
    public $tabladest = 'td_fondyf';
    public $tablahist = 'th_fondyf';
    public $cod = 'FonDyF';

    public $where = 8325;
    public $id = 8339;
   
    
    public $titulo = 8666;
    public $estado = 8334;
    public $self = false; // si es true son empresas si no otra entidad
    public $monto = 8573;
    
     public function monto() {  
        return 8573;
    }


    /* EMPRESA */
    public $cuit_value = 1695;
    public $cuit_table = 'td_empresas';
    public $clanae = 4891;
    public $zip = 1698;
    public $pcia = 4651;
    public $localidad = 1700;


    /**/
    public $idpreg = 8334;
    public $value = 90;

}
