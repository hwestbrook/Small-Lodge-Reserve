<?php
	
	//page width a is narrow, b is wide
	$d = "b";
	
    // require common code
    require_once("includes/common.php"); 
    
    //set the max amount of reservations to show at open
	$earlydate = date('Y-m-d', strtotime('-1 Year'));
	$latedate = date('Y-m-d', strtotime('+18 Months'));

?>


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN"
	"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en">
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
				$('.invoice').editable( 'clientchanger.php', {
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
				oTable = $('#clients').dataTable({
					"bProcessing": true,
					"sAjaxSource": ('clientAJAX.php')
				});
			} );
	</script>

<!--
	<script type="text/javascript" charset="utf-8">
		$(document).ready(function() {
			$('#resultsp').dataTable( {
				"bProcessing": true,
				"sAjaxSource": ('availabilityAJAX.php?datein=' + '2010-07-15' + '&dateout=' + '2010-08-30')
			} );
		} );
		
	</script>-->


</head>

<body>

<div class="wrap<?=$d?>">

	<? headwrite($d) ?>
	
	<? navwrite($d) ?>
	
	<? progresswrite() ?>

	<div id="results">
		<h3><em>Currently Available:</em></h3>
		<table cellpadding="0" cellspacing="0" border="0" class="display" id="clients">
			<!-- uid,admin,firstname,lastname,phonenumber,email,addressline1,addressline2,city,state,login.zipcode -->
			<thead>	
				<tr class="thead">
					<th class="clienthead"></th>
					<th class="clienthead"></th>
					<th class="clienthead">F.Name</th>
					<th class="clienthead">L.Name</th>
					<th class="clienthead"></th>
					<th class="clienthead"></th>
					<th class="clienthead">Ad 1</th>
					<th class="clienthead">Ad 2</th>
					<th class="clienthead">City</th>
					<th class="clienthead">ST</th>
					<th class="clienthead">Zip</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>

	<? adminnavwrite($d) ?>

</div>

<? footwrite($d) ?>

</body>
</html>
