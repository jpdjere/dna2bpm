<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');



class PACC1 {    
    public $nombre = 'PACC 1.1';
    public $cod = 'PACC1';
    public $where = array (
            6223 
    );
    public $id = 6390;    
    public $tabladest = 'td_pacc_1';
    public $titulo = 5673;
    public $url = 'RenderEdit/editnew.php?idvista=3141&origen=V&idap=7';
    public $estado = 6225;
    public $self = false; // si es true son empresas si no otra entidad
    
    function monto() {                     
        return 6385;
    }

}