<?php
//include_once 'include/config.php';
class Campaign {
	//Database connect
	public function __construct() {
		$db = new DB_Class();
	}
	// Returns an array of file types: typeID and typeName
	public function get_campaign_status() {
		$arr = array();
		$result = mysql_query("SELECT * FROM tbl_wfStatus")
				or die("Could not connect: " . mysql_error());
		
		for ($i = 0; $i < mysql_num_fields($result); ++$i) {
 			$arr[0][$i] = mysql_field_name($result,$i);
 		}
 		
		$i = 1;
		while($row=mysql_fetch_array($result)){
			$j = 0;
			for ($j = 0; $j < mysql_num_fields($result); ++$j) {
				$arr[$i][mysql_field_name($result,$j)] = $row[mysql_field_name($result,$j)];
				}
			++$i;
		}
		return $arr;
 	}
 	
public function get_campaignStatusByID($id) {
	
	$result = mysql_query("SELECT wfStatusName, wfStatusID FROM tbl_wfStatus where wfStatusID = ".$id."")
		or die("Could not connect: " . mysql_error());
	$value = mysql_fetch_assoc($result);
		
			if ($value['wfStatusID'] == $id) {
				return $value['wfStatusName'];
		
	}
}	
public function get_statusIDByName($status) {
	
	//echo "fetching ID with status ".$status." <br>";
	
	$result = mysql_query("SELECT * FROM tbl_wfStatus where wfStatusName = '$status'")
	or die("get_CampaignIDByStatus failed: " . mysql_error());

	$value = mysql_fetch_assoc($result);
		
		return $value['wfStatusID'];
	
	
	}

 	// Returns an array of content
	public function get_campaign($sort,$dir)
 	{
 		// Returns a 3-D array with the following:
 		// ID, Name, Description, Keywords, TypeID, Format, CreatedDate, CreatedByID, CreatedByName, 
 		// UpdatedDate, UpdatedByID, UpdatedByName, OwnedByID, OwnedByName, FileName
 		
 		
 		$sql = "SELECT campaignID as 'ID', campaignName as 'Name', campaignDescription as 'Description', campaignKeywords as 'Keywords', campaignStatus as 'StatusID', t3.wfStatusName as 'Status', 
				CreatedDate as 'CreatedDate', launchDate as 'LaunchDate', createdBy as 'CreatedByID', concat(t2.userFirstName, ' ', t2.userLastName) as 'CreatedByName', canEdit
				FROM tbl_campaigns as t1
				LEFT JOIN tbl_user as t2 on t1.createdBy= t2.userID
				LEFT JOIN tbl_wfStatus as t3 on t1.campaignStatus = t3.wfStatusID ";
		
 		$sql .= "ORDER BY $sort $dir";		 	 

 		$arr = array();
 		$result = mysql_query($sql)
		 		or die("Could not connect: " . mysql_error());

 		for ($i = 0; $i < mysql_num_fields($result); ++$i) {
 			$arr[0][$i] = mysql_field_name($result,$i);
 		}
 		
		$i = 1;
		while($row=mysql_fetch_array($result)){
 			for ($j = 0; $j < mysql_num_fields($result); ++$j) {
	 				$arr[$i][mysql_field_name($result,$j)] = $row[mysql_field_name($result,$j)];
				}
			++$i;
		}
		return $arr;

 	}
 	
 	public function get_campaignByID($id)
 	{
 		
 		$sql = "SELECT * 
 				FROM tbl_campaigns
 				WHERE campaignID = $id";
 		$result = mysql_query($sql)
 			or die("Could not connect: " . mysql_error());
 		
 		$arr = array();
 		while($row=mysql_fetch_assoc($result)){
 			foreach ($row as $key=>$value) {
 				$arr[$key] = $value;
 				//echo $key ."=>".$value."<br>";
 			}
 		}
 		return $arr;
 	}
 	
 	
 	
