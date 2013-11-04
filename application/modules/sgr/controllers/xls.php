<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * padfyj
 * 
 * Description of the class
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date    Oct 21, 2013
 */
class Xls extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->module_url = base_url() . 'dna2/sgr/xls';

        
    }

    function Index($table = 'sgr_fdr_contingente', $filename = 'ANEXO14.xls') {

        //echo dirname(__FILE__); //$this->module_url;

        $uploadpath = '/home/ich/Desktop/' . $filename;
        $this->load->library('excel_reader2');
        $data = new Excel_reader2($uploadpath);

        /*
          Example Usage:

          $data = new Spreadsheet_Excel_Reader("test.xls");

          Retrieve formatted value of cell (first or only sheet):

          $data->val($row,$col)

          Or using column names:

          $data->val(10,'AZ')

          From a sheet other than the first:

          $data->val($row,$col,$sheet_index)

          Retrieve cell info:

          $data->type($row,$col);
          $data->raw($row,$col);
          $data->format($row,$col);
          $data->formatIndex($row,$col);

          Get sheet size:

          $data->rowcount();
          $data->colcount();

          $data->sheets[0]['cells'][$i][$j] - data from $i-row $j-column

          $data->sheets[0]['numRows'] - count rows
          $data->sheets[0]['numCols'] - count columns

          $data->sheets[0]['cellsInfo'][$i][$j] - extended info about cell
          $data->sheets[0]['cellsInfo'][$i][$j]['type'] = "date" | "number" | "unknown"
          $data->sheets[0]['cellsInfo'][$i][$j]['raw'] = value if cell without format
          $data->sheets[0]['cellsInfo'][$i][$j]['format'] = Excel-style Format string of cell
          $data->sheets[0]['cellsInfo'][$i][$j]['formatIndex'] = The internal Excel index of format

          $data->sheets[0]['cellsInfo'][$i][$j]['colspan']
          $data->sheets[0]['cellsInfo'][$i][$j]['rowspan']

         */
        
        
        $config['hostname'] = "localhost";
        $config['username'] = "root";
        $config['password'] = "root";
        $config['database'] = "forms2";
        $config['dbdriver'] = "mysql";
        $config['dbprefix'] = "";
        $config['pconnect'] = FALSE;
        $config['db_debug'] = TRUE;
        $config['cache_on'] = FALSE;
        $config['cachedir'] = "";
        $config['char_set'] = "utf8";
        $config['dbcollat'] = "utf8_general_ci";
         $db = $this->load->database($config, true, false);

        $SQL = "SELECT *
        FROM `" . $table . "`
        WHERE `cuit_sgr` = '30-70937729-5'";

        $DB_forms2 = $this->db;
        $query = $DB_forms2->query($SQL);
        
        
       var_dump("db");





        /* $sql = "INSERT INTO $table (";
          for ($index = 1; $index <= $data->sheets[0]['numCols']; $index++) {
          $sql.= strtolower($data->sheets[0]['cells'][1][$index]) . ", ";
          }

          $sql = rtrim($sql, ", ") . " ) VALUES ( ";
          for ($i = 2; $i <= $data->sheets[0]['numRows']; $i++) {
          $valuesSQL = '';
          for ($j = 1; $j <= $data->sheets[0]['numCols']; $j++) {
          $valuesSql .= "\"" . $data->sheets[0]['cells'][$i][$j] . "\", ";
          }
          echo $sql . rtrim($valuesSql, ", ") . " ) <br>";
          } */
    }

}
