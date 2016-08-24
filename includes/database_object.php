<?php

require_once(LIB_PATH.DS.'database.php');

class DatabaseObject{
     
     //common Database Methods
    public static function find_all(){
        
        return static::find_by_sql("SELECT * FROM " . static::$table_name);
    }
    
    public static  function find_by_id($id=0){
        
        global $database;
        $result_array = static::find_by_sql("SELECT * FROM " . static::$table_name . " WHERE id= ". $database->escape_value($id) ." LIMIT 1");
        
        return !empty($result_array) ? array_shift($result_array) : false;
    }
    
    public static function find_by_sql($sql){
        
        global $database;
        $result_set = $database->query($sql);
        $object_array = array();
        while($row = $database->fetch_array($result_set)){
            $object_array[] = static::instantiate($row);
        }
        
        return $object_array;
    }
    
    public static function count_all(){
        global $database;
        $sql = "SELECT COUNT(*) FROM " . static::$table_name;
        $result_set = $database->query($sql);
        $row = $database->fetch_array($result_set);
        return array_shift($row);
    }
     
    private static function instantiate($record){ 
        //could check that $record exist and is an array
        //simple , long-form approch:
        $class_name = get_called_class();
        $object = new $class_name;
//        $object->id = $record['id'];
//        $object->username = $record['username'];
//        $object->first_name = $record['first_name'];
//        $object->last_name = $record['last_name'];
//        $object->password = $record['password'];
        
        //More dynamic, short-form approach:
        foreach($record as $attribute=>$value){
            if($object->has_attribute($attribute)){
                $object->$attribute = $value;
            }
        }
        return $object;
    }
    
    private function has_attribute($attribute){
        
        //get_object_vars return an associate array with all attributes
        //(include. private ones) as the keys and their current values as the value
        $object_vars= get_object_vars($this);
        
        //we don't care about the value, we just want to know if they key exists 
        //will return true or false 
        return array_key_exists($attribute, $object_vars);
    }
    
    public function attributes(){
        $attributes = array();
        foreach(static::$db_fields as $field){
            $attributes[$field] = $this->$field;
        }
        return $attributes;
    }
    
    public function sanitized_attributes(){
        global $database;
        
        $clean_attributes = array();
        
        foreach($this->attributes() as $key => $value){
            $clean_attributes[$key] = $database->escape_value($value);
        }
        return $clean_attributes;
    }
    
    public function save(){
        return isset($this->id) ? $this->updated() : $this->create();
    }
    
    public function create(){
        global $database;
        
        $attributes = $this->sanitized_attributes();
        $sql  = "INSERT INTO ". static::$table_name . "(";
        $sql .= join(", ", array_keys($attributes));
        $sql .= ") VALUES ('";
        $sql .= join("', '", array_values($attributes) );
        $sql .= "')" ;
        if($database->query($sql)){
            $this->id = $database->insert_id();
            return true;
        }else{
            return false;
        }
    }
    
    public function update(){
        global $database;
        
        $attributes = $this->sanitized_attributes();
        $attribute_pairs = array();
        foreach($attributes as $key => $value){
            $attribute_pairs[] = "{$key}='{$value}'";
        }
        
        $sql = "UPDATE " . static::$table_name . " SET ";
        $sql .= join(", ", $attribute_pairs); 
        $sql .= " WHERE id=". $database->escape_value($this->id);
        
        $database->query($sql);
        return ($database->affected_row() == 1) ? true : false;
    }
       
    public function delete(){
        global $database;
        $sql  = "DELETE FROM " . static::$table_name;
        $sql .= " WHERE id=". $database->escape_value($this->id);
        $sql .= " LIMIT 1";
        
        $database->query($sql);
        return ($database->affected_row() == 1) ? true : false;
    }
    
     
}