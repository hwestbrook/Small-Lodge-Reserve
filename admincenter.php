<?php
	
	//page width a is narrow, b is wide
	$d = "b";
	
    // require common code
    require_once("includes/common.php"); 
    
    // get user id for looking up Portfolio
    $user = $_SESSION["uid"];
    
    //set the max amount of reservations to show at open
	$earlydate = date('Y-m-d', strtotime('-1 Year'));
	$latedate = date('Y-m-d', strtotime('+18 Months'));
    
	// check for Reservations
	$sqlcheck =<<<EOT
		SELECT lastname,transactionid,timestamp,roomname,maxdate,mindate,numguests,invoicenum
		FROM
			(SELECT lastname,transactionid,timestamp,roomname,MAX(date) AS maxdate,MIN(date) AS mindate,numguests,invoicenum 
			FROM rsrvtrans,rooms,login
			WHERE rsrvtrans.room=rooms.room AND rsrvtrans.uid=login.uid
			GROUP BY transactionid) AS hop
		WHERE maxdate<'$latedate' AND mindate>'$earlydate'
EOT;
	
	$curres = mysql_query($sqlcheck);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<? realheadwrite() ?>

	<link href="otherjs/datatables/css/adminrestable.css" rel="stylesheet" type="text/css" />
	
	<!-- datatables Javascript, -->
	<script type="text/javascript" src="otherjs/datatables/js/jquery.dataTables.min.js"></script>
	
	<!-- jeditable Javascript, -->
	<script type="text/javascript" src="otherjs/jquery.jeditable.mini.js"></script>
	
	<!-- this is the script to initialize the datatables -->
	<script type="text/javascript" charset="utf-8">
			var oTable;
			
			$(document).ready(function() {
				/* Apply the jEditable handlers to the table */
				$('.invoice').editable( 'invoicechanger.php', {
					"callback": function( sValue, y ) {
						var aPos = oTable.fnGetPosition( this );
						oTable.fnUpdate( sValue, aPos[0], aPos[1] );
					},
					"submitdata": function ( value, settings ) {
						return { "row_id": this.parentNode.getAttribute('class'), "transid": this.previousElementSibling.firstChild.innerHTML };
					},
					"height": "10px",
					"tooltip": "Click to edit... Add the invoice number here!",
					"name":'newvalue'
				} );
				
				/* Init DataTables */
				oTable = $('#curres').dataTable();
			} );
		</script>

</head>

<body>

<div class="wrap<?=$d?>">

	<? headwrite($d) ?>
	
	<? navwrite($d) ?>
	
	<? progresswrite() ?>

	<div id="content">
		<h1 text-align="center">**ADMIN CENTER**</h1>
		<a href="allres"></a>
		<h2><em>All Reservations</em></h2>
		<? if (mysql_fetch_assoc($curres) == null) { 
				echo "There are currently no reservations for this account.";
				}   		
		   else { mysql_data_seek($curres,0); ?>
			
			<table id="curres">
				<thead>
					<tr id="curreshead">
						<th style="width: 100px; " class="sorting_asc"><? echo "Res ID" ?></th>
						<th style="width: 100px; " class="sorting"><? echo "L.Name" ?></th>
						<th style="width: 100px; " class="sorting"><? echo "Inv #" ?></th>
						<th style="width: 100px; " class="sorting"><? echo "Rm" ?></th>
						<th style="width: 100px; " class="sorting"><? echo "CI" ?></th>
						<th style="width: 100px; " class="sorting"><? echo "CO" ?></th>
						<th style="width: 100px; " class="sorting"><? echo "# Gs" ?></th>
						<th style="width: 100px; " class="sorting"><? echo "Del" ?></th>
					</tr>
				</thead>
				<tbody class="currestbl">
				<? while ($row = mysql_fetch_assoc($curres)) { ?>
					<tr class="curresrow">
						<td class="transid"><a href="#changeresanchor" onclick="changeres( '<? 
							echo date('Y-m-d', strtotime('-3 day', strtotime($row["mindate"]))) ?>', '<? 
							echo date('Y-m-d', strtotime('+4 days', strtotime($row["maxdate"]))) ?>', '<? 
							echo $row["transactionid"] ?>');"><? 
							echo $row["transactionid"] ?></a>
						</td>
						<td class="lastname"><? echo $row["lastname"] ?></td>
						<td class="invoice"><? if ($row["invoicenum"] == 0) {
									echo "Waiting Approval";
								}
							   else {
									echo $row["invoicenum"];
							   }
						?></td>
						<td class="roomname"><? echo $row["roomname"] ?></td>
						<td class="earlydate"><? echo date('F j, Y', strtotime($row["mindate"])) ?></td>
						<td class="latedate"><? echo date('F j, Y', strtotime( '+1 day', strtotime($row["maxdate"]))) ?></td>
						<td class="numguests"><? echo $row["numguests"] ?></td>
						<td class="transid"><a onClick="alertDel('<?=$row["transactionid"]?>')" href="#allres">Del</a>
						</td>
					</tr>
				<? } ?>
				</tbody>
			</table>
			<small>* Click the link in the Reservation ID column to modify that reservation</small>
		<? } ?>
		
	</div>
	
	<div id="changereserve" style="display: none;">
		<a name="changeresanchor"></a>
		<h3><em>Current Reservation and Available Dates:</em></h3>
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
			
			<form method="post" action="bookit.php">
				<input id="jsonchangedates" name="jsondates" value="" type="hidden" />
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
					<td><input type="text" name="datein" id="datepickerin" /></td>
					<td><input type="text" name="dateout" id="datepickerout" /></td>
					<td><input type="submit" class="submitbutton" value="Go!" /></td>
				</tr>
			</table>
		</form>
	</div>
	
	<div id="results" style="display: none;">
		<h3><em>Currently Available:</em></h3>
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
			<form method="post" action="adminbookit.php">
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
