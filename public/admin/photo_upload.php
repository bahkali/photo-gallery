<?php

require_once("../../includes/initialize.php");


if(!$session->is_logged_in()){redirect_to("../admin/login.php");}

    $max_file_size = 1048576; //1MB (10MB 10485760, 100KB 102400)

    if(isset($_POST['submit'])){    
        $photo = new Photograph();
        $photo->caption = $_POST['caption'];
        $photo->attach_file($_FILES['file_upload']);
        if($photo->save_upload()){
            //success
            $session->message("Photograph uploaded successfully");
            redirect_to("view_photo.php");
        }else{
            //failure
            $message = join("<br>", $photo->errors);
        }
    }

?>

<?php include_layout_template("admin_header.php"); ?>

<?php echo output_message($message); ?>

<form action="photo_upload.php" enctype="multipart/form-data" method="post">
    <p><label for="file_upload">Upload File</label></p>
    <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo $max_file_size; ?>">
    <p><input type="file" name="file_upload"></p>
    <p>Caption: <input type="text" name="caption" value=""></p>
    <p><input type="submit" value="submit" name="submit"></p>
</form>

<p><a href="view_photo.php">View Pictures</a></p>

</div>
<?php include_layout_template("admin_footer.php"); ?>
