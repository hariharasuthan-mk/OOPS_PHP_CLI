#!/usr/bin/php
<?php
/*
; DO NOT ALTER OR REMOVE COPYRIGHT NOTICES OR THIS HEADER.
; 
; Contributor(s): Hariharasuthan
;
; Portions Copyrighted 2018 
*/

/*
;CLI DB Config
*/

/**
 * Main application class.
*/
final class Index {
    
  
    /**
     * System config.
    */
    public function init() {
        // error reporting - all errors for development (ensure you have display_errors = On in your php.ini file)
        error_reporting(E_ALL | E_STRICT);
        mb_internal_encoding('UTF-8');        
        //set_exception_handler(array($this, 'handleException'));
        spl_autoload_register(array($this, 'loadClass'));   
        
        
    }
    
    /**
     * Class loader.
    */
    
    public function loadClass($name) {
        /*
        require_once('Classes/Database.inc.php'); 
        require_once('Classes/Getdetails.inc.php'); 
        */
        
        $classes = array(               
            'Database' => '/Classes/Database.inc.php', 
            'Getdetails' => '/Classes/Getdetails.inc.php', 
            
            
            
        );
        if (!array_key_exists($name, $classes)) {
            die('Class "' . $name . '" not found.');
        }        
        //print __DIR__.$classes[$name]; //require_once __DIR__.$classes[$name];
        require_once __DIR__.$classes[$name];
        
    }
    
    /**
     * Run the application!
    */
    public function run() {
        
        //
            $obj = new Getdetails();
            $arr_final_conf = $obj->get();
            $dbconnect = new Database($arr_final_conf['host'],$arr_final_conf['dbuser'],$arr_final_conf['dbpass'],$arr_final_conf['dbname']);


            if(($dbconnect->connection_status=="Failure")){
                die("\e[1;37;41m Database connection failure\e[0m\n".PHP_EOL);
                exit;
            }
            else{
                echo "\e[1;32;40m Connected with host and processing for Database creation\e[0m\n"; 
                $db = $dbconnect->CreateDatabase($arr_final_conf['dbname']);
                if($db===true){ 
                    echo "\e[1;32;40m ".$arr_final_conf['dbname']." has created ...\e[0m\n".PHP_EOL;

                    //config.inc        
                    chmod(__DIR__."/inc",0775);        
                    $ini_Data = array('database' =>  $arr_final_conf,);
                    //var_dump($ini_Data);
                    $inc = $obj->write_ini_file($ini_Data, __DIR__.'/inc/data-config.ini', true);
                    echo "\e[1;32;40m Now you are ready to run the script of ...\e[0m\n".PHP_EOL;


                }

            }   

        //
        
        
    }
    
    
   

}

$index = new Index();
$index->init();
$index->run(); // run application!

?>
