<?php

function strip_zero_from_date($marked_string =""){
    
    $no_zeros = str_replace('*0', '',$marked_string );
    
    $cleaned_string = str_replace('*', '', $no_zeros);
    
    return $cleaned_string;
}

function redirect_to($loc = null){
    if($loc != null){
        header("Location: {$loc}");
    }
}

function output_message($msg = ""){
    if(!empty($msg)){
        return "<p class='message'>{$msg}</p>";
    }else{
        return "";
    }
}



function __autoload($class_name){
    $class_name = strtolower($class_name);
    $path = LIB_PATH.DS."{$class_name}.php";
    if(file_exists($path)){
        require_once($path);
    }else{
        die("The file {$class_name}.php could not be found.");
    }
}

function include_layout_template($template=""){
    include(SITE_ROOT.DS.'public'.DS.'layouts'.DS.$template);
}


function log_action($action, $message=""){
    
    $path = SITE_ROOT.DS.'logs'.DS.'log.txt';
    $new = file_exists($path) ? false: true;
    if($handle = fopen($path, 'a')){
        
        $timestamp = strftime("%Y-%m-%d %H:%M:%S", time());
        $content = "{$timestamp} |{$action}: {$message} \n";

        fwrite($handle, $content);
        fclose($handle); 
        
        if($new){Chmod($path, 0755);}
    }
    else{
       echo "Could not open log file for writting"; 
    }
        
   
}

function log_read(){
    
    $path = SITE_ROOT.DS.'logs'.DS.'log.txt';
    
    if(file_exists($path) && is_readable($path) && $handle = fopen($path, 'r')){
        echo "<ul class=\"log-entries\">";
        while(!feof($handle)){
            $entry = fgets($handle);
            if(trim($entry) != ""){
                echo "<li>{$entry}</li>";
            }
        }
        echo "</ul>";
        fclose($handle);
    }else{
        echo "Could not read from {$path}";
    }
    
    
}



