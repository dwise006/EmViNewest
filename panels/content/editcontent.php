<?php 
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
?>
<h1>Edit Content</h1>
<?php
set_include_path("../");

/*Include sidebar */
//include("../../inc/sidebar.php");
//showSidebar("editcontent");

include_once '../../config/general.php';
include_once '../../config/DB_Class.php';
include_once '../../pages/content/contentclass.php';
include_once '../../pages/user/userclass.php';

session_start();

// Get the user ID out of the session
$uid = $_SESSION['ID'];


// Get the content out of the url
$cid = $_GET['ID'];
if ($cid == null || $cid == '')
{
	echo "No parameter ID = provided in URL<br>";
	die;
}

$con = new Content();
$content = $con->get_contentByID($_GET['ID']);

$ftypeID = $content['contentType'];
$ftypename = $con->get_contentTypeByID($ftypeID);
$fileloc = $content['fileLocation'];
if ($fileloc == null || $fileloc == '') {
	$fileloc = uniqid();
}
//echo $filesLocation . $fileloc . "." . $ftypename . "<br>";

?>

<script type="text/javascript">
function validateForm()
 {
 var name=document.forms["aContent"]["name"].value;
 var desc=document.forms["aContent"]["description"].value;
 var type=document.forms["aContent"]["type"].value;
 var fup = document.getElementById('file');
 var fileName = fup.value;
 var ext = fileName.substring(fileName.lastIndexOf('.') + 1);
 if (name==null || name=="")
 {
   alert("Content name cannot be blank.");
   return false;
 }
 else if (desc==null || desc=="")
 {
 alert("Content description cannot be blank.");
 return false;
 }
 else if (ext == null || ext =="")
 {
	 //return true;
 }
 else if (ext.toLowerCase() != type.toLowerCase())
 {
 	 alert("Selected content file format ("+type+")\ndoes not match the file extension ("+ext+").\nPlease select a different format or file.");
 	 return false;
 }
}
</script>

<?php 
echo "<script>";
echo "function openWin()";
echo "{";
echo "var myWindow = window.open(\"".$siteUrl ."pages/ckeditor.php?name=".$fileloc.".".$ftypename."\",\"_blank\",\"toolbar=yes, scrollbars=yes, resizable=yes, top=500, left=500, width=1100, height=1000\");";
echo "}";
echo "</script>";


// This section handles the page submission and the saving of data to the database
if (!empty($_POST)) {

	echo $_POST["name"] . "<br>" . $_POST["description"] . "<br>" . $_POST["keywords"] . "<br>";
	
	if ($_FILES["file"]["error"] > 0) {
		echo "Return Code: " . $_FILES["file"]["error"] . "<br>";
	}
	else if ($_FILES["file"]["size"] > 0) {
		// remove the old file and replace it with the new file
		if (file_exists($filesLocation . $fileloc . "." . $ftypename)) {
			$removefile = "rm " . $filesLocation . $fileloc . "." . $ftypename;
			exec($removefile,$output,$ret);
			if ($ret) {
				echo "Error removing " . $filesLocation . $fileloc . "." . $ftypename;
				die;
			}
		}
		if (file_exists($filesLocation . $fileloc . ".png")) {
			$removefile = 'rm ' . $filesLocation . $content['fileLocation'] . '.png';
			exec($removefile,$output,$ret);
			if ($ret){
				echo "Error removing " . $filesLocation . $fileloc . ".png";
				die;
			}
		}
	
		echo "Upload: " . $_FILES["file"]["name"] . "<br>";
		echo "Type: " . $_FILES["file"]["type"] . "<br>";
		echo "Size: " . ($_FILES["file"]["size"] / 1024) . " kB<br>";
		echo "Temp file: " . $_FILES["file"]["tmp_name"] . "<br>";
		move_uploaded_file($_FILES["file"]["tmp_name"],
			$filesLocation . $fileloc . "." . $_POST["type"]);
		echo "Moved to " . $filesLocation . $fileloc . "." . $_POST["type"] . "<br>";
	}
	// Function to write data to the DB is public function add_content($uid,$name,$desc,$kw,$type,$loc)
	$con->update_content($uid,$cid,$_POST["name"],$_POST["description"],$_POST["keywords"],$con->get_contentIDByType($_POST["type"]));
	
	// Run conversion using imagemagick to generate thumbnail for html files
	if ($_POST["type"] == 'html') {
		$convertopdf = 'xvfb-run --server-args="-screen 0, 800x600x24" wkhtmltopdf ' . $filesLocation . $fileloc . '.' . $_POST("type") . ' ' . $filesLocation . $fileloc . '.pdf';
		$convertopng = 'convert ' . $filesLocation . $fileloc . '.pdf[0] ' . $filesLocation . $fileloc . '.png';
		exec($convertopdf,$output,$ret);
		if ($ret) {
			echo "Error fetching screen dump for $fileloc\n";
			die;
		}
		exec($convertopng,$output,$ret);
		if ($ret){
			echo "Error converting $fileloc to png\n";
			die;
		}
		exec('rm ' . $filesLocation . $fileloc . '.pdf',$output,$ret);
		if ($ret){
			echo "Error deleting $fileloc pdf file\n";
			die;
		}
	}
	else if ($_POST["type"] == 'txt') {
		// First, make an HTML page with the content in it
		$file = '/var/www/emvi/content/upload/'. $fileloc . '.html';
		$fcontent = "<pre>" . file_get_contents($filesLocation. $fileloc . '.txt') . "</pre>";
		file_put_contents($file,$fcontent);

		$convertopdf = 'xvfb-run --server-args="-screen 0, 800x600x24" wkhtmltopdf ' . $filesLocation . $fileloc . '.html ' . $filesLocation . $fileloc . '.pdf';
		$convertopng = 'convert ' . $filesLocation . $fileloc . '.pdf[0] ' . $filesLocation . $fileloc . '.png';
		exec($convertopdf,$output,$ret);
		if ($ret) {
			echo "Error fetching screen dump for $file\n";
			die;
		}
		exec($convertopng,$output,$ret);
		if ($ret){
			echo "Error converting $fileloc to png\n";
			die;
		}
		exec('rm ' . $filesLocation . $fileloc . '.pdf',$output,$ret);
		if ($ret){
			echo "Error deleting $fileloc pdf file\n";
			die;
		}
	}
	
	// Redirect the landing page back to the content main page
	header('Location: '. $siteUrl . 'member.php#!/content');
	
}
?>



