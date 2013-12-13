<?php


session_start();
/* METRO UI TEMPLATE v4.b1
/*
/* Copyright 2013 Thomas Verelst, http://metro-webdesign.info
/* Do not redistribute or sell this template, nor claim this is your own work. 
/* Donation required when using this. */

require_once('inc/defaults.php');
require_once('admin/config.php');
require_once('config/general.php');
require_once('config/layout.php');
require_once('config/pages.php');
//require_once('config/mobile.php');

//require_once('inc/detectdevice.php');

/* TILE INITS */
require_once('inc/init.php');
require_once('inc/tilefunctions.php');

/* FILES*/
$cssFiles = array( /* Add your css files to this array */
	'css/layout.css',
	'css/nav.css',
	'css/tiles.css',
	'themes/'.$theme.'/theme.css',	
);
$jsFiles = array( /* Add your js files to this array */
	'js/functions.js',
	'js/main.js',	
);

/* PLUGIN SYSTEM */
require_once("inc/plugins.php");
foreach(glob("plugins/" . "*") as $folder){
	if(is_dir($folder) && !in_array($folder, $disabledPluginsDesktop)){
		if(file_exists($folder."/plugin.js")){
			$jsPluginsArray[] = $folder."/plugin.js";		
		}
		if(file_exists($folder."/plugin.css")){
			$cssPluginsArray[] = $folder."/plugin.css";		
		}
		if(file_exists($folder."/desktop.js")){
			$jsPluginsArray[] = $folder."/desktop.js";		
		}
		if(file_exists($folder."/desktop.css")){
			$cssPluginsArray[] = $folder."/desktop.css";		
		}
		if(file_exists($folder."/plugin.php")){
			include($folder."/plugin.php");
		}
	}
}

triggerEvent("beforeDoctype");
?>
<!DOCTYPE html>
<?php
triggerEvent("afterDoctype");


if(file_exists('themes/'.$theme.'/theme.js')){
	$jsFiles[] = 'themes/'.$theme.'/theme.js';
}
if(file_exists('themes/'.$theme.'/theme.php')){
	require_once('themes/'.$theme.'/theme.php');
}

triggerEvent("fileInclude");

require_once("inc/compress.php");
require_once("inc/seo.php");
?>
<?php // if(strpos($_SERVER['HTTP_USER_AGENT'],"Version/4.0")!=false&&strpos($_SERVER['HTTP_USER_AGENT'],"Android")!=false&&strpos($_SERVER['HTTP_USER_AGENT'],"AppleWebKit/")!=false){}?>
<html>
<head>
	<?php triggerEvent("headStart");?>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <meta name="Description" content="<?php echo $siteMetaDesc;?>"/>
    <meta name="keywords" content="<?php echo $siteMetaKeywords;?>"/>
    <link rel="icon" type="image/ico" href="<?php echo $favicon_url;?>"/>
    <meta name="viewport" content="width=1024,initial-scale=1.00, minimum-scale=1.00">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title><?php echo $siteTitle;?></title> 
    <META NAME="ROBOTS" CONTENT="INDEX, FOLLOW">
    <?php
	
    /*FONT*/
    if($googleFontURL != ""){?>
    	<link href='<?php echo $googleFontURL?>' rel='stylesheet' type='text/css'>
		<?php
	}

	/*CSS*/
	echo $css;
	include_once("inc/css.php");
	
	/*GA*/
	if($googleAnalyticsCode != ""){
		?><script type='text/javascript'>var _gaq = _gaq || [];_gaq.push(['_setAccount', '<?php echo $googleAnalyticsCode?>']);_gaq.push(['_trackPageview']);(function(){var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);})();</script><?php
	}
	?> 
    <!--[if lt IE 9]>
    <script type="text/javascript" language="javascript" src="http://code.jquery.com/jquery-1.9.1.min.js"></script>
    <script src="js/html5.js">
	<![endif]-->
	<!--[if gte IE 9]>
    <script src="http://code.jquery.com/jquery-2.0.0b2.js"></script>
    <script src="js/html5.js">
	<![endif]-->
    <!--[if !IE]>
    <script src="http://code.jquery.com/jquery-2.0.0b2.js"></script>
	<![endif]-->

    <script type="text/javascript">window.jQuery || document.write('<script type="text/javascript" src="js/jquery191.js"><\/script>')</script> 
    <script type="text/javascript" language="javascript" src="js/plugins.js"></script>
	<?php
	/*JS */
	include("inc/phptojs.php");
	if(!$bot){
		echo $js;
	}

    triggerEvent("headEnd");
	?>
    <noscript><style>#tileContainer{display:block}</style></noscript>
    <script type="text/javascript">
		function userNotExist(value) {
			
			if (value == 1){
				var r=alert("You did not fill in a required field.");
			}
			if (value == 2){
				var r=alert("That user does not exist in our database. Click the link below to register.");
			}
			if (value == 3){
				var r=alert("Account has not yet been enabled. Please try again later.");
			}
			if (value == 4){
				var r=alert("Your account has been locked.  Please contact the site admin for assistance.");
			}
			if (value == 5){
				var r=alert("Incorrect password, please try again.");
			}
			

		 }

	</script>

