<!DOCTYPE HTML>
<!DOCTYPE html PUBLIC "" ""><HTML><HEAD>
<META http-equiv="Content-Type" content="text/html; charset=utf-8"></HEAD>
<BODY>
<PRE>&lt;style type="text/css"&gt;
.tableheaders {
	width:140px;
	font-weight: normal;
	font-size: small;
	color: #FAF6F6;
	border-bottom-style: solid;
	border-bottom-width: 1px;
	background-color: #000080;
	text-align: center;

}
.tablebody {
	width: 110px;
	font-weight: normal;
	font-size: small;
	color: #000000;
	border-bottom-style: solid;
	border-bottom-width: 1px;
	background-color: #C0C0C0;
	text-align: center;

}


&lt;/style&gt;


&lt;?php


class Search
{
	
	
	/*this constructor profides a way create an instance of the currently logged on user*/
	function _construct() {
    	
		
	}
	
	/*withID($id) provides a way to create an instance of another user of the system if you have 
	the userID*/
	public function find($type, $field, $word ) {
    	include("../../config/DB_Connect.php");
		

		$query = mysql_query("SELECT * FROM ".$type." WHERE ".$field." = '".$word."'");
		if (!$query) {    
				die("Query to show fields from table failed userclass.php Line 58");
		}
		//$found = mysql_fetch_assoc($query);
		
		if (mysql_num_rows($query) &lt; 1){
			echo "There is no content in the system that matches your search.";
			echo "&lt;/br&gt;&lt;/br&gt;";
			echo "You searched ".$field." for ".$word;
			
		}else{
		$this-&gt;printTable($query);
		}
    	
    }
	
	
    public function printTable($f,$type,$field,$word) {
    
    
    
    	/*echo "&lt;table&gt;";
    		while ($found = mysql_fetch_assoc($f)){
    		echo "&lt;tr&gt;";
    			echo "&lt;td&gt;".$found['contentID']."&lt;/td&gt;";
    			echo "&lt;td&gt;".$found['contentName']."&lt;/td&gt;";
				echo "&lt;td&gt;".$found['contentDescription']."&lt;/td&gt;";
				echo "&lt;td&gt;".$found['contentType']."&lt;/td&gt;";
				echo "&lt;td&gt;".$found['createdDate']."&lt;/td&gt;";
				echo "&lt;td&gt;".$found['updatedDate']."&lt;/td&gt;";
    		echo "&lt;/tr&gt;";
    		}
    	echo "&lt;/table&gt;";
*/    	
    	session_start();
$uid = $_SESSION['ID'];

    	
  




// Function returns the following beginning in row 1:
// ID, Name, Description, Keywords, TypeID, Format, CreatedDate, CreatedByID, CreatedByName, 
// UpdatedDate, UpdatedByID, UpdatedByName, OwnedByID, OwnedByName, FileName

$contentList = '';
for ($i = 1; $i &lt; count($f); ++$i) {
		$contentList .= '&lt;tr&gt;&lt;td class="tablebody"&gt;'. htmlentities($f[$i]['Name']) . '&lt;/td&gt;
		&lt;td class="tablebody"&gt;' . htmlentities($f[$i]['Format']) . '&lt;/td&gt;
		&lt;td class="tablebody"&gt;' . htmlentities($f[$i]['Description']) . '&lt;/td&gt;
		&lt;td class="tablebody"&gt;' . htmlentities($f[$i]['Keywords']) . '&lt;/td&gt;
		&lt;td class="tablebody"&gt;' . date("m/d/Y g:i a", strtotime($f[$i]['UpdatedDate'])) . '&lt;/td&gt;
		&lt;td class="tablebody"&gt;' . htmlentities($f[$i]['OwnedByName']) . '&lt;/td&gt;
		&lt;td class="tablebody"&gt;';
	if ($f[$i]['FileName'] != '') {
		$contentList .= '&lt;a href="content/upload/'. $f[$i]['FileName'] .'" target="_blank"&gt;View&lt;/a&gt;';
	}
	$contentList .=	'&lt;/td&gt;&lt;td class="tablebody"&gt;';
	if ($f[$i]['OwnedByID'] == $uid) {
		if ($type == 'tbl_email'){
			$contentList .= '&lt;a href="panels/email/editemail.php?ID='.$f[$i]['ID'].'"&gt;Edit&lt;/a&gt;';
		}else if($type == 'tbl_content'){
			$contentList .= '&lt;a href="panels/content/editcontent.php?ID='.$f[$i]['ID'].'"&gt;Edit&lt;/a&gt;';
		}else if($type == 'tbl_campaign'){
			$contentList .= '&lt;a href="panels/campaigns/editcampaign.php?ID='.$f[$i]['ID'].'"&gt;Edit&lt;/a&gt;';
		}
		
	}
	$contentList .= '&lt;/td&gt;	
		&lt;td class="tablebody"&gt;Clone&lt;/td&gt;
		&lt;td class="tablebody"&gt;';
	if ($f[$i]['OwnedByID'] == $uid) {
		$contentList .= 'Delete';
	}	
	$contentList .= '&lt;/td&gt;
		&lt;/tr&gt;';
}



    	//DISPLAY THE CONFERENCE REGISTRANTS
echo "&lt;table&gt;";
	echo "&lt;thead class=\"tableheaders\"&gt;";
echo '&lt;tr&gt;';
if (strtolower($orderby) == 'name' &amp;&amp; strtolower($dir) == 'asc') {
	echo '&lt;th class="tableheaders"&gt;&lt;a href="member.php#!/search/searchcontent.php?Type='.$type.'&amp;Field='.$field.'&amp;Value='.$word.'&amp;orderBy=Name&amp;dir=desc" style="text-decoration:none; color:white"&gt;Content Name&lt;/td&gt;';
} else {
	echo '&lt;th class="tableheaders"&gt;&lt;a href="member.php#!/search/searchcontent.php?Type='.$type.'&amp;Field='.$field.'&amp;Value='.$word.'&amp;orderBy=Name&amp;dir=asc" style="text-decoration:none; color:white"&gt;Content Name&lt;/td&gt;';
}
if (strtolower($orderby) == 'format' &amp;&amp; strtolower($dir) == 'asc') {
	echo '&lt;th class="tableheaders"&gt;&lt;a href="member.php#!/search/searchcontent.php?Type='.$type.'&amp;Field='.$field.'&amp;Value='.$word.'&amp;orderBy=Format&amp;dir=desc" style="text-decoration:none; color:white"&gt;Format&lt;/td&gt;';
} else {
	echo '&lt;th class="tableheaders"&gt;&lt;a href="member.php#!/search/searchcontent.php?Type='.$type.'&amp;Field='.$field.'&amp;Value='.$word.'&amp;orderBy=Format&amp;dir=asc" style="text-decoration:none; color:white"&gt;Format&lt;/td&gt;';
}
if (strtolower($orderby) == 'description' &amp;&amp; strtolower($dir) == 'asc') {
	echo '&lt;th class="tableheaders"&gt;&lt;a href="member.php#!/search/searchcontent.php?Type='.$type.'&amp;Field='.$field.'&amp;Value='.$word.'&amp;orderBy=Description&amp;dir=desc" style="text-decoration:none; color:white"&gt;Description&lt;/td&gt;';
} else {
	echo '&lt;th class="tableheaders"&gt;&lt;a href="member.php#!/search/searchcontent.php?Type='.$type.'&amp;Field='.$field.'&amp;Value='.$word.'&amp;orderBy=Description&amp;dir=asc" style="text-decoration:none; color:white"&gt;Description&lt;/td&gt;';
}
if (strtolower($orderby) == 'keywords' &amp;&amp; strtolower($dir) == 'asc') {
	echo '&lt;th class="tableheaders"&gt;&lt;a href="member.php#!/search/searchcontent.php?Type='.$type.'&amp;Field='.$field.'&amp;Value='.$word.'&amp;orderBy=Keywords&amp;dir=desc" style="text-decoration:none; color:white"&gt;Keywords&lt;/td&gt;';
} else {
	echo '&lt;th class="tableheaders"&gt;&lt;a href="member.php#!/search/searchcontent.php?Type='.$type.'&amp;Field='.$field.'&amp;Value='.$word.'&amp;orderBy=Keywords&amp;dir=asc" style="text-decoration:none; color:white"&gt;Keywords&lt;/td&gt;';
}
if (strtolower($orderby) == 'updatedate' &amp;&amp; strtolower($dir) == 'asc') {
	echo '&lt;td style="min-width:100px;font-weight:bold;"&gt;&lt;a href="member.php#!/search/searchcontent.php?Type='.$type.'&amp;Field='.$field.'&amp;Value='.$word.'&amp;orderBy=UpdatedDate&amp;dir=desc" style="text-decoration:none; color:white"&gt;Last Updated&lt;/td&gt;';
} else {
	echo '&lt;th class="tableheaders"&gt;&lt;a href="member.php#!/search/searchcontent.php?Type='.$type.'&amp;Field='.$field.'&amp;Value='.$word.'&amp;orderBy=UpdatedDate&amp;dir=asc" style="text-decoration:none; color:white"&gt;Last Updated&lt;/td&gt;';
}
if (strtolower($orderby) == 'ownedby' &amp;&amp; strtolower($dir) == 'asc') {
	echo '&lt;th class="tableheaders"&gt;&lt;a href="member.php#!/search/searchcontent.php?Type='.$type.'&amp;Field='.$field.'&amp;Value='.$word.'&amp;orderBy=OwnedByName&amp;dir=desc" style="text-decoration:none; color:white"&gt;Locked By&lt;/td&gt;';
} else {
	echo '&lt;th class="tableheaders"&gt;&lt;a href="member.php#!/search/searchcontent.php?Type='.$type.'&amp;Field='.$field.'&amp;Value='.$word.'&amp;orderBy=OwnedByName&amp;dir=asc" style="text-decoration:none; color:white"&gt;Locked By&lt;/td&gt;';
}
echo '&lt;th class="tableheaders"&gt;&lt;/td&gt;';
echo '&lt;td style="min-width:50px;font-weight:bold"&gt;&lt;/td&gt;';
echo '&lt;td style="min-width:50px;font-weight:bold"&gt;&lt;/td&gt;';
echo '&lt;td style="min-width:50px;font-weight:bold"&gt;&lt;/td&gt;';
echo '&lt;/tr&gt;';
echo '&lt;/thead&gt;';
echo $contentList;
echo '&lt;/table&gt;';

    	
    	
    
    
    }
	public function get_content($type,$field,$word,$sort,$dir)
 	{
 	include("../../config/DB_Connect.php");
 	
 	if($sort == NULL) {
	$orderby = 'ID';
	$dir = 'ASC';
}
else {
	$orderby = $sort;
	$dir = $dir;
}


 		// Returns a 3-D array with the following:
 		// ID, Name, Description, Keywords, TypeID, Format, CreatedDate, CreatedByID, CreatedByName, 
 		// UpdatedDate, UpdatedByID, UpdatedByName, OwnedByID, OwnedByName, FileName
 		
 		if ($type == tbl_content){
	 		$sql = "SELECT contentID as 'ID', contentName as 'Name', contentDescription as 'Description', contentKeywords as 'Keywords', contentType as 'TypeID', t5.typeFormat as 'Format', createdDate as 'CreatedDate', createdBy as 'CreatedByID', concat(t2.userFirstName, ' ', t2.userLastName) as 'CreatedByName', updatedDate as 'UpdatedDate', updatedBy as 'UpdatedByID', concat(t3.userFirstName, ' ', t3.userLastName) as 'UpdatedByName', fileLocation as 'FileName', canEdit as 'OwnedByID', concat(t4.userFirstName, ' ', t4.userLastName) as 'OwnedByName',
fileLocation as 'FileName' FROM tbl_content as t1 LEFT JOIN tbl_user as t2 on t1.createdBy = t2.userID LEFT JOIN tbl_user as t3 on t1.updatedBy = t3.userID LEFT JOIN tbl_user as t4 on t1.canEdit = t4.userID LEFT JOIN tbl_contentTypes as t5 on t1.contentType = t5.typeFormat";
			
	 		//$sql .= "";
	 		
	 		$sql .= " ORDER BY $orderby $dir";
	 		
 		}	 	 
		//echo $sql;
 		$arr = array();
 		$result = mysql_query($sql)
		 		or die("Could not connect: " . mysql_error());

 		for ($i = 0; $i &lt; mysql_num_fields($result); ++$i) {
 			$arr[0][$i] = mysql_field_name($result,$i);
 		}
 		
		$i = 1;
		while($row=mysql_fetch_array($result)){
			if (strpos(strtolower($row[$field]),strtolower($word)) !== false) {
			

 			for ($j = 0; $j &lt; mysql_num_fields($result); ++$j) {
 					
	 				$arr[$i][mysql_field_name($result,$j)] = $row[mysql_field_name($result,$j)];
					}
				++$i;
				}
			
		}
		//print_r($arr);
		echo "You searched for ".$field." with the value of ".$word.".";
		$this-&gt;printTable($arr,$type,$field,$word);
		

		return $arr;

 	}
 	
 	

public function get_email($type,$field,$word,$sort,$dir)
 	{
 	
 	include("../../config/DB_Connect.php");
 	
 	if($sort == NULL) {
		$orderby = 'ID';
		$dir = 'ASC';
	}else {
		$orderby = $sort;
		$dir = $dir;
	}
 		// Returns a 3-D array with the following:
 		// ID, Name, Description, Keywords, HTMLContentID, TextContentID, Subject, FromName, FromAddress,
 		// CreatedDate, CreatedByID, CreatedByName, UpdatedDate, UpdatedByID, UpdatedByName, OwnedByID, OwnedByName
 		
 		$sql = "SELECT emailID as 'ID', emailName as 'Name', emailDescription as 'Description', emailKeywords as 'Keywords', 
 				emailHTML as 'HTMLContentID', emailText as 'TextContentID', emailSubject as 'Subject', emailFromName as 'FromName', 
 				emailFromAddress as 'FromAddress', createdDate as 'CreatedDate', createdBy as 'CreatedByID', 
 				concat(t2.userFirstName, ' ', t2.userLastName) as 'CreatedByName', updatedDate as 'UpdatedDate', 
				updatedBy as 'UpdatedByID', concat(t3.userFirstName, ' ', t3.userLastName) as 'UpdatedByName', 
 				canEdit as 'OwnedByID', concat(t4.userFirstName, ' ', t4.userLastName) as 'OwnedByName' 
				FROM tbl_email as t1
				LEFT JOIN tbl_user as t2 on t1.createdBy = t2.userID
				LEFT JOIN tbl_user as t3 on t1.updatedBy = t3.userID
				LEFT JOIN tbl_user as t4 on t1.canEdit = t4.userID ";
		
 		$sql .= " ORDER BY $orderby $dir";		 	 

 		$arr = array();
 		$result = mysql_query($sql)
		 		or die("Could not connect: " . mysql_error());

 		for ($i = 0; $i &lt; mysql_num_fields($result); ++$i) {
 			$arr[0][$i] = mysql_field_name($result,$i);
 		}
 		
		$i = 1;
		while($row=mysql_fetch_array($result)){
			if (strpos(strtolower($row[$field]),strtolower($word)) !== false) {
			

 			for ($j = 0; $j &lt; mysql_num_fields($result); ++$j) {
 					
	 				$arr[$i][mysql_field_name($result,$j)] = $row[mysql_field_name($result,$j)];
					}
				++$i;
				}
			
		}
		
		echo "You searched for ".$field." with the value of ".$word.".";
		$this-&gt;printTable($arr,$type,$field,$word);
		

		return $arr;

 	}
	
 	
 	public function get_campaign($type,$field,$word,$sort,$dir)
 	{
 		// Returns a 3-D array with the following:
 		// ID, Name, Description, Keywords, TypeID, Format, CreatedDate, CreatedByID, CreatedByName, 
 		// UpdatedDate, UpdatedByID, UpdatedByName, OwnedByID, OwnedByName, FileName
 		include("../../config/DB_Connect.php");
 	
 	if($sort == NULL) {
		$orderby = 'ID';
		$dir = 'ASC';
	}else {
		$orderby = $sort;
		$dir = $dir;
	}

 		
 		$sql = "SELECT campaignID as 'ID', campaignName as 'Name', campaignDescription as 'Description', campaignKeywords as 'Keywords', campaignStatus as 'StatusID', t3.wfStatusName as 'Status', 
				CreatedDate as 'CreatedDate', launchDate as 'LaunchDate', createdBy as 'CreatedByID', concat(t2.userFirstName, ' ', t2.userLastName) as 'CreatedByName', canEdit
				FROM tbl_campaigns as t1
				LEFT JOIN tbl_user as t2 on t1.createdBy= t2.userID
				LEFT JOIN tbl_wfStatus as t3 on t1.campaignStatus = t3.wfStatusID ";
		
 		$sql .= "ORDER BY $orderby $dir";		 	 

 		$arr = array();
 		$result = mysql_query($sql)
		 		or die("Could not connect: " . mysql_error());

 		for ($i = 0; $i &lt; mysql_num_fields($result); ++$i) {
 			$arr[0][$i] = mysql_field_name($result,$i);
 		}
 		
		$i = 1;
		while($row=mysql_fetch_array($result)){
			if (strpos(strtolower($row[$field]),strtolower($word)) !== false) {
			

 			for ($j = 0; $j &lt; mysql_num_fields($result); ++$j) {
 					
	 				$arr[$i][mysql_field_name($result,$j)] = $row[mysql_field_name($result,$j)];
					}
				++$i;
				}
			
		}
		
		echo "You searched for ".$field." with the value of ".$word.".";
		$this-&gt;printTable($arr,$type,$field,$word);
		

		return $arr;

 	}



	
	


}

?&gt;
</PRE><IFRAME 
style="width: 0px; height: 0px; visibility: hidden;"></IFRAME></BODY></HTML>
