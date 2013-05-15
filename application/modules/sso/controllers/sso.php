<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * sso
 * 
 * Description of the class
 * 
 * @author Juan Ignacio Borda <juanignacioborda@gmail.com>
 * @date    Jan 14, 2013
 */
class sso extends CI_Controller {

    function __construct() {
        parent::__construct();
    }

    function Index() {
        echo "<h1>SSO</h1>";
        echo "<h3>Consulta de prueba LDAP</h3>";
        echo "Conectando ...";
        $ldap_server = 's1.local';
        $ldap_port = '390';
        $ldaprdn = 'cn=zentyal,dc=s1,dc=local';
        $ldappass = 'yzNRgV8fP4gL@eKCKfkq';
        $baseDN = "ou=Users,dc=s1,dc=local";
        // Debe ser un servidor LDAP válido!
        $ldapconn = ldap_connect($ldap_server, $ldap_port) or die("Can\'t connect to LDAP Server:$ldap_server on port $ldap_port");

        //-----SETINGS
        ldap_set_option($ldapconn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($ldapconn, LDAP_OPT_REFERRALS, 0);
        //-----SETINGS
        //
        // realizando la autenticación as ROOT
        //$ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass);

        $mail = "test@mp.gba.gov.ar";

        $passw = 'test123';
        //---1ro busco el uid del usuario con el 
        // Bind anonymously to the LDAP server to search.
        $ldapbind = ldap_bind($ldapconn) or die("Could not bind anonymously.");
        $ldapbind = ldap_bind($ldapconn, $ldaprdn, $ldappass) or die("Could not bind with password");
        $filter = "(mail=$mail)";
        $result = ldap_search($ldapconn, $baseDN, $filter, array('dn'), 1) or die("Search error.");
        $info = ldap_get_entries($ldapconn, $result);

        if ($info['count']) {
            var_dump($info);
            $dn = $info[0]['dn'];
            // realizando la autenticación as test
            $ldapbind = ldap_bind($ldapconn, $dn, 'test123');
            if ($ldapbind) {
                echo "<h3>AUTH OK!</h3>";
                //@todo pass data to next step
                } else {
                echo "<h3>AUTH #FAIL</h3>";
            }
        } else {
            echo 'user not found';
        }
    }

}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */