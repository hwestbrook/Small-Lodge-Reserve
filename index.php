<?
	//page width a is narrow, b is wide
	$d = "a";
	
	// require common code
    require_once("includes/common.php"); 
    
    // get user id for looking up Portfolio
    // $user = $_SESSION["uid"];
    
    //set the auto values
	$earlydate = date('Y-m-d', strtotime('Today'));
	$latedate = date('Y-m-d', strtotime('+30 Days'));
   
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
		<h2><em>Check Lodging Availabilty at Hi-Lo!</em></h2>
		
		<form action="#" onsubmit="proxycontact(this.datein.value,this.dateout.value); return false">
			<table>
				<tr>
					<td>Arrival Date <br /><small>(e.g. 2010-07-15)</small></td>
					<td>Departure Date <br /><small>(e.g. 2010-07-25)</small></td>
				</tr>
				<tr>
					<td><input type="date" name="datein" id="datepickerin" value="<?=$earlydate?>" /></td>
					<td><input type="date" name="dateout" id="datepickerout" value="<?=$latedate?>" /></td>
					<td><input type="submit" /></td>
				</tr>
			</table>
		</form>
		<p>or just <a href="login.php">Login</a></p>
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
	
	<? rightnavwrite($d) ?>

</div>

<? outsidefootwrite($d) ?>

</body>
</html>
