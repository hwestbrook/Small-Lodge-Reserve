<?

    //page width a is narrow, b is wide
	$d = "a";
    
    // constants
    require_once("includes/common.php"); 
    
    // get user id for looking up Portfolio
    $user = $_SESSION["uid"];

    // get the array from session
    $bigtime = $_SESSION["bigtime"];
    
    // get the number of people staying in the lodge
    $numpeople = mysql_real_escape_string($_POST["numpeople"]);
	$handicap = mysql_real_escape_string($_POST["handicap"]);
	$activities = mysql_real_escape_string($_POST["activities"]);
	$comments = mysql_real_escape_string($_POST["comments"]);
	$prefcontact = mysql_real_escape_string($_POST["prefcontact"]);
    $changetransid = mysql_real_escape_string($_POST["changetransid"]);	
    $roomseed = $bigtime[0]["in"];
	
	// make sure handicap is not equal to NULL
	if ($handicap != 1) {$handicap = 0;}

	// make unique identifier
	$converted = transactionid($user, $roomseed);

    // put the data in the database!
    for ($i = 0; $i < count($bigtime); $i++) {
    	for ($j = 0; $j < count($bigtime[$i]["dates"]); $j++) {
    		$room = $bigtime[$i]["in"];
    		$date = $bigtime[$i]["dates"][$j];
    		
    		
    		$sql =<<<EOD
				INSERT INTO rsrvtrans (room, date, numguests, handicap, activities, comments, uid, transactionid, prefcontact) 
    			VALUES ( $room, "$date", $numpeople, $handicap, "$activities", "$comments", $user, "$converted", "$prefcontact")
EOD;
    		
    		$err_chk = mysql_query($sql);
    	}
    }
	
	// email that shit out!
	
	// multiple recipients
	$to  = 'melissa@hilofishing.com' . ', '; // note the comma
	$to .= 'heath@hilofishing.com';
	
	$subject = 'New Reservation!';
	
	// The message
	$message = "A new reservation has been entered in the system.\nThe transaction ID is: " . $converted . "\nPlease check the website to follow up the reservation.";

	// In case any of our lines are larger than 70 characters, we should use wordwrap()
	$message = wordwrap($message, 70);
	
	$headers = 'From: heath@hilofishing.com' . "\r\n" .
	    'Reply-To: heath@hilofishing.com' . "\r\n" .
	    'X-Mailer: PHP/' . phpversion();

	// if(mail($to, $subject, $message, $headers)) {echo "yipee";}
	// 	else {echo "nopee";}

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
		<?  if(!$err_chk) { 
			echo "Error, please try again";
		} 	
			else { ?>
			<h2>Your Reservation Has Been Entered Into the System</h2>
			Melissa Brown, our booking agent, will be calling you soon for a deposit.
			<br />	<br />
			Your transaction id # is: <?=$converted?> 	<br />
			Please refer to it if you have any questions or concerns.
			<br />	<br />
			<h2><em>Thanks! Click <a href="usercenter.php">Here</a> to return to the user center.</em></h2>
		<? } ?>
	</div>

	<? rightnavwrite($d) ?>

</div>

<? footwrite($d) ?>

</body>
</html>

