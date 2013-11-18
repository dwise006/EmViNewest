
<?php
include_once '../../config/general.php';

$file = "../content/upload/".$_GET['name']."";

/*if(isset($_GET['name'])){
	echo $_GET['name'];
}
*/	

?>

<script src="../ckeditor/ckeditor.js"></script>





<form name="ckeditor" method="post" action="<?php echo $_SERVER['PHP_SELF']."?name=".$_GET['name'];?>">
<textarea id="editor1" name="tekst" class="ckeditor"> <?php  echo @file_get_contents($file);  ?></textarea>
<br>
<input name="Save" type="submit" value="Save" />

<input name="Cancel" type="submit" value="Cancel" />
</form>



 

    <?php
    if ($_POST["Save"])
    {
       $data = $_POST["tekst"];
        @file_put_contents($file, $data);
        sleep(3);
       
       header('Location: '.$siteUrl.'ckeditor.php?name='.$_GET['name'].'');
 
    }
    ?>
