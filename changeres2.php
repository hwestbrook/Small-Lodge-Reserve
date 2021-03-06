<?

    //page width a is narrow, b is wide
	$d = "a";
    
    // constants
    require_once("includes/common.php"); 

    // get the array from session
    $bigtime = $_SESSION["bigtime"];
    
    // get the number of people staying in the lodge
    $numpeople = mysql_real_escape_string($_POST["numpeople"]);
    $changetransid = mysql_real_escape_string($_POST["changetransid"]);	

	// get the uid for the transaction
	$sqluidget = "SELECT uid FROM rsrvtrans WHERE transactionid = '$changetransid' GROUP BY transactionid";
	$user = mysql_result(mysql_query($sqluidget),0,0);
    
    // delete the old data from database
	$sqldel = "DELETE FROM rsrvtrans WHERE transactionid='$changetransid'";
	mysql_query($sqldel);

	// put the data back into the database!
    for ($i = 0; $i < count($bigtime); $i++) {
    	for ($j = 0; $j < count($bigtime[$i]["dates"]); $j++) {
    		$room = $bigtime[$i]["in"];
    		$date = $bigtime[$i]["dates"][$j];
    		$transactionid = $changetransid;
    		
    		$sql =<<<EOD
				INSERT INTO rsrvtrans (room, date, numguests, uid, transactionid) 
    			VALUES ( $room, "$date", $numpeople, $user, "$transactionid")
EOD;
    		
    		mysql_query($sql);
    	}
    }
	

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<? realheadwrite() ?>

</head>

<body>

<div class="wrap<?=$d?>">

	<? headwrite($d) ?>
	
	<? navwrite($d) ?>
	
	<? progresswrite() ?>

	<div id="content">
		<h2>Your Reservation Has Been Entered Into the System</h2>
		Melissa Brown, our booking agent, will be in touch with you soon to confirm this change.
		<br />	<br />
		Your transaction id # is: <?=$transactionid?> 	<br />
		Please refer to it if you have any questions or concerns.
		<br />	<br />
		<h2><em>Thanks! Click <a href="usercenter.php">Here</a> to return to the user center.</em></h2>
		
	</div>

	<? rightnavwrite($d) ?>

</div>

<? footwrite($d) ?>

</body>
</html>

