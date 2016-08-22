<?php
require_once(LIB_PATH.DS.'database.php');

class Photograph extends DatabaseObject{
    
    protected static $table_name = "photographs";
    protected static $db_fields = array("id", "filename", "type", "size", "caption");
    
    public $id;
    public $filename; 
    public $type;
    public $size;
    public $caption;
    
    private $temp_path;
    protected $upload_dir= "images";
    
    public $errors = array();
    
    protected $upload_errors = array(
        UPLOAD_ERR_OK => "No erros.",
        UPLOAD_ERR_INI_SIZE => "Larger than upload_max_filesize.",
        UPLOAD_ERR_FORM_SIZE => "Larger than form MAX_FILE_SIZE",
        UPLOAD_ERR_PARTIAL => "Partial upload",
        UPLOAD_ERR_NO_FILE => "No file",
        UPLOAD_ERR_NO_TMP_DIR => "No temporary directory.",
        UPLOAD_ERR_CANT_WRITE => "Can't write to disk.",
        UPLOAD_ERR_EXTENSION => "File upload stopped by extension." 
     );

    public function image_path(){
        return $this->upload_dir.DS.$this->filename;
    }
    
    public function size_as_text(){
        if($this->size < 1024){
            return "{$this->size} bytes";
        } elseif($this->size < 1048576){
            $size_kb = round($this->size/1024);
            return "{$size_kb} KB";
        }else{
            $size_mb = round($this->size/1048576, 1);
            return "{$size_mb} MB";
        }
    }
    
    //pass in $_FILE(['uploaded_file']) as an argument
    public function attach_file($file){
        //perform error checking on the form parametres
        if(!$file || empty($file) || !is_array($file)){
            $this->errors[] = "No file was uploaded.";
            return false;
        }elseif( $file['error'] != 0){
            $this->error[] = $this->upload_errors[$file['error']];
            return false;
        }else{
        //set object attributes to the form parametres
        $this->temp_path = $file['tmp_name'];
        $this->filename  = basename($file['name']);
        $this->type      = $file['type'];
        $this->size      = $file['size'];
        //Don't worry about saving anything to the database yet
        return true;
        }
    }
    
    
    public function save_upload(){
        
        if(isset($this->id)){
            $this->update();
        }else{
            //make sure there are no errors
            //can't save if there are pre-existing errors
            if(!empty($this->errors)){ return false;}
            //make sure the caption is not too long for the DB
            if(strlen($this->caption) > 255){
                $this->errors[] = "The Caption can only be 255 characters long.";
                return false;
            }
            //Determine the target_path
            $target_path = SITE_ROOT.DS.'public'.DS.$this->upload_dir.DS.$this->filename;
            
            //make sure a file doesn't already exist in the target location
            if(file_exists($target_path)){
                $this->error[] = "The file {$this->filename} already exists.";
                return false;
            }
            
            //Attempt to move the file
            if(move_uploaded_file($this->temp_path, $target_path)){
                //Save a corresponding entry to the database
                if($this->create()){
                    unset($this->temp_path);
                    return true;
                }
            }else{
                //File was not moved.
                $this->errors[] = "The file upload failed, possibly due to incorrect permissions on the upload folder.";
                return false;
            }  
        }
        
    }
    
    public function destroy(){
        //frist remove the database entry
        if($this->delete()){
            $targe_path = SITE_ROOT.DS.'public'.DS.$this->image_path();
            return unlink($targe_path) ? true : false;
            
        }else{
        //then remove the file
            return false;
        }
    }
    
//    public static function get_all(){
//        global $database;
//        
//        $sql = "SELECT * FROM " . self::$table_name;
//        
//        $result_array = self::find_by_sql($sql);
//        
//        return !empty($result_array) ? $result_array : false;
//    }
    
         
}