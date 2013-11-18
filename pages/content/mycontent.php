<?php 
header("Cache-Control: no-cache, must-revalidate"); // HTTP/1.1
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
?>
<style type="text/css">
th {
	width:140px;
	font-weight: normal;
	font-size: small;
	color: #FAF6F6;
	border-bottom-style: solid;
	border-bottom-width: 1px;
	background-color: #000080;
	text-align: center;
}
td {
	width: 110px;
	font-weight: normal;
	font-size: small;
	color: #000000;
	border-bottom-style: solid;
	border-bottom-width: 1px;
	background-color: #C0C0C0;
	text-align: center;
}
</style>
<h1>My Content</h1>
<?php

$subNav = array(
	"All Content ; content/content.php ; #F98408;",
	"My Content ; content/mycontent.php ; #F98408;",
	"Add Content ; ../panels/content/addcontent.php ; #F98408;", 
);

set_include_path("../");
include_once '../../inc/essentials.php';
include '../../../config/DB_Class.php';
include_once 'contentclass.php';
session_start();
$uid = $_SESSION['ID'];

?>
<script>
$mainNav.set("content"); // this line colors the top button main nav with the text "home"
</script>

<?php

if(!isset($_GET['orderBy'])) {
	$orderby = 'UpdatedDate';
	$dir = 'DESC';
}
else {
	$orderby = $_GET['orderBy'];
	$dir = $_GET['dir'];
}

$content = new Content();
$types = $content->get_content($orderby,$dir);
// Function returns the following beginning in row 1:
// ID, Name, Description, Keywords, TypeID, Format, CreatedDate, CreatedByID, CreatedByName, 
// UpdatedDate, UpdatedByID, UpdatedByName, OwnedByID, OwnedByName, FileName

$contentList = '';
for ($i = 1; $i < count($types); ++$i) {
	if ($uid == $types[$i]['OwnedByID']) {
			$contentList .= '<tr><td>'. htmlentities($types[$i]['Name']) . '</td>
			<td>' . htmlentities($types[$i]['Format']) . '</td>
			<td>' . htmlentities($types[$i]['Description']) . '</td>
			<td>' . date("m/d/Y g:i a", strtotime($types[$i]['UpdatedDate'])) . '</td>
			<td>' . htmlentities($types[$i]['OwnedByName']) . '</td>
			<td>';
		if ($types[$i]['FileName'] != '') {
			$contentList .= '<a href="content/upload/'. $types[$i]['FileName'] .'" target="_blank">View</a>';
		}
		$contentList .=	'</td><td>';
		if ($types[$i]['OwnedByID'] == $uid) {
			$contentList .= 'Edit';
		}
		$contentList .= '</td>	
			<td>Clone</td>
			<td>';
		if ($types[$i]['OwnedByID'] == $uid) {
			$contentList .= 'Delete';
		}	
	$contentList .= '</td>
		<td><a href="panels/content/publishcontent.php?ID='.$types[$i]['ID'].'">Publish</a></td>
		</tr>';
		
	}
}

//DISPLAY THE LOCKED BY TABLE
echo 'With You As Current Owner <br />';
echo '<table>';
echo '<tr>';
if (strtolower($orderby) == 'name' && strtolower($dir) == 'asc') {
	echo '<th><a href="member.php#!/content/mycontent.php?orderBy=Name&dir=desc" style="text-decoration:none; color:white;">Content Name</th>';
} else {
	echo '<th><a href="member.php#!/content/mycontent.php?orderBy=Name&dir=asc" style="text-decoration:none; color:white;">Content Name</th>';
}
if (strtolower($orderby) == 'format' && strtolower($dir) == 'asc') {
	echo '<th><a href="member.php#!/content/mycontent.php?orderBy=Format&dir=desc" style="text-decoration:none; color:white;">Format</th>';
} else {
	echo '<th><a href="member.php#!/content/mycontent.php?orderBy=Format&dir=asc" style="text-decoration:none; color:white;">Format</th>';
}
if (strtolower($orderby) == 'keywords' && strtolower($dir) == 'asc') {
	echo '<th><a href="member.php#!/content/mycontent.php?orderBy=Keywords&dir=desc" style="text-decoration:none; color:white;">Keywords</th>';
} else {
	echo '<th><a href="member.php#!/content/mycontent.php?orderBy=Keywords&dir=asc" style="text-decoration:none; color:white;">Keywords</th>';
}
if (strtolower($orderby) == 'updatedate' && strtolower($dir) == 'asc') {
	echo '<th><a href="member.php#!/content/mycontent.php?orderBy=UpdatedDate&dir=desc" style="text-decoration:none; color:white;">Last Updated</th>';
} else {
	echo '<th><a href="member.php#!/content/mycontent.php?orderBy=UpdatedDate&dir=asc" style="text-decoration:none; color:white;">Last Updated</th>';
}
if (strtolower($orderby) == 'ownedby' && strtolower($dir) == 'asc') {
	echo '<th><a href="member.php#!/content/mycontent.php?orderBy=OwnedByName&dir=desc" style="text-decoration:none; color:white;">Locked By</th>';
} else {
	echo '<th><a href="member.php#!/content/mycontent.php?orderBy=OwnedByName&dir=asc" style="text-decoration:none; color:white;">Locked By</th>';
}
echo '<th colspan="5"></th>';
echo '</tr>';
echo $contentList;
echo '</table><br /><br />';

