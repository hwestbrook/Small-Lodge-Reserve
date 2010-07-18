<?
	// require common code
    require_once("includes/common.php"); 
    
    // get user id for looking up Portfolio
    $user = $_SESSION["uid"];
    
    // scrub reservation variable coming in through GET
    $transactionid = str_split(mysql_real_escape_string($_GET["resid"]),2);
    
    // get the transaction from the table
    $sql = "SELECT rooms.room,roomname,date,numguests,invoicenum 
    		FROM rsrvtrans,rooms 
    		WHERE rsrvtrans.room=rooms.room AND uid=$user transactionid=$transactionid";
   	$result = mysql_query($sql);
    
    
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<head>

<? realheadwrite() ?>

<script type="text/javascript">
	window.onload=function(){
	 changeres(<? $datein ?>,<? $dateout ?>,<? $transactionid ?>);
	}
</script>

</head>

<body>

<? headwrite() ?>

<? navwrite() ?>

<? progresswrite() ?>

<div id="content">
	<form action="#" onsubmit="proxycontact(this.datein.value,this.dateout.value); return false">
		<table>
			<tr>
				<td>Arrival Date</td>
				<td>Departure Date</td>
			</tr>
			<tr>
				<td><input type="text" name="datein" id="datepickerin" /></td>
				<td><input type="text" name="dateout" id="datepickerout" /></td>
				<td><input type="submit" /></td>
			</tr>
		</table>
	</form>
</div>

<div id="results" style="display: none;">
	<h2><em>Currently Available:</em></h2>
	<b>     If you would like to request these dates, please <a href="login.php">Login</a></b>
	<br />
	<br />
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
</div>

<? outsidefootwrite() ?>


</body>
</html>
