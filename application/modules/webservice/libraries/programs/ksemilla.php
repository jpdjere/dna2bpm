<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');



class KSEMILLA {   
   
    
    public $nombre = 'Capital Semilla';
    public $cod = 'PACC3';
    public $where = array (
            5692,
            5693 
    );
    public $id = 5928;
    public $tabladest = 'td_pacc';
    public $titulo = 5673;
    public $url = '/RenderEdit/editnew.php?idvista=1761&idap=238&origen=V';
    public $estado = 5925;
    public $self = false; // si es true son empresas si no otra entidad
    
    function monto() {              
        /*
         * Para K semilla MONTO 5904 = 1 => 6193 5904 = 2/3 => 5931
         */
        $cat = 5904;
        if ($cat == 1) {
            $monto = 5904;
        } else {
            // --es categoria 2 Ã³ 3
            $monto = 5931;
        }
        return $monto;
    }

}