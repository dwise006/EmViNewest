<h1>Campaign View</h1>
<?php
//set_include_path("../");

/*Include sidebar */
//include("../../inc/sidebar.php");
//showSidebar("an_example");
include_once '../../pages/include/config.php';
include_once '../../pages/campaigns/campaignclass.php';
include_once '../../config/general.php';

echo "got delete request for the campaign: ". $_GET['ID'] ."<br>";

$remover = new Campaign();

	//need a campaignID and an empty array in order to fluch all of the reviewrs assigned to a campaign.
	$emptyUser = array('one'=> 0);
	$remover->remove_reviewers($_GET['ID'],$emptyUser);
	echo "Removing reviewers complete<br>";
	
	//Call function in campaignclass to remove all comments for said campaign.
  	$remover->remove_allCampaignComments($_GET['ID']);
  	echo "Removing comments complete<br>";

  	//Call function to unlock content
  	echo "still need this from keith<br>";
  	
  	//Call function to remove campaign by campaignID
	$remover->delete_campaign($_GET['ID']);
	echo "Removing campaing complete<br>";
	
	//redirect back to the page you came from
	if (isset($_GET['page'])){
		header('Location: '.$siteUrl.'member.php#!/url=workflow/mytasks.php');

	}else{
		header('Location: '.$siteUrl.'member.php#!/campaigns');
	}

?>