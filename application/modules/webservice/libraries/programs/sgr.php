<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');



class SGR {
    
    // tipo operacion 5779
    public $nombre = 'SGR';
    public $tabladest = 'td_sgr';
    public $cod = 'SGR';
    public $where = array (
            5272 
    );
    public $id = 0;
    public $titulo = 1693;
    public $url = '/frontcustom/245/pacc.externo.print.php';
    public $estado = 5272;
    public $self = true; // si es true son empresas si no otra entidad
    
    function monto($idrel) {
        return 0;
    }

}
