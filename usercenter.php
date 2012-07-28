<?php
	//page width a is narrow, b is wide
	$d = "a";
	
	// require common code
    require_once("includes/common.php"); 
    
    // get user id for looking up Portfolio
    $user = $_SESSION["uid"];
   
	// check for Reservations
	$sqlcheck = "SELECT transactionid,timestamp,roomname,MAX(date),MIN(date),numguests,invoicenum FROM rsrvtrans,rooms WHERE uid=$user AND rsrvtrans.room=rooms.room GROUP BY transactionid"; 
	$curres = mysql_query($sqlcheck);
	
	//set the auto values
	$earlydate = date('Y-m-d', strtotime('Today'));
	$latedate = date('Y-m-d', strtotime('+30 Days'));

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<? realheadwrite() ?>

<style type="text/css" media="screen">
	#changeresclose {
		position: relative;
		top: -40px;
		left: 500px;
		background-color: #A5B4C5;
		padding: 5px;
		border: 1px solid black;
	}
	#lookupclose {
		position: relative;
		top: -40px;
		left: 500px;
		background-color: #A5B4C5;
		padding: 5px;
		border: 1px solid black;
	}
</style>

<!-- sliding tables! -->
<script type="text/javascript">
	$(document).ready(function() {
	    $(".changeres").click(function () {
	      $("#changereserve").show("blind", {}, 1000);
	    });
	  });
	$(document).ready(function() {
	    $("#changeresclose").click(function () {
	      $("#changereserve").hide("blind", {}, 1000);
	    });
	  });

	$(document).ready(function() {
	    $("#lookupsubmit").click(function () {
	      $("#results").show("blind", {}, 1000);
	    });
	  });
	$(document).ready(function() {
	    $("#lookupclose").click(function () {
	      $("#results").hide("blind", {}, 1000);
	    });
	  });
	

</script>


</head>

<body>

<div class="wrap<?=$d?>">

	<? headwrite($d) ?>
	
	<? navwrite($d) ?>
	
	<? progresswrite() ?>

	<div id="content">
		<h2><em>My Reservations</em></h2>
		<? if (mysql_fetch_assoc($curres) == null) { 
				echo "There are currently no reservations for this account.";
				}   		
		   else { mysql_data_seek($curres,0); ?>
			<table id="curres">
				<thead>
					<tr id="curreshead">
						<td><? echo " Reservation ID " ?></td>
						<td><? echo " Approval Number " ?></td>
						<td><? echo " Room " ?></td>
						<td><? echo " Check-In " ?></td>
						<td><? echo " Check-Out " ?></td>
						<td><? echo " Guests " ?></td>
					</tr>
				</thead>
				<tbody class="currestbl">
				<? while ($row = mysql_fetch_assoc($curres)) { ?>
					<tr class="curresrow">
						<td><a class="changeres" onclick="changeres( '<? 
							echo date('Y-m-d', strtotime('-3 day', strtotime($row["MIN(date)"]))) ?>', '<? 
							echo date('Y-m-d', strtotime('+4 days', strtotime($row["MAX(date)"]))) ?>', '<? 
							echo $row["transactionid"] ?>');"><? 
							echo $row["transactionid"] ?></a>
						</td>
						<td><? if ($row["invoicenum"] == 0) {
									echo "Waiting Approval";
								}
							   else {
									echo $row["invoicenum"];
							   }
						?></td>
						<td><? echo $row["roomname"] ?></td>
						<td><? echo date('M j, Y', strtotime($row["MIN(date)"])) ?></td>
						<td><? echo date('M j, Y', strtotime( '+1 day', strtotime($row["MAX(date)"]))) ?></td>
						<td><? echo $row["numguests"] ?></td>
					</tr>
				<? } ?>
				</tbody>
			</table>
			<small>* Click the link in the Reservation ID column to modify that reservation</small>
		<? } ?>
		
	</div>
	
	<div id="changereserve" style="display: none;">
		<h3><em>Current Reservation and Available Dates:</em></h3>
		<big id="changeresclose">Close</big>
		<b>Select or Deselect dates to change your reservation</b>
		<p>If you submit this form with no new dates selected in green, your reservation will be canceled.</p>
		<p>No changes will occur until the "Submit Change" button below is clicked and you follow the steps on the confirmation page.</p>
		<table id="changereservep">
				<tr class="thead">
					<td>Date</td>
					<td>Annex 1</td>
					<td>Annex 2</td>
					<td>Annex 3</td>
					<td>Annex 4</td>
					<td>Main 1</td>
					<td>Main 2</td>
					<td>Main 3</td>
				</tr>
		</table>
		
		<div id="changedates">
			
			<form method="post" action="changeres.php">
				<input id="jsonchangedates" name="jsonchangedates" value="" type="hidden" />
				<input id="changetransid" name="changetransid" value="" type="hidden" />
				<br />
				<strong><em>Change to Dates in Green: </em></strong>
				<input type="submit" class="submitbutton" name="bookit" value="Submit Change" />
			</form>
		</div>
		
	</div>
	
	
	<div id="lookup">
		<h2><em>Check Availabilty and Book New Dates</em></h2>
		<form action="#" onsubmit="proxycontact(this.datein.value,this.dateout.value); return false">
			<table>
				<tr>
					<td>Arrival Date <br /><small>(e.g. 2010-07-15)</td>
					<td>Departure Date <br /><small>(e.g. 2010-07-25)</td>
				</tr>
				<tr>
					<td><input type="text" name="datein" id="datepickerin" value="<?=$earlydate?>" /></td>
					<td><input type="text" name="dateout" id="datepickerout" value="<?=$latedate?>" /></td>
					<td><input type="submit" class="submitbutton" id="lookupsubmit" value="Go!" /></td>
				</tr>
			</table>
		</form>
	</div>
	
	<div id="results" style="display: none;">
		<h3><em>Currently Available:</em></h3>
		<big id="lookupclose">Close</big>
		<table id="resultsp">
				<tr class="thead">
					<td>Date</td>
					<td>Annex 1</td>
					<td>Annex 2</td>
					<td>Annex 3</td>
					<td>Annex 4</td>
					<td>Main 1</td>
					<td>Main 2</td>
					<td>Main 3</td>
				</tr>
		</table>
		
		<div id="booking" style="display: none;">
			<form method="post" action="bookit.php">
				<input id="jsondates" name="jsondates" value="" type="hidden" />
				<strong><em>Book These Dates: </em></strong><input type="submit" class="submitbutton" name="bookit" value="Book It" />
			</form>
	</div>
	</div>

	<? rightnavwrite($d) ?>

</div>

<? footwrite($d) ?>

</body>
</html>
