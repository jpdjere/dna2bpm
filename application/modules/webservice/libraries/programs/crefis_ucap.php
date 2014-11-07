<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');



// ---pegada como UCAP
class CreFis_UCAP {
	public $nombre = 'Credito Fiscal - UCAP';
	public $cod = 'CreFis';
        public $tabladest = 'td_crefis';
	public $where = array (
			4846
	);
	public $id = 4837;
	public $titulo = 4842;
	public $url = 'frontcustom/231/interno.print.php?a=1';
	public $estado = 4970;
        public $self = false; // si es true son empresas si no otra entidad

	function monto() {
		return 5040;
	}

}
