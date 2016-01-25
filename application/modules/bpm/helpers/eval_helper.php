<?php
/**
 * This function protects eval calls within BPM
 */
 function save_eval($code){
    $disclousure=array(
    'phpinfo',
    'posix_mkfifo',
    'posix_getlogin',
    'posix_ttyname',
    'posix_getuid',
    'getenv',
    'get_current_user',
    'proc_get_status',
    'get_cfg_var',
    'disk_free_space',
    'disk_total_space',
    'diskfreespace',
    'getcwd',
    'getlastmo',
    'getmygid',
    'getmyinode',
    'getmypid',
    'getmyuid',
         );
    
    $system=array(
       'chgrp',
        'chmod',
        'chown',
        'copy',
        'file_put_contents',
        'lchgrp',
        'lchown',
        'link',
        'mkdir',
        'move_uploaded_file',
        'rename',
        'rmdir',
        'symlink',
        'tempnam',
        'touch',
        'unlink', 
        );
     $blacklist=array_merge($system,$disclousure);
     foreach($blacklist as $nono) { 
        if(strpos($code,$nono) !== false) { 
            $status = 0; 
            return 0; 
        } 
    } 
     
 }
