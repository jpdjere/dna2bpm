<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');



class CreFis {

    public $nombre = 'Credito Fiscal';
    public $tabladest = 'td_crefis';
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

    function monto() {       
        return 5040;
    }
    
    
  

}