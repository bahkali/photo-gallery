<?php 
require_once("../../includes/initialize.php");

if(!$session->is_logged_in())
{ redirect_to("../admin/login.php");}
?>
<?php include_layout_template("admin_header.php"); ?>

<?php


 //find all the photos

    $photos= Photograph::find_all();
?>


<h2>Photographs</h2>

<?php echo output_message($message); ?>
<table class="bordered">
    <tr>
        <th>Image</th>
        <th>Filename</th>
        <th>Caption</th>
        <th>Size</th>
        <th>Type</th>
        <th>Comments</th>
        <th>&nbsp;</th>
    </tr>
    <?php foreach($photos as $photo):?>
        
        <tr>
            <td><img src="../<?php echo $photo->image_path() ; ?>" width="100" alt=""></td>
            <td><?php echo $photo->filename ; ?></td>
            <td><?php echo $photo->caption ; ?></td>
            <td><?php echo $photo->size_as_text(); ?></td>
            <td><?php echo $photo->type ; ?></td>
            <td>
                <a href="comments.php?id=<?php echo $photo->id;?>">
                <?php echo count($photo->comments()); ?>
                </a>
            </td>
            <td><a href="delete_photo.php?id=<?php echo $photo->id;?>">Delete</a></td>
        </tr>
    
   <?php endforeach; ?>
   
</table>
 <p><a href="photo_upload.php">Upload a new photograth</a></p>
 <p><a href="index.php">Home</a></p>
</div>
<?php include_layout_template("admin_footer.php"); ?>