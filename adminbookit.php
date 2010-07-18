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
    $sql = "SELECT uid,lastname,firstname,email FROM login";
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
		
		$numdays = count($jsondates[$i]->datesavail);
		
		
		if ( $numdays > 0) {
			// put the room in the array
			$room["in"] = $jsondates[$i]->num;
			$room["name"] = $jsondates[$i]->room;

			// establish dates array
			$room["dates"] = array();
			
			// put dates into room["dates"]
			for ($j = 0, $k = 0; $j < $numdays; $j++) {
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

</head>

<body>

<div class="wrap<?=$d?>">

	<? headwrite($d) ?>
	
	<? navwrite($d) ?>
	
	<? progresswrite() ?>
	
	<div id="content">
		<h2>Please Confirm Your Reservation</h2>
		<form method="post" action="adminbookit2.php">
			<table>
				<tr>
					<td>
						<input type="hidden" name="changetransid" value="<?php echo $_POST["changetransid"] ?>" />
					</td>
				</tr>
			<?  if ($bigtime[0]["dates"][0] == 0) {
					echo "You did not select any dates<br /><br />";
					echo "Since you did not select alternate dates,<br />";
					echo "Pressing submit will delete your current reservation.<br /><br />";
				}
				else { ?>	
				<tr>
					<td>Guest:</td>
					<td>
						<select name="client">
							<option>new client...</option>
						<? while ($row = mysql_fetch_assoc($result)) { ?>	
							<option value="<?= $row["uid"] ?>"><? echo($row["lastname"] . ", " . $row["firstname"] . " - " . $row["email"]) ?></option>
						<? } ?>
						</select>
					</td>
				</tr>
				<tr>
					<td colspan="2">Number of guests in each room:
					<input type="number" name="numpeople" min="1" max="4" style="width: 40px;" />
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
			<?	} ?> <? } ?>
				<tr>
					<td></td>
					<td><input type="submit" value="Submit" /></td>
				</tr>
			</table>
		</form>
	</div>

	<? rightnavwrite($d) ?>

</div>

<? footwrite($d) ?>

</body>
</html>