$contentList = '';
for ($i = 1; $i < count($types); ++$i) {
	if ($uid == $types[$i]['CreatedByID']) {
		$contentList .= '<tr><td>'. htmlentities($types[$i]['Name']) . '</td>
			<td>' . htmlentities($types[$i]['Format']) . '</td>
			<td>' . htmlentities($types[$i]['Description']) . '</td>
			<td>' . date("m/d/Y g:i a", strtotime($types[$i]['UpdatedDate'])) . '</td>
			<td>' . htmlentities($types[$i]['OwnedByName']) . '</td>
			<td>';
		if ($types[$i]['FileName'] != '') {
			$contentList .= '<a href="content/upload/'. $types[$i]['FileName'] .'" target="_blank">View</a>';
		}
		$contentList .=	'</td><td>';
		if ($types[$i]['OwnedByID'] == $uid) {
			$contentList .= 'Edit';
		}
		$contentList .= '</td>
			<td>Clone</td>
			<td>';
		if ($types[$i]['OwnedByID'] == $uid) {
			$contentList .= 'Delete';
		}
		$contentList .= '</td>
		<td><a href="panels/content/publishcontent.php?ID='.$types[$i]['ID'].'">Publish</a></td>
		</tr>';
	}
}
//DISPLAY THE CREATED BY TABLE
echo 'With You as Creator <br />';
echo '<table width = "100%" cellpadding="3" cellspacing="1" border="1">';
echo '<tr>';
if (strtolower($orderby) == 'name' && strtolower($dir) == 'asc') {
	echo '<td style="min-width:100px;font-weight:bold;"><a href="member.php#!/content/mycontent.php?orderBy=Name&dir=desc" style="text-decoration:none;">Content Name</td>';
} else {
	echo '<td style="min-width:100px;font-weight:bold;"><a href="member.php#!/content/mycontent.php?orderBy=Name&dir=asc" style="text-decoration:none;">Content Name</td>';
}
if (strtolower($orderby) == 'format' && strtolower($dir) == 'asc') {
	echo '<td style="min-width:25px;font-weight:bold;"><a href="member.php#!/content/mycontent.php?orderBy=Format&dir=desc" style="text-decoration:none;">Format</td>';
} else {
	echo '<td style="min-width:25px;font-weight:bold;"><a href="member.php#!/content/mycontent.php?orderBy=Format&dir=asc" style="text-decoration:none;">Format</td>';
}
if (strtolower($orderby) == 'description' && strtolower($dir) == 'asc') {
	echo '<td style="min-width:100px;font-weight:bold;"><a href="member.php#!/content/mycontent.php?orderBy=Description&dir=desc" style="text-decoration:none;">Description</td>';
} else {
	echo '<td style="min-width:100px;font-weight:bold;"><a href="member.php#!/content/mycontent.php?orderBy=Description&dir=asc" style="text-decoration:none;">Description</td>';
}
if (strtolower($orderby) == 'updatedate' && strtolower($dir) == 'asc') {
	echo '<td style="min-width:100px;font-weight:bold;"><a href="member.php#!/content/mycontent.php?orderBy=UpdatedDate&dir=desc" style="text-decoration:none;">Last Updated</td>';
} else {
	echo '<td style="min-width:100px;font-weight:bold;"><a href="member.php#!/content/mycontent.php?orderBy=UpdatedDate&dir=asc" style="text-decoration:none;">Last Updated</td>';
}
if (strtolower($orderby) == 'ownedby' && strtolower($dir) == 'asc') {
	echo '<td style="min-width:100px;font-weight:bold;"><a href="member.php#!/content/mycontent.php?orderBy=OwnedByName&dir=desc" style="text-decoration:none;">Locked By</td>';
} else {
	echo '<td style="min-width:100px;font-weight:bold;"><a href="member.php#!/content/mycontent.php?orderBy=OwnedByName&dir=asc" style="text-decoration:none;">Locked By</td>';
}
echo '<td colspan="5"></td>';
echo '</tr>';
echo $contentList;
echo '</table><br /><br />';



