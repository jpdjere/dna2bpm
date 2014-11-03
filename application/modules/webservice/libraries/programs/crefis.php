<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

require_once 'functions.php';
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
		return $this->getvalue ( $idrel, 5040 );
	}

}

function getvalue($id, $idframe) {

	$rtnVal = null;
	$result = null;
	$id = (double) $id;
	$idframe = (int) $idframe;
	//----Get container
	$frame = $this->mongo->db->frames->findOne(array('idframe' => $idframe), array('container'));
	$query = array('id' => $id);
	$fields = array((string) $idframe);
	if ($frame['container']) {
		$result = $this->mongo->db->selectCollection($frame['container'])->findOne($query, $fields);
	} else {
		trigger_error("container property missing for: $idframe");
	}
	//var_dump($frame['container'],json_encode($query),json_encode($fields),$result);
	$rtnVal = (isset($result[$idframe])) ? $result[$idframe] : null;
	return $rtnVal;
}