 	public function add_campaign($uid,$name,$desc,$kw,$status,$ldate)
 	{

 		
 		$date = date();
 		$sql = "INSERT INTO `tbl_campaigns`
 		(campaignName, campaignDescription, campaignKeywords, campaignStatus, createdDate, createdBy, launchDate, canEdit, updatedBy, updatedDate) 
 		VALUES ('$name','$desc','$kw','$status',NOW(),'$uid','$ldate','$uid', '$uid',NOW())";
 		
 		$res = mysql_query($sql) 
			OR die(mysql_error());
		//echo $sql;
 	}
 	
 	public function delete_campaign($cid){
 		$remove = "DELETE FROM `tbl_campaigns` WHERE `campaignID` = ".$cid."";
 		$res = mysql_query($remove) 
			OR die(mysql_error());

 	}
 	
 	
 	public function edit_campaign($cid,$uid,$name,$desc,$kw,$status,$ldate)
 	{
		echo $cid."</br>";
		echo $uid."</br>";
		echo $name."</br>";
		echo $desc."</br>";
		echo $kw."</br>";
		echo $status."</br>";
		echo $ldate."</br>";
 		
 		$date = $date = date('Y-m-d H:i:s');
		
		if ($status == $this->get_statusIDByName('In Review')){
 			$sql = "UPDATE `tbl_campaigns` SET `campaignName`='".$name."', `campaignDescription`='".$desc."', `campaignKeywords`='".$kw."', `campaignStatus`='".$status."', `launchDate`='".$ldate."', `updatedBy`='".$uid."', `updatedDate`='".$date."', `canEdit`= '0' WHERE `campaignID`='".$cid."'";
 		}else{
 			$sql = "UPDATE `tbl_campaigns` SET `campaignName`='".$name."', `campaignDescription`='".$desc."', `campaignKeywords`='".$kw."', `campaignStatus`='".$status."', `launchDate`='".$ldate."', `updatedBy`='".$uid."', `updatedDate`='".$date."'  WHERE `campaignID`='".$cid."'";
		}	
 		
 		$res = mysql_query($sql) 
			OR die(mysql_error());
		//echo $sql;
 	}

 	public function add_reviewers($cid,$uid,$order)
 	{
 		
 		$check = mysql_query("select * from tbl_reviewers where campaignID = '".$cid."' and reviewerID = '".$uid."'");
 		if (mysql_num_rows($check) == 0){
		$sql = "INSERT INTO `tbl_reviewers`
 		(campaignID, reviewerID, reviewOrder, isComplete) 
 		VALUES ('$cid','$uid','$order',0)";
 		
 		$res = mysql_query($sql) 
			OR die(mysql_error());
			}
	
	}
	
	public function remove_reviewers($cid,$uid)
 	{
 		echo "removing reviewers: <br>";
 		$exists = false;
 		$check = $this->get_reviewers($cid);
 		while ($remove = mysql_fetch_assoc($check)){
 			for ($i = 0; $i <= sizeof($uid); $i++){
 				echo $remove['reviewerID'] . "<br>";
 				echo $uid[$i] . "<br><br>";

 				if ($remove['reviewerID'] == $uid[$i]){
					$exists = true;
				
				}
			}
			if ($exists == false){
				$iscomment = $this->get_reviewerComment($remove['reviewerID'], $cid);

				if ($iscomment != ""){
					$num = mysql_query("Select campaignID FROM tbl_commentArchive WHERE campaignID = ".$cid." and comment = '".$iscomment."'");
					if (!$num){
						$copyComment = "INSERT INTO `tbl_commentArchive` (`campaignID`, `reviewerID`,  `reviewResult`, `comment`, `commentDate`) SELECT `campaignID`, `reviewerID`, `reviewResult`, `reviewComments`, `CommentDate`  FROM `tbl_reviewers` WHERE `campaignID` = ".$cid." and reviewerID = '".$remove['reviewerID']."'";
							$copy = mysql_query($copyComment) 
								OR die(mysql_error());
					}
				}
			
				$sql = "DELETE FROM `tbl_reviewers` WHERE campaignID = '".$cid."' and reviewerID = '".$remove['reviewerID']."'";
	 		
	 			$res = mysql_query($sql) 
					OR die(mysql_error());
				
			}
			$exists = false;
		}
		
	}

	
	public function get_reviewers($cid)
 	{
		$sql = "SELECT `reviewerID`, `reviewResult`, `reviewOrder`, `userFirstName`, `userLastName`, `userEmailAddress`, `commentDate` FROM `tbl_reviewers` 
left join tbl_user on `userID` = `reviewerID` 
WHERE `campaignID` = ".$cid." order by `reviewOrder` asc";
 		
 		$res = mysql_query($sql) 
			OR die(mysql_error());
		return $res;
	
	}

