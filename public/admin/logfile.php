<?php

require_once("../../includes/initialize.php");


if(!$session->is_logged_in())
{ redirect_to("../admin/login.php");}
if(isset($_GET['clear'])){
if($_GET['clear'] == 'true'){
    $path =SITE_ROOT.DS.'logs'.DS.'log.txt';
    file_put_contents($path, '');
    log_action("Logs Cleared", "By User ID {$session->user_id}");
    redirect_to("logfile.php");
}}
?>

<?php include_layout_template("admin_header.php"); ?>
        <a href="index.php">&laquo; Back</a><br>
        <h2>Log File</h2>
        <p><a href="logfile.php?clear=true">Clear log File</a></p>
        <?php  log_read();?>
        
       
    </div>
<?php include_layout_template("admin_footer.php"); ?>
