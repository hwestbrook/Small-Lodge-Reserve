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
    $clientid = mysql_real_escape_string($_POST["client"]);
    
    /* Since this is adminbookit2, we wont be deleting reservations with this...
    // if this is a change in reservations, need to delete all old first
    if ($changetransid != "") {
    	$sqlchange = "DELETE FROM rsrvtrans WHERE uid=$user AND transactionid='$changetransid'";
    	mysql_query($sqlchange);
    }   
    */
    
    // put the data in the database!
    for ($i = 0; $i < count($bigtime); $i++) {
    	for ($j = 0; $j < count($bigtime[$i]["dates"]); $j++) {
    		$room = $bigtime[$i]["in"];
    		$date = $bigtime[$i]["dates"][$j];
    		$transactionid = date('siHymd') . sprintf("%d", $user);
    		
    		$codeset = "0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ";
			$base = strlen($codeset);
			$n = $transactionid;
			$converted = "";
			
			while ($n > 0) {
			  $converted = substr($codeset, ($n % $base), 1) . $converted;
			  $n = floor($n/$base);
			}
    		
    		$sql =<<<EOD
				INSERT INTO rsrvtrans (room, date, numguests, uid, transactionid) 
    			VALUES ( $room, "$date", $numpeople, $clientid, "$converted")
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
		<h2>The Reservation Has Been Entered Into the System</h2>
		Be sure to double check this reservation, and ask if the client would like something mailed
		<br />	<br />
		The transaction id # is: <?=$converted?> 	<br />
		An email was sent to the client
		<br />	<br />
		<h2><em>Nice! Click <a href="admincenter">Here</a> to return to the Admin center.</em></h2>
		
	</div>

	<? rightnavwrite($d) ?>

</div>

<? footwrite($d) ?>

</body>
</html>

