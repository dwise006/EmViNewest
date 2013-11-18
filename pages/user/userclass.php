<?php


class User
{
	public $userID = 1;  			// assigned at registration
	public $userFirstName;		//Registered users first name
	public $userLastName;		//Registered users last name
	public $userEMailAddress;				//Registered users Email address
	public $userPhoneNumber;		//Registered users phone number
	public $userRole;			//Registered users access role.  Assiged by an EMVI administrator
	public $userAccountStatus;		//states whethere the account is active or not
	
	/*this constructor profides a way create an instance of the currently logged on user*/
	public function __construct() {
	
	
    	include("../../config/DB_Connect.php");
		session_start(); 

		$query = mysql_query("SELECT * FROM `tbl_user` WHERE userID = '".$_SESSION['ID']."'");
		if (!$query) {    
				die("Query to show fields from table failed userclass.php line 20");
		}
		$user = mysql_fetch_assoc($query);

		
		$this->userID = $user['userID'];
		$this->userFirstName = $user['userFirstName'];
		$this->userLastName = $user['userLastName'];
		$this->userEMailAddress = $user['userEMailAddress'];
		$this->userPhoneNumber = $user['userPhoneNumber'];
		$this->userRole = $user['userRole'];
		$this->userAccountStatus = $user['userAccountStatus'];
		
	}
	
	/*withID($id) provides a way to create an instance of another user of the system if you have 
	the userID*/
	public static function withID( $id ) {
    	$instance = new self();
    	$instance->loadByID( $id );
    	return $instance;
    }
	
	/*withRow( array $row ) provides a way to create an instance of another user of the system if 
	you have the users row from the user table*/
    public static function withRow( array $row ) {
    	$instance = new self();
    	$instance->fill( $row );
    	return $instance;
    	
    }

    protected function loadByID( $id ) {
    	include("../../config/DB_Connect.php");
		

		$query = mysql_query("SELECT * FROM tbl_user WHERE userID = '".$id."'");
		if (!$query) {    
				die("Query to show fields from table failed userclass.php Line 58");
		}
		$user = mysql_fetch_assoc($query);

    	
    	$this->fill( $user );
    }

    protected function fill( array $user ) {
    	
    	$this->userID = $user['userID'];
		$this->userFirstName = $user['userFirstName'];
		$this->userLastName = $user['userLastName'];
		$this->userEMailAddress = $user['userEMailAddress'];
		$this->userPhoneNumber = $user['userPhoneNumber'];
		$this->userRole = $user['userRole'];
		$this->userAccountStatus = $user['userAccountStatus'];
		
    }

	
	/*Update user info in the database */
	public function user_update(){ 
  		
  		include("../../config/DB_Connect.php");
  		include '../../config/functions.php';

  		if (!check_email_address($_POST['userEMailAddress'])){
 		return 405;
 		}

			
  		mysql_query("UPDATE tbl_user SET userFirstName = '".$this->userFirstName."', userLastName = '".$this->userLastName."', userEMailAddress = '".$this->userEMailAddress."', userPhoneNumber = '".$this->userPhoneNumber."', userAccountStatus = '".$this->userAccountStatus."', userRole = '".$this->userRole."' WHERE userID = '".$this->userID."'")or die(mysql_error());
  		return 701;
  		
  		
  			
	}

	/*Update user password in the database */
	public function user_change_password($pass, $cpass){
		include '../../config/functions.php';
		// this makes sure both passwords entered match and are of proper length and complexity
	 	if (!check_password($pass)){
	 		return 404;
	 		//die('Your password does ot meet the minumum requiremetns posted on the registration page.  Please resubmit.');
	 	}
	
	 	if ($pass != $cpass) {
	 		return 406;
	 		//die('Your passwords did not match. ');
	 	}

 	// here we encrypt the password and add slashes if needed
	 	$pass = md5($pass);
	 	if (!get_magic_quotes_gpc()) {
	 		$pass = addslashes($pass);
	 		}
		include("../../config/DB_Connect.php");

  		mysql_query("UPDATE tbl_user SET userPassword = '".$pass."'  WHERE userID = '".$this->userID."'")or die(mysql_error());

			return 704;
		
	}
	
	/*Handles the logon for a user. Creates the session*/
	public function logon($username, $password){
	
	}
	
	/*Handles logoff action for a user.  Destroys the session.*/
	public function logoff(){
		session_start(); 
		$_SESSION['loggedout'] = 'yes';	
 		$past = time() - 4000; 
 		//this makes the time in the past to destroy the cookie 
 		setcookie(ID_my_site, gone, $past); 
 		setcookie(Key_my_site, gone, $past); 
 		include_once '../../config/general.php';
		header('Location: '.$siteUrl.''); 
	}
	
	/*Handles the removal of a user from the database*/
	public function userRemove(){
		
		mysql_query("DELETE FROM tbl_user Where userID='".$this->userID."'");
		return 703;

	}
	
	/*Handles the addition of a user from the database*/
	public function user_add(){
			
					return 410;

	}
	public function get_allusers(){
	
		include("../../config/DB_Connect.php");	
		$allusers = mysql_query("SELECT `userID`,`userLastName`,`userFirstName` FROM `tbl_user` ORDER BY `userLastName`");
		if (!$allusers) {    
				die("Query to show fields from table failed userclass.php Line 156");
		}
		return $allusers;

	
	}

}

?>