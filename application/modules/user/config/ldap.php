<?php

$config['ldap_server'] = 'zentyal.mp.gba.gov.ar';
$config['ldap_port'] = '390';
$config['ldaprdn'] = 'cn=zentyal,dc=mp,dc=gba,dc=gov,dc=ar';
$config['ldappass'] = '44SAkkfltjohkfjlqvz6';
$config['baseDN'] = "ou=Users,dc=mp,dc=gba,dc=gov,dc=ar";
$config['groupsDN'] = "ou=Groups,dc=mp,dc=gba,dc=gov,dc=ar";
$config['ldap_use_groups'] = true;
$config['groupAdmin'] = 2001;
$config['userDefaultGidnumber'] = 1901;
$config['member_attr']='member';  //<------Zentyal
////-----OpenDS local
//$config['ldap_server'] = '127.0.0.1';
//$config['ldap_port'] = '1389';
//$config['ldaprdn'] = 'cn=root';
//$config['ldappass'] = 'root';
//$config['baseDN'] = "ou=User,dc=mp,dc=gba,dc=gov,dc=ar";
//$config['groupsDN'] = "ou=Groups,dc=mp,dc=gba,dc=gov,dc=ar";
//$config['ldap_use_groups'] = true;
//$config['groupAdmin'] = 1000;
//$config['userDefaultGidnumber'] = 999;
//$config['member_attr'] = 'uniquemember'; //<----OpenDs

//-----Override GroupAdmin
$config['home'] ='/home/';
//-----set member Attributo to search or save members

$config['user_map'] = array(
    'idu' => 'uidnumber',
    "name" => "givenname",
    "lastname" => "sn",
    "cn" => "cn",
    "company" => "",
    "email" => "mail",
    "idnumber" => "employeenumber",
    "phone" => "",
    "nick" => "uid",
    "passw" => "",
    "phone" => "telephonenumber",
);

$config['user_template'] = array(
'objectClass' => array(
 0 => 'person',
 1 => 'inetorgperson',
 2 => 'organizationalperson',
 3 => 'top')
);
//$config['ldap_server'] = 'ldap.mp.gba.gov.ar';
//$config['ldap_port']= '389';
//$config['ldaprdn']= 'cn=admin,dc=mp,dc=gba,dc=gov,dc=ar';
//$config['ldappass'] = 'root';
//$config['baseDN']= "ou=People,dc=mp,dc=gba,dc=gov,dc=ar";
//$config['groupsDN']= "ou=Groups,dc=mp,dc=gba,dc=gov,dc=ar";

