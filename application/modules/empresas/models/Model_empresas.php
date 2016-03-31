<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Acceso a la informacion registrada en la DB dna3 para PACC1.3
 *
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 */
class Model_empresas extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->main_container = 'container.empresas';
        $this->tmp_container = $this->main_container . '13_tmp';

        /* LOADER */
        $this->load->library('cimongo/Cimongo.php', '', 'dna3');
        $this->dna3->switch_db('dna3');
        $this->load->model('app');
    }
    function altas_anio($year){
    $aquery=array (
  0 =>
  array (
    '$match' =>
    array (
      'ident' => 7,
    ),
  ),
  1 =>
  array (
    '$group' =>
    array (
      '_id' =>
      array (
        'year' =>['$year' => '$checkdate'],
        'month' =>['$month' => '$checkdate']
      ),
      'qtty' =>['$sum' => 1],
      'checkdate'=>['$first'=>'$checkdate'],
    ),
  ),
  2 =>
  array (
    '$project' =>
    array (
      '_id' => false,
      'year' => '$_id.year',
      'month' => '$_id.month',
      'qtty' => '$qtty',
      'date' =>array (
            '$dateToString' =>
            array (
              'format' => '%Y-%m',
              'date' => '$checkdate',
            ),
          ),
    ),
  ),
  3 =>
  array (
    '$match' =>
    array (
      'year' => (int)$year,
    ),
  ),
  4 =>
  array (
    '$sort' =>
    array (
      'year' => 1,
      'month' => 1,
    ),
  ),
);
    $rs=$this->mongowrapper->db->idsent->aggregate($aquery);
    return $rs['result'];
    }


    function altas_todas(){

       $aquery=array (
  0 =>
  array (
    '$match' =>
    array (
      'ident' => 7,
    ),
  ),
  1 =>
  array (
    '$group' =>
    array (
      '_id' =>
      array (
        'year' =>
        array (
          '$year' => '$checkdate',
        ),
        'month' =>
        array (
          '$month' => '$checkdate',
        ),
      ),
      'qtty' =>
      array (
        '$sum' => 1,
      ),
      'checkdate'=>['$first'=>'$checkdate'],
    ),
  ),
  2 =>
  array (
    '$project' =>
    array (
      '_id' => false,
      'year' => '$_id.year',
      'month' => '$_id.month',
      'qtty' => '$qtty',
      'date' =>array (
            '$dateToString' =>
            array (
              'format' => '%Y-%m',
              'date' => '$checkdate',
            ),
          ),
    ),
  ),
  3 =>
  array (
    '$sort' =>
    array (
      'year' => 1,
      'month' => 1,
    ),
  ),
);
    $rs=$this->mongowrapper->db->idsent->aggregate($aquery);
    // var_dump($rs);exit;
    return $rs['result'];
    }
}