<form name="aContent" method="post" enctype="multipart/form-data" onsubmit="return validateForm()" action="<?php echo $_SERVER['PHP_SELF'] . "?ID=" . $cid;?>">
<table width="450px"><tr>
		<td>Content name:</td>
		<td><input type="text" name="name" value="<?php echo $content['contentName'];?>" /></td>
	</tr>
	<tr>
		<td>Content description:</td>
		<td><input type="text" name="description" value="<?php echo $content['contentDescription'];?>" /></td>
	</tr>
	<tr>
		<td>Content file format:</td>
		<td>
		<select name="type">
<?php 
 $types = $con->get_content_types();
 $skip = 0;
 foreach($types as $t)
 {
 	if ($skip > 0)	
  		echo "<option name=\"type\" value=\"". $t['typeFormat'] ."\"";
 		if ($t['typeID'] == $content['contentType']) {
			echo " SELECTED ";
			}
		echo "\">".$t['typeFormat']."</option>";
 	++$skip;
 }
?>
		</select>
		</td>
	</tr>
	<tr>
	<td>Content keywords</td>
	<td><textarea rows="3" cols="20" name="keywords"><?php echo $content['contentKeywords'];?></textarea>
	</td>
	</tr>

	<tr>
		<td>Upload file:</td>
		<td><input type="file" name="file" id="file"></td>
		</tr>
		<tr>
		<td></td>
		<td><input type="submit" value="Update"></td>
		</tr>
			<?php if ($content['fileLocation'] != '') {?>
	<tr>
	<td colspan="2" width="85">Warning! A file has already been attached to this content.<br>
	Uploading a file will delete the old one and replace it<br>
	with the new one. To leave the file unchanged, leave the<br>file field blank.<br><br>
	
	</td>
	</tr></table>
	<?php }
	
	echo '<table><tr><td align="center">';
	
	if ($ftypename == 'html' || $ftypename == 'txt') {
		echo '<form><input type="button" value="Hot Edit Content" style="background-color:#c00; color:#fff;" ONCLICK="openWin()"></form></td></tr>
                <td align="center"><a href="content/upload/' . $fileloc . '.' . $ftypename . '" target="_blank"><img src="' . $siteUrl . 'content/upload/' . $fileloc . '.png" width="250">
                </a>';
	}
	else {
		echo '<a href="content/upload/' . $fileloc . '.' . $ftypename . '" target="_blank"><img src="' . $siteUrl . 'content/upload/' . $fileloc . '.' . $ftypename . '" width="250"></a>';

	}
		
	echo '</td></tr></table>';
		
?>
		
</form>

