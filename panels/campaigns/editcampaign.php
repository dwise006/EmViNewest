<h1>Edit Campaign</h1>
<?php
set_include_path("../");

/*Include sidebar */
//include("../../inc/sidebar.php");
//showSidebar("editcontent");
include_once '../../pages/include/config.php';

include_once '../../pages/campaigns/campaignclass.php';
//include_once '../../pages/workflow/workflowclass.php';
include_once '../../pages/user/userclass.php';
include_once '../../config/general.php';
include_once '../../config/functions.php';

session_start();

$con = new Campaign();
//$review = new Workflow();
if (isset($_GET['ID'])){
$campaign = $con->get_campaignByID($_GET['ID']);
$reviewers = $con->get_reviewers($_GET['ID']);
}
if (isset($_POST['campaignID'])){
$campaign = $con->get_campaignByID($_POST['campaignID']);
$reviewers = $con->get_reviewers($_POST['campaignID']);
}
$uid = $_SESSION['ID'];

?>

<script type="text/javascript">
function validateForm()
 {
 var name=document.forms["aCampaign"]["name"].value;
 var desc=document.forms["aCampaign"]["description"].value;
 
 if (name==null || name=="")
 {
   alert("Campaign name cannot be blank.");
   return false;
 }
 else if (desc==null || desc=="")
 {
 alert("Campaign description cannot be blank.");
 return false;
 }
 
}

function validateDel()
 {
 var r=confirm("Are you sure you want to delete this campaign?  This action will remove all reviewers and comments along with releasing the attached content for use by other campaigns.");
	if (r) {
		return true;
	 }
	 else {
	   return false;
	 } 
 
}

</script>

<?php 

// This section handles the page submission and the saving of data to the database
	if (!empty($_POST)) {
	// check to ensure that file type chosen is the same as file type of the file

		// check keywords. If the default string was left in the box, zero it out
		if ($_POST["keywords"] == '(comma delimited)'){
			$kw = '';
		}
		else {
			$kw = $_POST["keywords"];
		}
		$page = $_POST['page'];
		$ldate = $_POST['start_year']."-".$_POST['start_month']."-".$_POST['start_day'];
		$ldate=date("Y-m-d",strtotime($ldate));
		echo $ldate;

		// Function to write data to the DB is public function edit_campaign($cid,$uid,$name,$desc,$kw,$status,$ldate)
		$con->edit_campaign($_POST['campaignID'],$uid,$_POST["name"],$_POST["description"],$kw,$con->get_statusIDByName($_POST["status"]),$ldate);
				
		$reviewers = array();
		if ($_POST['Reviewer1'] != 'none'){
			$con->add_reviewers($_POST['campaignID'], $_POST['Reviewer1'], '1' );
			$reviewers[0] = $_POST['Reviewer1'];
		}
		if ($_POST['Reviewer2'] != 'none'){
			$con->add_reviewers($_POST['campaignID'], $_POST['Reviewer2'], '2');
			$reviewers[1] = $_POST['Reviewer2'];
		}
		if ($_POST['Reviewer3'] != 'none'){
			$con->add_reviewers($_POST['campaignID'], $_POST['Reviewer3'], '3');
			$reviewers[2] = $_POST['Reviewer3'];
		}
		if ($_POST['Reviewer4'] != 'none'){
			$con->add_reviewers($_POST['campaignID'], $_POST['Reviewer4'], '4');
			$reviewers[3] = $_POST['Reviewer4'];
		}
		if ($_POST['Reviewer5'] != 'none'){
			$con->add_reviewers($_POST['campaignID'], $_POST['Reviewer5'], '5');
			$reviewers[4] = $_POST['Reviewer5'];
		}
		
		$con->remove_reviewers($_POST['campaignID'], $reviewers);


		// Redirect the landing page back to the content main page
		if ($page == 'a'){
		header('Location: '.$siteUrl.'member.php#!/url=workflow/mytasks.php');
		}else{
		header('Location: '.$siteUrl.'member.php#!/campaigns');

		}
	}	

?>
<div style="overflow:auto">
<form name="aCampaign" method="post" enctype="multipart/form-data" onsubmit="return validateForm()" action="<?php echo $_SERVER['PHP_SELF'];?>">
<table width="450px"><tr>
		<td>Content name:</td>
		<td><input type="text" name="name" value="<?php echo $campaign['campaignName'];?>" /></td>
		</tr>
	<tr>
		<td>Content description:</td>
		<td><input type="text" name="description" value="<?php echo $campaign['campaignDescription'];?>" /></td>
		
	</tr>
	<tr>
	<td>Content keywords</td>
	<td><textarea rows="3" cols="20" name="keywords" value="<?php echo $campaign['campaignKeywords'];?>"><?php 
	if ($campaign['campaignKeywords'] != ""){
		echo $campaign['campaignKeywords'];
	}else{
		echo "(comma delimited)";
	}
	?>
	</textarea>
	</td>
	</tr>
	<tr>
		<td>Campaign Launch Date:</td>
		<td><table>
				<tr><td>Year</td><td>Month</td><td>Day</td></tr>
				<tr>
				<td><?php echo createYears(2000, 2030, 'start_year', date("Y")) ?></td>
				
				<td><?php echo createMonths('start_month', date("m")); ?></td>
				
				<td><?php echo createDays('start_day', date("d")); ?></td>
				
				</tr>
				
				</table>
		</td>
	</tr>
	
	<tr>
		<td>Campaign Status:</td>
		<td>
		<select name="status" value="New">
