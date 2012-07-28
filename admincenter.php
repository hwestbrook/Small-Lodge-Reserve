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
		SELECT lastname,firstname,phonenumber,email,handicap,activities,comments,prefcontact,transactionid,timestamp,roomname,maxdate,mindate,numguests,invoicenum
		FROM
			(SELECT lastname,firstname,phonenumber,email,handicap,activities,comments,prefcontact,transactionid,timestamp,roomname,MAX(date) AS maxdate,MIN(date) AS mindate,numguests,invoicenum 
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
			
			/* Formating function for row details */
			function fnFormatDetails ( oTable, nTr )
			{
				var aData = oTable.fnGetData( nTr );
				var sOut = '<table cellpadding="5" cellspacing="0" border="0" style="padding-left:50px; text-align:left;">';
				sOut += '<tr><td>Name:</td><td>'+aData[2]+', '+aData[3]+'</td>';
				sOut += '<td>Phone:</td><td>'+aData[4]+'</td></tr>';
				sOut += '<tr><td>Email:</td><td>'+aData[5]+'</td>';
				sOut += '<td>Pref Contact:</td><td>'+aData[14]+'</td></tr>';
				sOut += '<tr><td>Handicap Accessibility:</td><td>'+aData[6]+'</td></tr>';
				sOut += '<tr style="display:none;"><td>'+aData[15]+'</td></tr>';
				sOut += '<tr><td>Activities:</td><td class="activities" colspan="5">'+aData[12]+'</td></tr>';
				sOut += '<tr><td>Comments:</td><td class="comments" colspan="5">'+aData[13]+'</td></tr>';
				sOut += '</table>';

				return sOut;
			}
			
			var oTable;
			
			$(document).ready(function() {
				/*
				 * Insert a 'details' column to the table
				 */
				var nCloneTh = document.createElement( 'th' );
				var nCloneTd = document.createElement( 'td' );
				nCloneTd.innerHTML = '<img src="includes/details_open.png">';
				nCloneTd.className = "center";

				$('#curres thead tr').each( function () {
					this.insertBefore( nCloneTh, this.childNodes[0] );
				} );

				$('#curres tbody tr').each( function () {
					this.insertBefore(  nCloneTd.cloneNode( true ), this.childNodes[0] );
				} );

				/* Apply the jEditable handlers to the table */
				$('.invoice').editable( 'invoicechanger.php', {
					"callback": function( sValue, y ) {
						var aPos = oTable.fnGetPosition( this );
						oTable.fnUpdate( sValue, aPos[0], aPos[1] );
					},
					"submitdata": function ( value, settings ) {
						return { "row_id": this.parentNode.getAttribute('class'), "transid": this.previousElementSibling.previousElementSibling.firstChild.innerHTML };
					},
					"height": "10px",
					"tooltip": "Click to edit... Add the invoice number here!",
					"name":'newvalue'
				} );
				
				
				
				/* Init DataTables */
				oTable = $('#curres').dataTable( {
					"aoColumnDefs": [
						{ "bSortable": false, "aTargets": [ 0 ] }
					],
					"aaSorting": [[1, 'asc']],
					"aoColumns": [ 
						/* plus */   null,
						/* res id */  null,
						/* lname */  null,
						/* fname */ { "bSearchable": false,"bVisible": false },
						/* phone */ { "bSearchable": false,"bVisible": false },
						/* email */ { "bSearchable": false,"bVisible": false },
						/* Hcap */ { "bSearchable": false,"bVisible": false },
						/* inv# */  null,
						/* rm */    null,
						/* ci */  null,
						/* co */  null,
						/* Gs */  null,
						/* activities */ { "bSearchable": false,"bVisible": false },
						/* comments */ { "bSearchable": false,"bVisible": false },
						/* prefcontact */ { "bSearchable": false,"bVisible": false },
						/* text res */ { "bSearchable": false,"bVisible": false },
						/* del */  null
					],
				});
				
				
				/* Add event listener for opening and closing details
				 * Note that the indicator for showing which row is open is not controlled by DataTables,
				 * rather it is done here
				 */
				$('td img', oTable.fnGetNodes() ).each( function () {
					$(this).click( function () {
						var nTr = this.parentNode.parentNode;
						if ( this.src.match('details_close') )
						{
							/* This row is already open - close it */
							this.src = "includes/details_open.png";
							oTable.fnClose( nTr );
						}
						else
						{
							/* Open this row */
							this.src = "includes/details_close.png";
							oTable.fnOpen( nTr, fnFormatDetails(oTable, nTr), 'details' );
							/* Apply the jEditable handlers to the table */
							$('.activities').editable( 'activitychanger.php', {
								"callback": function( sValue, y ) {
									var aPos = oTable.fnGetPosition( this );
									oTable.fnUpdate( sValue, aPos[0], aPos[1] );
								},
								"submitdata": function ( value, settings ) {
									return { "row_id": this.parentNode.getAttribute('class'), "transid": this.parentNode.firstElementChild.parentNode.previousSibling.firstChild.innerHTML };
								},
								"height": "10px",
								"tooltip": "Click to edit... add comments here!",
								"name":'newvalue'
							} );

							/* Apply the jEditable handlers to the table */
							$('.comments').editable( 'commentchanger.php', {
								"callback": function( sValue, y ) {
									var aPos = oTable.fnGetPosition( this );
									oTable.fnUpdate( sValue, aPos[0], aPos[1] );
								},
								"submitdata": function ( value, settings ) {
									return { "row_id": this.parentNode.getAttribute('class'), "transid": this.parentNode.firstElementChild.parentNode.previousSibling.previousSibling.firstChild.innerHTML };
								},
								"height": "10px",
								"tooltip": "Click to edit... add comments here!",
								"name":'newvalue'
							} );
						}
					} );
				} );
			} );
	</script>

	<style type="text/css" media="screen">
		#changeresclose {
			position: relative;
			top: -40px;
			left: 700px;
			background-color: #A5B4C5;
			padding: 5px;
			border: 1px solid black;
		}
		#lookupclose {
			position: relative;
			top: -40px;
			left: 700px;
			background-color: #A5B4C5;
			padding: 5px;
			border: 1px solid black;
		}
	</style>

	<!-- sliding tables! -->
	<script type="text/javascript">
		$(document).ready(function() {
		    $(".transid").click(function () {
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

	<div id="content" style="background-color: yellow;">
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
						<th style="width: 100px; " class="sorting"><? echo "F.Name" ?></th>
						<th style="width: 100px; " class="sorting"><? echo "Phone" ?></th>
						<th style="width: 100px; " class="sorting"><? echo "Email" ?></th>
						<th style="width: 100px; " class="sorting"><? echo "HCap" ?></th>
						<th style="width: 100px; " class="sorting"><? echo "Inv #" ?></th>
						<th style="width: 100px; " class="sorting"><? echo "Rm" ?></th>
						<th style="width: 100px; " class="sorting"><? echo "CI" ?></th>
						<th style="width: 100px; " class="sorting"><? echo "CO" ?></th>
						<th style="width: 100px; " class="sorting"><? echo "# Gs" ?></th>
						<th style="width: 100px; " class="sorting"><? echo "Act" ?></th>
						<th style="width: 100px; " class="sorting"><? echo "Com" ?></th>
						<th style="width: 100px; " class="sorting"><? echo "Contact" ?></th>
						<th style="width: 100px; " class="sorting"><? echo "textres" ?></th>
						<th style="width: 100px; " class="sorting"><? echo "Del" ?></th>
					</tr>
				</thead>
				<tbody class="currestbl">
				<? while ($row = mysql_fetch_assoc($curres)) { ?>
					<tr class="curresrow">
						<td class="transid"><a onclick="changeres( '<? 
							echo date('Y-m-d', strtotime('-3 day', strtotime($row["mindate"]))) ?>', '<? 
							echo date('Y-m-d', strtotime('+4 days', strtotime($row["maxdate"]))) ?>', '<? 
							echo $row["transactionid"] ?>');"><? 
							echo $row["transactionid"] ?></a>
						</td>
						<td class="lastname"><? echo $row["lastname"] ?></td>
						<td class="firstname"><? echo $row["firstname"] ?></td>
						<td class="phonenumber"><? echo $row["phonenumber"] ?></td>
						<td class="email"><? echo $row["email"] ?></td>
						<td class="handicap"><? if ($row["handicap"] == 0) {
									echo "No";
								}
							   else {
									echo "Yes";
							   }
						?></td>
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
						<td class="activities"><? echo $row["activities"] ?></td>
						<td class="comments"><? echo $row["comments"] ?></td>
						<td class="prefcontact"><? echo $row["prefcontact"] ?></td>
						<td class="prefcontact"><? echo $row["transactionid"] ?></td>
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
		<form action="" onsubmit="proxycontact(this.datein.value,this.dateout.value); return false">
			<table>
				<tr>
					<td>Arrival Date <br /><small>(e.g. 2010-07-15)</td>
					<td>Departure Date <br /><small>(e.g. 2010-07-25)</td>
				</tr>
				<tr>
					<td><input type="text" name="datein" id="datepickerin" /></td>
					<td><input type="text" name="dateout" id="datepickerout" /></td>
					<td><input id="lookupsubmit" type="submit" class="submitbutton" value="Go!" /></td>
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
			<form method="post" action="adminbookit.php">
				<input id="jsondates" name="jsondates" value="" type="hidden" />
				<strong><em>Book These Dates: </em></strong><input type="submit" class="submitbutton" name="bookit" value="Book It" />
			</form>
	</div>
	</div>

	<? adminnavwrite($d) ?>

</div>

<? footwrite($d) ?>

</body>
</html>
