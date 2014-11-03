<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

include("functions.php");

class CreFis {

	public $nombre = 'Credito Fiscal';
	public $cod = 'CreFis';
	public $where = array (
			4844,
			4845
	);
	public $id = 4837;
	public $titulo = 4842;
	public $url = 'RenderEdit/editnew.php?idvista=3133&origen=V&idap=7';
	public $estado = 4970;
	public $self = false; // si es true son empresas si no otra entidad

	function monto($idrel) {
		return getvalue ( $idrel, 5040 );
	}

}

// ---pegada como UCAP
class CreFis_UCAP {

	public $nombre = 'Credito Fiscal - UCAP';
	public $cod = 'CreFis';
	public $where = array (
			4846
	);
	public $id = 4837;
	public $titulo = 4842;
	public $url = 'frontcustom/231/interno.print.php?a=1';
	public $estado = 4970;

	function monto($idrel) {
		return getvalue ( $idrel, 5040 );
	}

}

class PACC1 {

	public $nombre = 'PACC 1.1';
	public $cod = 'PACC1';
	public $where = array (
			6223
	);
	public $id = 6390;
	public $titulo = 5673;
	public $url = 'RenderEdit/editnew.php?idvista=3141&origen=V&idap=7';
	public $estado = 6225;

	function monto($idrel) {
		return 'Total:' . getvalue ( $idrel, 6385 ) . '</br> ANR:' . getvalue ( $idrel, 6386 );
	}

}

class PACC1_DIRCON {

	public $nombre = 'PACC 1.1 DIRCON';
	public $cod = 'PACC1 DIRCON';
	public $where = array (
			6364
	);
	public $id = 6390;
	public $titulo = 5673;
	public $url = '/frontcustom/248/pacc1.externo.print.php';
	public $estado = 6225;
	public $self = false; // si es true son empresas si no otra entidad

	function monto($idrel) {
		return 'Total:' . getvalue ( $idrel, 6385 ) . '</br> ANR:' . getvalue ( $idrel, 6386 );
	}

}

class PACC13 {

	public $nombre = 'PACC 1.3';
	public $cod = 'PACC3';
	public $where = array (
			6065
	);
	public $id = 5691;
	public $titulo = 5673;
	public $url = 'RenderEdit/editnew.php?idvista=1655&origen=V&idap=7';
	public $estado = 5689;
	public $self = false; // si es true son empresas si no otra entidad

	function monto($idrel) {
		return 'Total:' . getvalue ( $idrel, 6057 ) . '</br> ANR:' . getvalue ( $idrel, 6058 );
	}

}

class PACC13_DIRCON {

	public $nombre = 'PACC 1.3 DIRCON';
	public $cod = 'PACC3 DIRCON';
	public $where = array (
			6065
	);
	public $id = 5691;
	public $titulo = 5673;
	public $url = '/frontcustom/245/pacc.externo.print.php';
	public $estado = 5689;
	public $self = false; // si es true son empresas si no otra entidad

	function monto($idrel) {
		return 'Total:' . getvalue ( $idrel, 6057 ) . '</br> ANR:' . getvalue ( $idrel, 6058 );
	}

}

// class SGR {
//
// //tipo operacion 5779
// public $nombre = 'SGR';
// public $cod = 'SGR';
// public $where = array(5272);
// public $id = 0;
// public $titulo = 1693;
// public $url = '/frontcustom/245/pacc.externo.print.php';
// public $estado = 5272;
// public $self = true; // si es true son empresas si no otra entidad
//
// function monto($idrel) {
// return 0;
// }
//
// }
class SGR {

	// tipo operacion 5779
	public $nombre = 'SGR';
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

class EXPYME {

	public $nombre = 'Expertos PyME';
	public $cod = 'EXPYME';
	public $where = array (
			3264
	);
	public $id = 5509;
	public $titulo = 5673;
	public $url = 'RenderEdit/editnew.php?idvista=3146&origen=V&idap=7';
	public $estado = 3293;
	public $self = false; // si es true son empresas si no otra entidad

	function monto($idrel) {
		$eval = getvalue ( $idrel, 3543 );
		$eval = str_replace ( '.', '', $eval );
		$eval = str_replace ( ',', '.', $eval );

		$imp = getvalue ( $idrel, 3543 );
		$imp = str_replace ( '.', '', $imp );
		$imp = str_replace ( ',', '.', $imp );
		return 'Eval: ' . $eval . ' +Imp:' . $imp . ' =' . ( double ) $eval + ( double ) $imp;
	}

}

class KSEMILLA {

	public $nombre = 'Capital Semilla';
	public $cod = 'PACC3';
	public $where = array (
			5692,
			5693
	);
	public $id = 5928;
	public $titulo = 5673;
	public $url = '/RenderEdit/editnew.php?idvista=1761&idap=238&origen=V';
	public $estado = 5925;
	public $self = false; // si es true son empresas si no otra entidad

	function monto($idrel) {
		/*
		 * Para K semilla MONTO 5904 = 1 => 6193 5904 = 2/3 => 5931
		 */
		$cat = getvalue ( $idrel, 5904 );
		if ($cat == 1) {
			$monto = getvalue ( $idrel, 5904 );
		} else {
			// --es categoria 2 ó 3
			$monto = getvalue ( $idrel, 5931 );
		}
		return $monto;
	}

}

// ---- no relacionales (la empresa como rol)
class DIRCON {

	public $nombre = 'DIRCON';
	public $cod = 'DIRCON';
	public $where = array (
			6065
	);
	public $id = 5691;
	public $titulo = 5673;
	public $url = '/frontcustom/245/pacc.externo.print.php';
	public $estado = 5689;
	public $self = false; // si es true son empresas si no otra entidad

	function monto($idrel) {
		return 'Total:' . getvalue ( $idrel, 6057 ) . '</br> ANR:' . getvalue ( $idrel, 6058 );
	}

}