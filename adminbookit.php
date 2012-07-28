<?php

    //page width a is narrow, b is wide
	$d = "a";
    
    // constants
    require_once("includes/common.php"); 
    
    // connect to database
    mysql_connect(DB_SERVER, DB_USER, DB_PASS);

    // select database
    mysql_select_db(DB_NAME);

    // need to lookup all the clients
    $sql = "SELECT uid,lastname,firstname,email FROM login ORDER BY lastname,firstname";
    $result = mysql_query($sql);
    
    // prepare an array for the lookup dates
    // stripslashes was not needed on my local machine, but I guess it is needed here...
    $jsondates = json_decode(stripslashes($_POST["jsondates"]));
	//print_r($_POST["jsondates"]);
	//var_dump($jsondates);
	
	$numrooms = count($jsondates);
	
	// prepare an array for the for the whole shebang
	$bigtime = array();
	
	for ($i = 0; $i < $numrooms; $i++) {
		//prepare an array for the room
		$room = array();
		
		$numdays = xcount($jsondates[$i]->datesavail);
		$numvalues = count($jsondates[$i]->datesavail);
		
		
		if ( $numdays > 0) {
			// put the room in the array
			$room["in"] = $jsondates[$i]->num;
			$room["name"] = $jsondates[$i]->room;

			// establish dates array
			$room["dates"] = array();
			
			// put dates into room["dates"]
			for ($j = 0, $k = 0; $j < $numvalues; $j++) {
				// check if there is a date here
				if ($jsondates[$i]->datesavail[$j] != null) {
					// put that date into the field
					$room["dates"][$k] = $jsondates[$i]->datesavail[$j];
					$k++;
				}
			}
			// put the room array into our bigger array of rooms
			$bigtime[] = $room;
		}
		else poop;		
	}
	
	// put the array into the session
	$_SESSION["bigtime"] = $bigtime;
	

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<? realheadwrite() ?>

<script type="text/javascript" charset="utf-8">
	function addClient (clnum) {
		if (clnum == 0)
			document.getElementById('clientinfo').style.display = "";
		else
			document.getElementById('clientinfo').style.display = "none";
	}
</script>

</head>

<body>

<div class="wrap<?=$d?>">

	<? headwrite($d) ?>
	
	<? navwrite($d) ?>
	
	<? progresswrite() ?>
	
	<div id="content" style="background-color: yellow;">
		<h2>Please Confirm the Reservation</h2>
		<form method="post" action="adminbookit2.php" id="bookitform">
			<table id=bookinfo>
				<tr>
					<td>
						<input type="hidden" name="changetransid" value="<?php echo $_POST["changetransid"] ?>" />
					</td>
				</tr>
			<?  if ($bigtime[0]["dates"][0] == 0) {
					echo <<<EOT
				<span id=nobookinfo>You did not select any dates<br /><br />
					Please go <a href="javascript:history.go(-1)">Back</a>  to the previous screen and select new dates. <br /><br />
					Thanks!
				</span>
EOT;
				}
				else { ?>	
				<tr>
					<td>Guest:</td>
					<td>
						<select id="client" name="client" onFocus="addClient(client.selectedIndex)" onChange="addClient(client.selectedIndex)">
							<option value="0">new client...</option>
						<? while ($row = mysql_fetch_assoc($result)) { ?>	
							<option value="<?= $row["uid"] ?>"><? echo($row["lastname"] . ", " . $row["firstname"] . " - " . $row["email"]) ?></option>
						<? } ?>
						</select>
					</td>
					<td colspan="2" rowspan="8">
						<div id=clientinfo style="display:'';">
							<b>QUICK ADD</b>
							<table style="float:right">
								<tr><td><input type="text" name="firstname" value="firstname"></td></tr>
								<tr><td><input type="text" name="lastname" value="lastname"></td></tr>
								<tr><td><input type="text" name="phone" value="phone"></td></tr>
								<tr><td><input type="text" name="email" value="email"></td></tr>
								<tr><td><input type="text" name="address1" value="address line 1"></td></tr>
								<tr><td><input type="text" name="address2" value="address line 2"></td></tr>
								<tr><td><input type="text" name="zipcode" value="zip code"></td></tr>
							</table>
						</div>
					</td>
				</tr>
				<tr>
					<td colspan="2">Number of guests in each room:
					<input type="number" id="guestnum" name="numpeople" min="1" max="4" style="width: 40px;" />
					</td>
				</tr>
				<tr>
					<td colspan="2">Preferred contact method:
						<select id="prefcontact" name="prefcontact">
						  <option value="phone">Phone</option>
						  <option value="email">Email</option>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2">Handicap Accessible:
					<input type="checkbox" name="handicap" value=1 style="width: 40px;" />
					</td>
				</tr>
				<tr><td><br /></td></tr>
			
				<? for ($b = 0; $b < count($bigtime); $b++) { ?>
				<tr>
					<td>Room: </td>
					<td><? echo $bigtime[$b]["name"] ?></td>
				</tr>
				<tr>
					<td>Check-in: </td>
					<td><? echo date('l, F j, Y', strtotime($bigtime[$b]["dates"][0])) ?></td>
				</tr>
				<tr>
					<td>Check-out: </td>
					<td><? echo date('l, F j, Y', strtotime( '+1 day', strtotime(end($bigtime[$b]["dates"])))) ?></td>
				</tr>
				<tr><td><br /></td></tr>
			<?	} ?> 
				<tr>
					<td colspan="4">
						<textarea rows="5" cols="60" wrap="physical" name="activities">Activities</textarea>
					</td>
				</tr>
				<tr><td><br /></td></tr>
				<tr>
					<td colspan="4">
						<textarea rows="5" cols="60" wrap="physical" name="comments">Comments</textarea>
					</td>
				</tr>
				<tr><td><br /></td></tr>
				<tr>
					<td></td>
					<td><input type="submit" value="Submit" /></td>
				</tr>
			</table>
			
			
			<? } ?>
		</form>
	</div>

	<? adminnavwrite($d) ?>

</div>

<? footwrite($d) ?>

</body>
</html>

