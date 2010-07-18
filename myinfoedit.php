<?
	//page width a is narrow, b is wide
	$d = "a";

    // require common code
    require_once("includes/common.php");

	// get the user 
	$user = $_SESSION["uid"];

	// get mysql ready and get Information
	$sql = "SELECT firstname,lastname,phonenumber,password,email,addressline1,addressline2,zipcode FROM login WHERE uid=$user";
	$row = mysql_fetch_assoc(mysql_query($sql));

?>

<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

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
		<h2>My Information</h2>
		<form method="post" action="myinfoedit2.php">
			<table id="myinfo">
				<tr>
					<td class="myinfolist">First Name: </td>
					<td class="myinfo"><input type="text" name="firstname" value="<? echo $row["firstname"] ?>" /></td>
				</tr>
				<tr>
					<td class="myinfolist">Last Name: </td>
					<td class="myinfo"><input type="text" name="lastname" value="<? echo $row["lastname"] ?>" /></td>
				</tr>
				<tr>
					<td class="myinfolist">Phone Number: </td>
					<td class="myinfo"><input type="text" name="phonenumber" value="<? echo $row["phonenumber"] ?>" /></td>
				</tr>
				<tr>
					<td class="myinfolist">Email: </td>
					<td class="myinfo"><input type="text" name="email" value="<? echo $row["email"] ?>" /></td>
				</tr>
				<tr>
					<td class="myinfolist">Address Line 1:  </td>
					<td class="myinfo"><input type="text" name="addressline1" value="<? echo $row["addressline1"] ?>" /></td>
				</tr>
				<tr>
					<td class="myinfolist">Address Line 2: </td>
					<td class="myinfo"><input type="text" name="addressline2" value="<? echo $row["addressline2"] ?>" /></td>
				</tr>
				<tr>
					<td class="myinfolist">Zip Code: </td>
					<td class="myinfo"><input type="text" name="zipcode" value="<? echo $row["zipcode"] ?>" /></td>
				</tr>
				<tr>
					<td class="myinfolist"></td>
					<td class="myinfo"><input type="submit" class="submitbutton" value="Update Information" /></td>
				</tr>
				<tr>
					<td class="myinfolist"></td>
					<td class="myinfo"><br /></td>
				</tr>
				<tr>
					<td class="myinfolist"></td>
					<td class="myinfo"><a href="changepwd">Or Change Password?</a></td>
				</tr>
				<tr>
					<td class="myinfolist"></td>
					<td class="myinfo"><br /></td>
				</tr>
			  </table>
		</form>	
    </div>

	<? rightnavwrite($d) ?>

</div>

<? footwrite($d) ?>

</body>

</html>
