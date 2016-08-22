<?php

function categorias_parser(&$entradas){
	$tabla = [
		1 => 'Micro',
		2 => 'Pequeña',
		3 => 'Mediana',
		4 => 'Grande'
	];
	foreach($entradas as &$entrada){
		$entrada['tamano_parseado'] = $tabla[$entrada['tamano']];
	}
}