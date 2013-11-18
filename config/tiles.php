<?php
/* All tiles on the homepage are configured here, be sure to check the tutorials/docs on http://metro-webdesign.info */

/* GROUP 0 My Work*/
include("DB_Connect.php");
session_start();
$uid = $_SESSION['ID'];
$work  = mysql_query("SELECT * FROM tbl_campaigns WHERE updatedBy = '".$uid."'");
		if (!$work) {    
				die("Query to show fields from table failed tiles.php Line 8");
		}
$review = mysql_query("SELECT * FROM tbl_reviewers left join tbl_campaigns on tbl_campaigns.campaignID = tbl_reviewers.campaignID WHERE reviewerID = '".$uid."' and isComplete = 0  and canEdit = 0");
		if (!$review) {    
				die("Query to show fields from table failed tiles.php Line 12");
		}
		
$allOpenWork = mysql_query("SELECT * FROM `tbl_campaigns` WHERE `campaignStatus` != 5 and `campaignStatus` != 4");
		if (!$allOpenWork) {    
				die("Query to show fields from table failed tiles.php Line 8");
		}

$allInReview = mysql_query("SELECT * FROM `tbl_campaigns` WHERE `campaignStatus` != 3");
		if (!$allInReview) {    
				die("Query to show fields from table failed tiles.php Line 8");
		}
$today = date('Y-m-d');
$allAddedToday = mysql_query("SELECT * FROM `tbl_campaigns` WHERE STR_TO_DATE(`createdDate`, '%Y-%m-%d') = '".$today."'");
		if (!$allAddedToday) {    
				die("Query to show fields from table failed tiles.php Line 8");
		}



$myNum = mysql_num_rows($work);
$myRev = mysql_num_rows($review);
$openTasks = mysql_num_rows($allOpenWork);
$allReview = mysql_num_rows($allInReview);
$addedToday = mysql_num_rows($allAddedToday);

$tile[] = array("type"=>"simple","group"=>0,"x"=>0,"y"=>0,'width'=>2,'height'=>1,"background"=>"#000080","url"=>"workflow/mytasks.php",
"title"=>"<div style='color:#FFFFFF;'>My Tasks</div>","text"=>"<div style='color:#FFFFFF;'>".$myNum." incomplete campaigns in work <br> ".$myRev." campaigns awaiting my review</div>");

$tile[] = array("type"=>"simple","group"=>0,"x"=>0,"y"=>1,'width'=>2,'height'=>1,"background"=>"#000080","url"=>"workflow/addedtoday.php",
"title"=>"<div style='color:#FFFFFF;'>All Tasks</div>","text"=>"<div style='color:#FFFFFF;'>".$addedToday." tasks added today<br>".$openTasks." incomplete tasks total <br> ".$allReview." tasks currently in review</div>");

// $tile[] = array("type"=>"slideshow","group"=>0,"x"=>0,"y"=>1,"width"=>1,"height"=>1,"background"=>"#6950ab","url"=>"",
	// "images"=>array("img/img1.png","img/img2.jpg","img/img3.jpg"),
	// "effect"=>"slide-right","speed"=>5000,"arrows"=>true,
	// "labelText"=>"Slideshow","labelColor"=>"#11528f","labelPosition"=>"bottom",
	// "classes"=>"noClick");

 /*$tile[] = array("type"=>"scrollText","group"=>0,"x"=>2,"y"=>1,"width"=>2,"height"=>1,"background"=>"#FF8000","url"=>"external:panels/example.php",
"title"=>"Click to open a sidepanel","text"=>array(
 "A sidepanel will come from the right, watch out!",
 "Okay, and what you are watching now is a scroll live tile...",
 "which can be very cool",
 "to open a sidepanel, check this source code in tiles.php"
 ),"scrollSpeed"=>2500);
*/
// $tile[] = array("type"=>"simple","group"=>0,"x"=>0,"y"=>2,'width'=>2,'height'=>1,"background"=>"#6950AB","url"=>"newtab:http://metro-webdesign.info/#!/docs-and-tutorials",
	// "title"=>"<span style='font-size:24px;'>Check the tutorials</span>",
	// "text"=>"be <em>CREATIVE</em> and <em>MODIFY</em> this example. It's just an example, play with the tiles!",
	// "img"=>"img/icons/box_warning.png","imgSize"=>"50","imgToTop"=>"5","imgToLeft"=>"5",
	// "labelText"=>"Metro-Webdesign","labelColor"=>"#453B5E","labelPosition"=>"bottom");

// $tile[] = array("type"=>"custom","group"=>0,"x"=>2,"y"=>0,'width'=>1,'height'=>1,"background"=>"#11528f","url"=>"typography.php",
// "content"=>
// "<div style='line-height:30px; margin-top:5px;'>
// <div style='color:#FFF;font-size:43px;line-heigt:70px;'><strong>CHECK</strong></div>
// <span style='color:#FFF;font-size:32px;'><strong>OUT</strong></span><span style='color:#BBB;font-size:32px;'>THE</span>
// <div style='font-size:57px;line-height:30px;'>TYPO</div>
// <div style='font-size:37px;line-height:40px;'>GRAPHY</div>
// </div>");

