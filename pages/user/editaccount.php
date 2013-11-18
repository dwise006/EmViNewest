<?php
$subNav = array(
	"Administration ; admins.php ; #F98408;",
	"Accounts ; accountmaint.php ; #F98408;",
	"CDN setup ; cdnsetup.php ; #F98408;",
);

set_include_path("../");
include("../../inc/essentials.php");

include("userclass.php");

session_start(); 
$me = new User();
$me = $me->withID($_SESSION['ID']);
?>


<form name="userform" method="post" action="../member.php#!/account">
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
  <input  type="text" name="first_name" maxlength="50" size="30" value="<?php echo $me->userFirstName; ?>">
 </td>
</tr>
 
<tr>
 <td valign="top"">
  <label for="last_name">Last Name *</label>
 </td>
 <td valign="top">
  <input  type="text" name="last_name" maxlength="50" size="30" value="<?php echo $me->userLastName; ?>">
 </td>
</tr>
<tr>
 <td valign="top">
  <label for="email">Email Address *</label>
 </td>
 <td valign="top">
  <input  type="text" name="email" maxlength="80" size="30" value="<?php echo $me->userEMailAddress; ?>">
 </td>
 
</tr>
<tr>
 <td valign="top">
  <label for="telephone">Telephone Number</label>
 </td>
 <td valign="top">
  <input  type="text" name="telephone" maxlength="30" size="30" value="<?php echo $me->userPhoneNumber; ?>">
 </td>
</tr>
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

  <input type="submit" value="Update">
 </td>
</tr>
</table>
</form>
 
<p>* Field is required</p>

 