</head>
<body class="full <?php echo $device?>">
<?php
triggerEvent("bodyBegin");

/*BG image*/
if($bgImage!=""){
	echo "<img src='".$bgImage."' id='bgImage'/>";
}
?>
<header>
	<div id="headerWrapper">
		<div id="headerCenter">
			<div id="headerTitles">
				<h1><a href="<?php if($bot){echo "index.php";}?>#!"><?php echo $siteName?></a></h1>
		   		<h2><?php echo $siteDesc;?></h2>
		    </div>
		    <div align="center">
		    <form action="logon.php" method="post"> 
 				<table border="0" align="center" style="width: 422px"> 
 					<tr><td colspan=2 class="auto-style3"></td></tr> 
 					<tr><td style="color:black">Username:</td>
 						<td><input type="text" name="username" maxlength="50" style="width: 128px"></td>
 						<td style="width: 68px"></td>
 						<td></td>
 					</tr> 
 					<tr>
 						<td style="color:black">Password:</td>
 						<td><input type="password" name="pass" maxlength="50" style="width: 128px">&nbsp;<input type="submit" name="submit" value="Login"></td>
 						<td align="left"></td>
 						<td></td>
 					</tr> 
 					<tr>
 						<td colspan="4"><span style="color:black">If you do not have an account, please 
			<a href="registration.php">register here</a>.</span></td>
					</tr>
 				</table> 
 			</form>
 			</div> 
		</div>
    </div>
    <?php triggerEvent("headerEnd");?>
</header>

			
<div id="wrapper">

	
	<div id="centerWrapper">
	
		<?php
		
		if (isset($_GET['msg'])){
			include ("pages/user/messageclass.php");
			$inform = new message();
			$inform->printMessage($_GET['msg']);
		}else if (isset($_GET['error'])){
			echo "<script>";
			echo "userNotExist(".$_GET['error'].")";
			echo "</script>";
			
		}
	?>
	</div> 	    
</div>
<div align="center" style="font-family:arial;color:#000000;font-size:15px">
<table style="align:center;width:60%">
	<tr>
		<td style="padding:25px;vertical-align:middle"><a href="http://cs.odu.edu" target="_blank"><img src="http://411orangef13.cs.odu.edu:8080/emvi/content/upload/529d776a462d7.jpg"></a></td>
		<td style="vertical-align:top"><p>EmVi is a Computer Science senior project created for CS411W at Old Dominion University.
		</p>
		<p>
		This is a fully functioning prototype developed in PHP and MySQL. You can try it out by logging in with the following credentials:<br>
username: csuser@odu.edu<br>
password: BigBlue#1		<br>
</p><p>
		Read more on the <a href="http://www.cs.odu.edu/~411orang/" target="_blank">project page</a>.
		</p></td>
	</tr>
</table>
</div>
    
	<footer>
		<?php 
		echo $siteFooter; 
		triggerEvent("siteFooter");
		?>
    </footer>
    <?php triggerEvent("wrapperEnd");?>

	<?php

/*if($device=='mobileOnDesktop'){
	?>
	<a id="mobileOnDesktop" href="mobile.php">Go to mobile site</a>
    <?php
}*/
?>
<?php triggerEvent("bodyEnd");?>
</body>
</html>