/* GROUP 1 Campaigns*/

$tile[] = array("type"=>"slide","group"=>1,"x"=>0,"y"=>0,'width'=>2,'height'=>1,"background"=>"#CC0000","url"=>"campaigns/campaigns.php",
	"text"=>"<h3 style='color:#FFFFFF;'>All Campaigns</h3>","img"=>"img/Campaigns_banner.jpg","imgSize"=>1,
	"slidePercent"=>0.40,
	"slideDir"=>"left", // can be up, down, left or right
	"doSlideText"=>true,"doSlideLabel"=>true,
	"labelText"=>"<div style='color:#FFFFFF;'>View</div>","labelColor"=>"#CC0000","labelPosition"=>"top",
);

$tile[] = array("type"=>"simple","group"=>1,"x"=>2,"y"=>0,'width'=>1,'height'=>1,"background"=>"#CC0000","url"=>"../panels/campaigns/addcampaign.php",
		"title"=>"<div style='color:#FFFFFF;'>New</div>","text"=>"<div style='color:#FFFFFF;'>Create Campaign</div>");

//Get all of the upcomming campaign releases

$sql = "SELECT `campaignName`, `launchDate` FROM `tbl_campaigns` WHERE 1 order by `launchDate` asc limit 9 ";
$query = mysql_query($sql);
if (!$query) {    
				die("Query to show fields from table failed tiles.php line 57");
		}

$upcomming = array();
$count = 0;
while($row = mysql_fetch_assoc($query)){ 
	array_push($upcomming , ("<table style='color:#FFFFFF; font-size:15px'><tr><td width=\"210px\">".$row['campaignName'] . "</td><td> " . date("m/d/Y", strtotime($row['launchDate']))."</td></tr>" ));
	if ($row = mysql_fetch_assoc($query)){
		$upcomming[$count] = $upcomming[$count] . "<tr><td width=\"210px\">".$row['campaignName'] . "</td><td> " . date("m/d/Y", strtotime($row['launchDate'])) . "</td></tr>";

		if ($row = mysql_fetch_assoc($query)){
			$upcomming[$count] = $upcomming[$count] . "<tr><td width=\"210px\">".$row['campaignName'] . "</td><td> " . date("m/d/Y", strtotime($row['launchDate'])). "</td></tr></table>";
		}else{
			$upcomming[$count] = $upcomming[$count] . "</table>";
		}
	}else{
		$upcomming[$count] = $upcomming[$count] . "</table>";
	}
	$count++;
}

$tile[] = array("type"=>"scrollText","group"=>1,"x"=>0,"y"=>1,"width"=>3,"height"=>1,"background"=>"#FF0000","url"=>"external:panels/example.php",
"title"=>"<div style='color:#FFFFFF; font-size:45px; font-weight:bolder'>Upcoming Events</div>","text"=>$upcomming,"scrollSpeed"=>5000);
 
/*$tile[] = array("type"=>"slide","group"=>1,"x"=>0,"y"=>1,'width'=>2,'height'=>1,"background"=>"#00BFFF","url"=>"sidebars.php",
	"text"=>"<h3>A page with sidebar</h3>","img"=>"img/metro_slide_300x150_2.png","imgSize"=>1,
	"slidePercent"=>0.40,
	"slideDir"=>"up", // can be up, down, left or right
	"doSlideText"=>true,"doSlideLabel"=>true,
	"labelText"=>"A slide tile","labelColor"=>"#00BFFF","labelPosition"=>"top",
);

$tile[] = array("type"=>"slideshow","group"=>1,"x"=>2,"y"=>0,"width"=>1,"height"=>1,"background"=>"#6950ab","url"=>"newtab:http://google.com",
	"images"=>array("img/chars/a.png","img/chars/b.png","img/chars/c.png","img/chars/d.png","img/chars/e.png","img/chars/f.png","img/chars/g.png"),
	"effect"=>"slide-right, slide-left, slide-down, slide-up, flip-vertical, flip-horizontal, fade",
	"speed"=>1500,"arrows"=>false,
	"labelText"=>"Random fx","labelColor"=>"#453B5E","labelPosition"=>"top");
	
$tile[] = array("type"=>"flip","group"=>1,"x"=>2,"y"=>1,'width'=>1,'height'=>1,"background"=>"#C82345","url"=>"accordions.php","img"=>"img/metro_150x150.png",
	"text"=>"<h4 style='color:#FFF;'>Click for accordions!</h4>");
*/	
/* GROUP 2 Content*/

