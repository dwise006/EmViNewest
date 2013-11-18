<?php
$subNav = array(
	"Members ; user/members.php ; #F98408;",
	"My Account ; user/myaccount.php ; #F98408;",
);

set_include_path("../../");
include("../../inc/essentials.php");

include("../user/userclass.php");

session_start(); 
$me = new User();

$me = $me->withID($_SESSION['ID']);

$bot = true;
$page = "MyAccount";
$reqUrl = "myaccount.php";

		if (isset($_GET['msgID'])){
			include '../../pages/user/messageclass.php';
			$inform = new message();
			$inform->printMessage($_GET['msgID']);
		}

?>


<form name="userform" method="post" action="../<?php echo $_SERVER['PHP_SELF']."?".$bot."&".$page."&".$reqUrl;?>">
<table width="450px">
<tr>
<td valign="top">
<h3>Account Info</h3>
</td>
</tr>

<tr>

 <td valign="top">
  <label for="first_name">First Name *</label>
 </td>
 <td valign="top">
  <input  type="text" name="userFirstName" maxlength="50" size="30" value="<?php echo $me->userFirstName; ?>">
 </td>
</tr>
 
<tr>
 <td valign="top"">
  <label for="last_name">Last Name *</label>
 </td>
 <td valign="top">
  <input  type="text" name="userLastName" maxlength="50" size="30" value="<?php echo $me->userLastName; ?>">
 </td>
</tr>
<tr>
 <td valign="top">
  <label for="email">Email Address *</label>
 </td>
 <td valign="top">
  <input  type="text" name="userEMailAddress" maxlength="80" size="30" value="<?php echo $me->userEMailAddress; ?>">
 </td>
 
</tr>
<tr>
 <td valign="top">
  <label for="telephone">Telephone Number</label>
 </td>
 <td valign="top">
  <input  type="text" name="userPhoneNumber" maxlength="30" size="30" value="<?php echo $me->userPhoneNumber; ?>">
 </td>
</tr>
<tr>
 <td colspan="2" style="text-align:center">
  <input name="case" type="hidden" value="userform">

  <input type="submit" Name="Update" value="Update">
 </td>
</tr>
</table>
</form>


<form name="cPassword" method="post" action="../<?php echo $_SERVER['PHP_SELF']."?".$bot."&".$page."&".$reqUrl;?>">
<table width="450px">

<tr><td>  </td></tr>
<tr><td>  </td></tr>

<tr>
<td valign="top">
<h3>Change Password</h3>
</td>
</tr>
<tr>
 <td valign="top">
  <label for="password">Password</label>
 </td>
 <td valign="top">
  <input  type="password" name="password">
 </td>
 
</tr>
<tr>
 <td valign="top">
  <label for="cpassword">Confirm Password</label>
 </td>
 <td valign="top">
  <input  type="password" name="cpassword">
 </td>
 
</tr>

<tr>
 <td colspan="2" style="text-align:center">
  <input name="case" type="hidden" value="userform">

  <input type="submit" Name="cPassword" value="Chang Password">

 </td>
</tr>
</table>
</form>
 
<p>* Field is required</p>
<?php
	if(isset($_POST['Update']))
	{
		$me->userFirstName = $_POST['userFirstName'];
		$me->userLastName = $_POST['userLastName'];
		$me->userEMailAddress = $_POST['userEMailAddress'];
		$me->userPhoneNumber = $_POST['userPhoneNumber'];
		
		
		
		$msgID = $me->user_update();
		include_once '../../config/general.php';
		header('Location: '.$siteUrl.'member.php#!/myaccount?msgID='.$msgID);
	}
	if(isset($_POST['cPassword'])){
			
			$msgID = $me->user_change_password($_POST['password'], $_POST['cpassword']);
			include_once '../../config/general.php';
			header('Location: '.$siteUrl.'member.php#!/myaccount?msgID='.$msgID);

			
		}
?>
 
