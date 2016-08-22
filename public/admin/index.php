<?php

require_once("../../includes/initialize.php");


if(!$session->is_logged_in())
{ redirect_to("../admin/login.php");}

?>

<?php include_layout_template("admin_header.php"); ?>
       
       
        <h2>Menu</h2>
        
        <?php echo output_message($message); ?>
        
        <p><a href="logfile.php">Log file</a></p>
        <div>
            <p></p>
        </div>
        
        <p><a href="logout.php">Logout</a></p>
    </div>
<?php include_layout_template("admin_footer.php"); ?>
