<?php 
require_once("../../includes/initialize.php");

if(!$session->is_logged_in())
{ redirect_to("../admin/login.php");}


    if(empty($_GET['id'])){
        $session->message("No photograph ID was provided.");
        redirect_to('index.php');
    }
    
$photo = Photograph::find_by_id($_GET['id']);
if($photo && $photo->destroy()){
    $session->message("The photo was deleted.");
    redirect_to('view_photo.php');
} else{
    $session->message('The photo could not be deleted.');
    redirect_to('view_photo.php');
}


?>
<?php if(isset($database)){ $database->close_connection();} ?>