$tile[] = array("type"=>"slide","group"=>2,"x"=>0,"y"=>0,'width'=>2,'height'=>1,"background"=>"#990099","url"=>"email/email.php",
		"text"=>"<h3 style='color:#FFFFFF;'>All Email</h3>","img"=>"img/email-marketing.jpg","imgSize"=>1,
		"slidePercent"=>0.40,
		"slideDir"=>"left", // can be up, down, left or right
		"doSlideText"=>false,"doSlideLabel"=>false,
		"labelText"=>"<div style='color:#FFFFFF;'>View</div>","labelColor"=>"#990099","labelPosition"=>"top");

$tile[] = array("type"=>"simple","group"=>2,"x"=>2,"y"=>0,'width'=>1,'height'=>1,"background"=>"#990099","url"=>"../panels/email/addemail.php",
		"title"=>"<div style='color:#FFFFFF;'>New</div>","text"=>"<div style='color:#FFFFFF;'>Create Email</div>");

$tile[] = array("type"=>"simple","group"=>2,"x"=>0,"y"=>1,'width'=>1,'height'=>1,"background"=>"#336699","url"=>"../panels/content/addcontent.php",
		"title"=>"<div style='color:#FFFFFF;'>New</div>","text"=>"<div style='color:#FFFFFF;'>Create Content</div>");


$tile[] = array("type"=>"slide","group"=>2,"x"=>1,"y"=>1,'width'=>2,'height'=>1,"background"=>"#336699","url"=>"content/content.php",
		"text"=>"<h3 style='color:#FFFFFF;'>All Content</h3>","img"=>"img/New_Content.jpg","imgSize"=>1,
		"slidePercent"=>0.40,
		"slideDir"=>"left", // can be up, down, left or right
		"doSlideText"=>false,"doSlideLabel"=>false,
		"labelText"=>"<div style='color:#FFFFFF;'>View</div>","labelColor"=>"#336699","labelPosition"=>"top");



/* GROUP 3 Search*/

$tile[] = array("type"=>"simple","group"=>3,"x"=>0,"y"=>0,'width'=>2,'height'=>1,"background"=>"#008000","url"=>"search/search.php",
"title"=>"<div style='color:#FFFFFF;'>Find</div>","text"=>"<div style='color:#FFFFFF;'>Search campaigns, emails or content</div>");


/* GROUP 4 My Account*/
$tile[] = array("type"=>"simple","group"=>4,"x"=>0,"y"=>0,'width'=>2,'height'=>1,"background"=>"#800000","url"=>"user/members.php",
"title"=>"<div style='color:#FFFFFF;'>Update</div>","text"=>"<div style='color:#FFFFFF;'>Change email, name or password</div>");


/* GROUP 5 Help*/


$tile[] = array("type"=>"simple","group"=>5,"x"=>0,"y"=>0,'width'=>1,'height'=>1,"background"=>"#FFFF00","url"=>"help/help.php",
		"title"=>"<div style='color:#000000;'>User Guide</div>","text"=>"<div style='color:#000000;'>Learn how EmVi works!</div>");

$tile[] = array("type"=>"slide","group"=>5,"x"=>1,"y"=>0,'width'=>2,'height'=>1,"background"=>"#FFFF00","url"=>"help/about.php",
	"text"=>"<h3 style='color:#000000;'>About the Project</h3>","img"=>"img/ODU_sig_idea_reversed.jpg","imgSize"=>1,
	"slidePercent"=>0.50,
	"slideDir"=>"left", // can be up, down, left or right
	"doSlideText"=>false,"doSlideLabel"=>false,
	"labelText"=>"<div style='color:#FFFFFF;'>About Us</div>","labelColor"=>"#F98408","labelPosition"=>"top");

/*
 $tile[] = array("type"=>"img","group"=>5,"x"=>0,"y"=>0,'width'=>1,'height'=>1,"background"=>"#F98408","url"=>"help/help.php",
 		"img"=>"img/img3.jpg","desc"=>"Click here for help!",
 		"showDescAlways"=>true,"imgWidth"=>2,"imgHeight"=>1,
 		"labelText"=>"Img Tile","labelColor"=>"#509601","labelPosition"=>"bottom", "classes"=>"noClick");
*/


/* GROUP 6 Administration*/

include("DB_Connect.php");
session_start(); 

$query = mysql_query("SELECT * FROM tbl_user WHERE userID = '".$_SESSION['ID']."'");
	if (!$query) {    
		die("Query to show fields from table failed tiles.php Line 84");
	}
$user = mysql_fetch_assoc($query);

if ($user['userRole'] == 1){
	
$tile[] = array("type"=>"simple","group"=>6,"x"=>0,"y"=>0,'width'=>2,'height'=>1,"background"=>"#008080","url"=>"admin/admins.php",
"title"=>"<div style='color:#FFFFFF;'>Site Maintenance</div>","text"=>"<div style='color:#FFFFFF;'>Go here to set things up!</div>");
}




?>