<?php 
 $types = $con->get_campaign_status();
 $skip = 0;
 $cStatus = $con->get_campaignStatusByID($campaign['campaignStatus']);
 foreach($types as $t)
 {
 	if ($skip > 0)
 		if ($t['wfStatusName'] == $cStatus){	
 		echo "<option selected>". $t['wfStatusName'] ."</option>";
 		}else{
 		echo "<option>". $t['wfStatusName'] ."</option>";

 		}
 	++$skip;
 }
?>
		</select>
		</td>
	</tr>
	
	
	<tr><td><h3>Add Reviewers</h3></td></tr>
<tr>
		<td valign="top">Campaign Reviewer 1:</td>
		<td>
		<select name="Reviewer1" >
		<option value="none">No Selection</option>

<?php 
	//echo "Test";
	//include_once '../../pages/user/userclass.php';
	//echo "Test";
	$allusers = new User();
 $list = $allusers->get_allusers();
 
 //print_r($list);
	$assignRev = mysql_fetch_assoc($reviewers);
 while ($user = mysql_fetch_assoc($list))
 {
 		if ($user['userID'] != $uid){
 			if ($assignRev['reviewerID'] == $user['userID']){
 				echo "<option value =\"".$user['userID']."\" selected>". $user['userLastName'] . ", " . $user['userFirstName'] . "</option>";
 			}else{
 				echo "<option value =\"".$user['userID']."\">". $user['userLastName'] . ", " . $user['userFirstName'] . "</option>";
 			}
 		}
 }
 mysql_data_seek($list ,0);
?>
		</select>
		</td>
		
			</tr>
			<tr>
		<td valign="top">Campaign Reviewer 2:</td>
		<td>
		<select name="Reviewer2" >
		<option value="none">No Selection</option>

<?php 
	
$assignRev = mysql_fetch_assoc($reviewers);
 while ($user = mysql_fetch_assoc($list))
 {
 		if ($user['userID'] != $uid){
 			if ($assignRev['reviewerID'] == $user['userID']){
 				echo "<option value =\"".$user['userID']."\" selected>". $user['userLastName'] . ", " . $user['userFirstName'] . "</option>";
 			}else{
 				echo "<option value =\"".$user['userID']."\">". $user['userLastName'] . ", " . $user['userFirstName'] . "</option>";
 			}
 		}
 }
 mysql_data_seek($list ,0);
?>
		</select>
		</td>
		
			</tr>
<tr>
		<td valign="top">Campaign Reviewer 3:</td>
		<td>
		<select name="Reviewer3" >
		<option value="none">No Selection</option>
<?php 
	

 $assignRev = mysql_fetch_assoc($reviewers);
 while ($user = mysql_fetch_assoc($list))
 {
 		if ($user['userID'] != $uid){
 			if ($assignRev['reviewerID'] == $user['userID']){
 				echo "<option value =\"".$user['userID']."\" selected>". $user['userLastName'] . ", " . $user['userFirstName'] . "</option>";
 			}else{
 				echo "<option value =\"".$user['userID']."\">". $user['userLastName'] . ", " . $user['userFirstName'] . "</option>";
 			}
 		}
 }
 mysql_data_seek($list ,0);
?>
		</select>
		</td>
		
			</tr>
			<tr>
		<td valign="top">Campaign Reviewer 4:</td>
		<td>
		<select name="Reviewer4" >
		<option value="none">No Selection</option>
<?php 
	

$assignRev = mysql_fetch_assoc($reviewers);
 while ($user = mysql_fetch_assoc($list))
 {
 		if ($user['userID'] != $uid){
 			if ($assignRev['reviewerID'] == $user['userID']){
 				echo "<option value =\"".$user['userID']."\" selected>". $user['userLastName'] . ", " . $user['userFirstName'] . "</option>";
 			}else{
 				echo "<option value =\"".$user['userID']."\">". $user['userLastName'] . ", " . $user['userFirstName'] . "</option>";
 			}
 		}
 }
 mysql_data_seek($list ,0);
?>
		</select>
		</td>
		
			</tr>
<tr>
		<td valign="top">Campaign Reviewer 5:</td>
		<td>
		<select name="Reviewer5" >
		<option value="none">No Selection</option>
<?php 
	

 $assignRev = mysql_fetch_assoc($reviewers);
 while ($user = mysql_fetch_assoc($list))
 {
 		if ($user['userID'] != $uid){
 			if ($assignRev['reviewerID'] == $user['userID']){
 				echo "<option value =\"".$user['userID']."\" selected>". $user['userLastName'] . ", " . $user['userFirstName'] . "</option>";
 			}else{
 				echo "<option value =\"".$user['userID']."\">". $user['userLastName'] . ", " . $user['userFirstName'] . "</option>";
 			}
 		}
 }
 mysql_data_seek($list ,0);
?>
		</select>
		</td>
		
			</tr>


	
		<tr>
		<td></td>
		<input type="hidden" name="page" value="<?php echo $_GET['page'];?>" />

		<input type="hidden" name="campaignID" value="<?php echo $_GET['ID'];?>" />
		<td><input type="submit" value="Update"></td>
		</tr>
		</table>
</form>

<p>The order in which you select your reviewers will determine their order of approval.</p>


<form name="dCampaign" method="post" enctype="multipart/form-data" onsubmit="return validateDel()" action="<?php 
if ($_GET['page'] == 'a'){
	echo "panels/campaigns/deletecampaign.php?ID=".$_GET['ID']."&page=".$_GET['page']; 
}else{
	echo "panels/campaigns/deletecampaign.php?ID=".$_GET['ID']; 
}


?>">
<input name="Delete" type="submit" value="Delete this Campaign" />
</form>
</div>
