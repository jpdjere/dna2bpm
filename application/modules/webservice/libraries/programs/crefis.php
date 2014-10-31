<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include("functions.php");

class CreFis {

    public $nombre = 'Credito Fiscal';
    public $cod = 'CreFis';
    public $where = array(
        4844,
        4845
    );
    public $id = 4837;
    public $titulo = 4842;
    public $url = 'RenderEdit/editnew.php?idvista=3133&origen=V&idap=7';
    public $estado = 4970;
    public $self = false; // si es true son empresas si no otra entidad

    function monto($idrel) {
        $functions = $this->functions = new functions();
        return $functions->getvalue($idrel, 5040);
    }
    
    
  

}