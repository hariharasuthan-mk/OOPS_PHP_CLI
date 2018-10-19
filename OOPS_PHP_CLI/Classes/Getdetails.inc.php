<?php

/*
/*
; DO NOT ALTER OR REMOVE COPYRIGHT NOTICES OR THIS HEADER.
; 
; Contributor(s): Hariharasuthan

 * Description of Colors Cli
 *
 * @author Administrator
 * 
; Portions Copyrighted 2018 
*/
class Getdetails {
    //put your code here
    
    var $arr_db_conf,$arr_label,$arr_conf;    
    
    function __construct() {
        $this->arr_db_conf = array('host','dbuser','dbpass','dbname');
        $this->arr_label = array('Hostname','Database Username','Database Password','Database name');
        $this->arr_conf = array();
    }
    
    public function get(){
        
        $str = "Hello";
        echo "\e[1;32;40mWelcome to database configuration Mode...\e[0m\n";
        echo "Please Enter the following values".PHP_EOL."Please 'y' To continue".PHP_EOL;

        $handle = fopen ("php://stdin","r");
        $line = fgets($handle);

        if((trim($line) != 'y')){
            echo "\e[1;37;41mAborting the Intstallation!\e[0m\n".PHP_EOL;
            exit;
        }
        else{
            echo "Please Enter the following values ".PHP_EOL;
            foreach($this->arr_label as $value){       
                echo "\e[1;32;40m Enter Your ".$value."\e[0m".PHP_EOL;
                $input = trim(fgets(STDIN));
                array_push($this->arr_conf,$input);       
            }
            $this->arr_final_conf = array_combine($this->arr_db_conf,$this->arr_conf);//print_r($arr_final_conf);
        }
        
        return $this->arr_final_conf;
    }
    
    public function write_ini_file($assoc_arr, $path, $has_sections=FALSE) { 
        
        $content = ""; 
        if ($has_sections) { 
            foreach ($assoc_arr as $key=>$elem) { 
                $content .= "[".$key."]\n"; 
                foreach ($elem as $key2=>$elem2) { 
                    if(is_array($elem2)) 
                    { 
                        for($i=0;$i<count($elem2);$i++) 
                        { 
                            $content .= $key2."[] = \"".$elem2[$i]."\"\n"; 
                        } 
                    } 
                    else if($elem2=="") $content .= $key2." = \n"; 
                    else $content .= $key2." = \"".$elem2."\"\n"; 
                } 
            } 
        } 
        else { 
            foreach ($assoc_arr as $key=>$elem) { 
                if(is_array($elem)) 
                { 
                    for($i=0;$i<count($elem);$i++) 
                    { 
                        $content .= $key."[] = \"".$elem[$i]."\"\n"; 
                    } 
                } 
                else if($elem=="") $content .= $key." = \n"; 
                else $content .= $key." = \"".$elem."\"\n"; 
            } 
        } 

        if (!$handle = fopen($path, 'w')) { 
            return false; 
        }

        $success = fwrite($handle, $content);
        fclose($handle); 

        return $success; 
    }
    
}
