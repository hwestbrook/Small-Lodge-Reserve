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
		//store original val of client id for later use
		$origclientid = $clientid;
	
	// clean the input of the a new guest
	$email = mysql_real_escape_string($_POST["email"]);
    $firstname = mysql_real_escape_string($_POST["firstname"]);
    $lastname = mysql_real_escape_string($_POST["lastname"]);
    $phoneunclean = mysql_real_escape_string($_POST["phone"]);
    $address1 = mysql_real_escape_string($_POST["address1"]);
    $address2 = mysql_real_escape_string($_POST["address2"]);
    $zipcode = mysql_real_escape_string($_POST["zipcode"]);
	$password = md5("kingsalmon");

	// validate email address
	if ($email != "email" && validEmail($email) != true) {
		apologize("Invalid email address");
	}
	
	// clean phone
	$clean = phoneClean($phoneunclean);
	$phone = $clean[0];
	
	// validate zip code
	if ($zipcode != "zip code" && validateUSAZip($zipcode) != true) {
		apologize("Invalid zip code");
	}

	// if client id is 0, meaning new client, we need to add them first, then get their
	// uid, then put the reservation in the database
	if ($clientid == 0) {
		// insert data into mysql table
        mysql_query("INSERT INTO login (firstname, lastname, phonenumber, password, email, addressline1, addressline2, zipcode) VALUES('$firstname', '$lastname', '$phone', '$password', '$email', '$address1', '$address2', $zipcode)");

		// get the uid from the table and stuff it into clientid variable for database
		$sql = "SELECT uid,admin FROM login WHERE email='$email' AND password='$password'";
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		$clientid = $row["uid"];
	}

    // how long is $bigtime?
	$biglength = count($bigtime);
	$bigdatelength = count($bigtime[0]["dates"]);

	// make the unique trans id
	$transactionid = $clientid . date('dny', strtotime($bigtime[0]["dates"][0])) . date('d', strtotime($bigtime[0]["dates"][$bigdatelength - 1])) . $bigtime[0]["in"];
    		
    $codeset = "0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$base = strlen($codeset);
	$n = $transactionid;
	$converted = "";
	
	while ($n > 0) {
	  $converted = substr($codeset, ($n % $base), 1) . $converted;
	  $n = floor($n/$base);
	}
	

	// put the data in the database!
    for ($i = 0; $i < count($bigtime); $i++) {
    	for ($j = 0; $j < count($bigtime[$i]["dates"]); $j++) {
    		$room = $bigtime[$i]["in"];
    		$date = $bigtime[$i]["dates"][$j];
    		
    		
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
		<h2><em>Nice! Click <a href="admincenter.php">Here</a> to return to the Admin center.</em></h2>
		
		<?
		if ($origclientid == 0) {
			echo <<<EOT
		<h2>You have also registered a new client. The clients information is below.</h2>
		<table>	
			<tr>
				<td>Name:</td>
				<td>$firstname $lastname</td>
			</tr>
			<tr>
				<td>Phone:</td>
				<td>$phone</td>
			</tr>
			<tr>
				<td>Email:</td>
				<td>$email</td>
			</tr>
			<tr>
				<td>Address 1:</td>
				<td>$address1</td>
			</tr>
			<tr>
				<td>Address 2:</td>
				<td>$address2</td>
			</tr>
			<tr>
				<td>Zip:</td>
				<td>$zipcode</td>
			</tr>
			<tr>
				<td>Password:</td>
				<td>kingsalmon</td>
			</tr>
		</table>
EOT;
		}
		?>
		
	</div>

	<? rightnavwrite($d) ?>

</div>

<? footwrite($d) ?>

</body>
</html>

