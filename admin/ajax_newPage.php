<?php
session_start();
require_once("config.php"); 
if($_SESSION['ID_my_site']!=$username){
	die("It seems you're not logged in anymore. Page creation failed");
}
if(isset($_POST['name'])){
	$name = stripslashes($_POST['name']);
	if(!strstr($name,'.')){
		die("It seems your filename has no extension!");
	};
	if(file_exists("../pages/".$name)){
		die("File already exists!");
	}
	fopen("../pages/".$name, 'w') or die("Can't create file. Retry or check write permissions?");
	echo "yes";
}else{
	die("Something went wrong, try again or reload this page.");
}
?>