$contentList = '';
for ($i = 1; $i < count($types); ++$i) {
	if ($uid == $types[$i]['UpdatedByID']) {
		$contentList .= '<tr><td>'. htmlentities($types[$i]['Name']) . '</td>
			<td>' . htmlentities($types[$i]['Format']) . '</td>
			<td>' . htmlentities($types[$i]['Description']) . '</td>
			<td>' . date("m/d/Y g:i a", strtotime($types[$i]['UpdatedDate'])) . '</td>
			<td>' . htmlentities($types[$i]['OwnedByName']) . '</td>
			<td>';
		if ($types[$i]['FileName'] != '') {
			$contentList .= '<a href="content/upload/'. $types[$i]['FileName'] .'" target="_blank">View</a>';
		}
		$contentList .=	'</td><td>';
		if ($types[$i]['OwnedByID'] == $uid) {
			$contentList .= 'Edit';
		}
		$contentList .= '</td>
			<td>Clone</td>
			<td>';
		if ($types[$i]['OwnedByID'] == $uid) {
			$contentList .= 'Delete';
		}
		$contentList .= '</td>
		<td><a href="panels/content/publishcontent.php?ID='.$types[$i]['ID'].'">Publish</a></td>
		</tr>';
		
	}
}
//DISPLAY THE UPDATED BY TABLE
echo 'With You as Last Updated <br />';
echo '<table width = "100%" cellpadding="3" cellspacing="1" border="1">';
echo '<tr>';
if (strtolower($orderby) == 'name' && strtolower($dir) == 'asc') {
	echo '<td style="min-width:100px;font-weight:bold;"><a href="member.php#!/content/mycontent.php?orderBy=Name&dir=desc" style="text-decoration:none;">Content Name</td>';
} else {
	echo '<td style="min-width:100px;font-weight:bold;"><a href="member.php#!/content/mycontent.php?orderBy=Name&dir=asc" style="text-decoration:none;">Content Name</td>';
}
if (strtolower($orderby) == 'format' && strtolower($dir) == 'asc') {
	echo '<td style="min-width:25px;font-weight:bold;"><a href="member.php#!/content/mycontent.php?orderBy=Format&dir=desc" style="text-decoration:none;">Format</td>';
} else {
	echo '<td style="min-width:25px;font-weight:bold;"><a href="member.php#!/content/mycontent.php?orderBy=Format&dir=asc" style="text-decoration:none;">Format</td>';
}
if (strtolower($orderby) == 'description' && strtolower($dir) == 'asc') {
	echo '<td style="min-width:100px;font-weight:bold;"><a href="member.php#!/content/mycontent.php?orderBy=Description&dir=desc" style="text-decoration:none;">Description</td>';
} else {
	echo '<td style="min-width:100px;font-weight:bold;"><a href="member.php#!/content/mycontent.php?orderBy=Description&dir=asc" style="text-decoration:none;">Description</td>';
}
if (strtolower($orderby) == 'updatedate' && strtolower($dir) == 'asc') {
	echo '<td style="min-width:100px;font-weight:bold;"><a href="member.php#!/content/mycontent.php?orderBy=UpdatedDate&dir=desc" style="text-decoration:none;">Last Updated</td>';
} else {
	echo '<td style="min-width:100px;font-weight:bold;"><a href="member.php#!/content/mycontent.php?orderBy=UpdatedDate&dir=asc" style="text-decoration:none;">Last Updated</td>';
}
if (strtolower($orderby) == 'ownedby' && strtolower($dir) == 'asc') {
	echo '<td style="min-width:100px;font-weight:bold;"><a href="member.php#!/content/mycontent.php?orderBy=OwnedByName&dir=desc" style="text-decoration:none;">Locked By</td>';
} else {
	echo '<td style="min-width:100px;font-weight:bold;"><a href="member.php#!/content/mycontent.php?orderBy=OwnedByName&dir=asc" style="text-decoration:none;">Locked By</td>';
}
echo '<td colspan="5"></td>';
echo '</tr>';
echo $contentList;
echo '</table><br /><br />';


?>
<p>
<a href="panels/content/addcontent.php">Add new content</a>
</p>

