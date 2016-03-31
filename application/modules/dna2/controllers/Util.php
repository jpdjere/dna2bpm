<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * util
 * 
 * Description of the class
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date    Jul 30, 2014
 */
class Util extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->user->authorize();
        ini_set('error_reporting',E_ALL);
    }

    function Index() {
        
    }

    function preguntas_download() {
        $CI=& get_instance();
        $CI->db=$this->load->database('dna2',true);
        // Load the DB utility class
        $this->load->dbutil();
        $this->load->helper('download');
        $this->load->helper('file');
        $prefs = array(
            // Array of tables to backup.
            'tables' => array(
                'apform',
                'aplicacion',
                'entidades',
                'formularios',
                'opciones',
                'tablaopciones',
                'preguntas',
                'preguntasvista',
                'requeridos',
                'vistas',
            ),
            'ignore' => array(), // List of tables to omit from the backup
            'format' => 'gzip', // gzip, zip, txt
            'filename' => 'preguntas.sql', // File name - NEEDED ONLY WITH ZIP FILES
            'add_drop' => TRUE, // Whether to add DROP TABLE statements to backup file
            'add_insert' => TRUE, // Whether to add INSERT data to backup file
            'newline' => "\n"               // Newline character used in backup file
        );

// Backup your entire database and assign it to a variable
        $backup = & $this->dbutil->backup($prefs);

//// Load the file helper and write the file to your server
//        write_file('images/download/preguntas.sql', $backup);

// Load the download helper and send the file to your desktop
        force_download('preguntas.sql.gz', $backup);
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */