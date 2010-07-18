<?
	//page width a is narrow, b is wide
	$d = "a";

    // require common code
    require_once("includes/common.php");

    // log out current user (if any)
    logout();

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
		  <h3>To book your vacation, please <span class="bigletters"><a href="register.php">Register</a></span> for an account</h3>
		  <form action="login2.php" method="post">
			<table border="0" align="left">
			  <tr>
				<td class="field">Email:</td>
				<td><input name="email" type="email" /></td>
			  </tr>
			  <tr>
				<td class="field">Password:</td>
				<td><input name="password" type="password" /></td>
			  </tr>
			  <tr>
				<td></td>
				<td><br /></td>
			  </tr>
			  <tr>
				<td></td>
				<td><input type="submit" value="Log In" /></td>
			  </tr>
			  <tr>
				<td></td>
				<td>or </td>
			  </tr>
			  <tr>
				<td></td>
				<td>or return to the <a href="index.php">Availability</a> page.</td>
			  </tr>
			</table>
		  </form>
		</div>
		
	<? rightnavwrite($d) ?>

</div>

<? outsidefootwrite($d) ?>

  </body>

</html>