 	public function get_reviewerComment($rid, $cid){
 	
 		$select = "SELECT `reviewComments` FROM `tbl_reviewers` WHERE `campaignID` = ".$cid." and `reviewerID` = ".$rid."";
 		$res = mysql_query($select) 
			OR die(mysql_error());
			
		$result = mysql_fetch_assoc($res);
		return $result['reviewComments'];

 	
 	}
 	
 	public function get_archiveComment($cid){
 	
 		$select = "SELECT * FROM `tbl_commentArchive` left join tbl_user on `userID` = `reviewerID` WHERE `campaignID` = ".$cid."";
 		$res = mysql_query($select) 
			OR die(mysql_error());
			
		
		return $res;

 	
 	}

 	
	//Will put a campaign in review status where not changes can be made to the comapaign or attached content until review is complete
 	public function set_inreviewStatus($cid, $status){
 		
 		 		
 		$sql = "UPDATE `tbl_campaigns` SET `campaignStatus`= ".$status." ,`canEdit`= 0 WHERE `campaignID` = ".$cid." ";
 		
 		$res = mysql_query($sql) 
			OR die(mysql_error());
		$setReviewers = "UPDATE `tbl_reviewers` SET `isComplete`= 0 WHERE `campaignID` = ".$cid." ";
 		
 		$incomplete = mysql_query($setReviewers ) 
			OR die(mysql_error());


 		
 	}
 	
 	public function set_recallreviewStatus($cid, $status){
 		
 		 		
 		$sql = "UPDATE `tbl_campaigns` SET `campaignStatus`= ".$status." ,`canEdit`= `updatedBy` WHERE `campaignID` = ".$cid." ";
 		
 		$res = mysql_query($sql) 
			OR die(mysql_error());

 		
 	}
 	
 	public function set_NewOwner($cid, $newuid){
 	
 		$sql = "UPDATE `tbl_campaigns` SET `updatedBy`= ".$newuid." ,`canEdit`= ".$newuid." WHERE `campaignID` = ".$cid." ";
 		
 		$res = mysql_query($sql) 
			OR die("you have an error " . mysql_error());
 	}
 	
 	public function attachContent($cid, $eid){
 	
 		//verify that this pair does not exist in the database already.
 		$checksql = "SELECT * From `tbl_emailToCampaigns` Where `campaignID` = '".$cid."' and `emailID` = '".$eid."'";
 		$check = mysql_query($checksql) 
			OR die("you have an error " . mysql_error());

 		
 		//Add emailID and campaignID to lookup table
 		if (mysql_num_rows($check) == 0){
	 		$sql = "INSERT INTO `tbl_emailToCampaigns`(`campaignID`, `emailID`) VALUES ('".$cid."', '".$eid."')";
	 		
	 		$res = mysql_query($sql) 
				OR die("you have an error " . mysql_error());
		}
 	}
	
	public function get_attachedEmail($cid){
		
		$sql = "Select * From `tbl_emailToCampaigns` WHERE `campaignID` = '".$cid."'";
 		
 		$res = mysql_query($sql) 
			OR die("you have an error " . mysql_error());
		return $res;
		
	}
 	
 	public function detach_emailFromCampaign($cid, $eid){
		
		$sql = "DELETE From `tbl_emailToCampaigns` WHERE `campaignID` = '".$cid."' and `emailID`= '".$eid."'";
 		
 		$res = mysql_query($sql) 
			OR die("you have an error " . mysql_error());
				
	}
   
   public function remove_allCampaignComments($cid){
   
   		$remove = "DELETE FROM `tbl_commentArchive` WHERE `campaignID` = ".$cid."";
 		$res = mysql_query($remove) 
			OR die(mysql_error());

   }


}
?>