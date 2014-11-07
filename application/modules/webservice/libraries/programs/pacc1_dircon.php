<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class PACC1_DIRCON {
    
    public $nombre = 'PACC 1.1 DIRCON';
    public $cod = 'PACC1 DIRCON';
    public $where = array (
            6364 
    );
    public $id = 6390;
    public $tabladest = 'td_pacc_1';
    public $titulo = 5673;
    public $url = '/frontcustom/248/pacc1.externo.print.php';
    public $estado = 6225;
    public $self = false; // si es true son empresas si no otra entidad
    
    function monto() {
        return 6385;
    }

}