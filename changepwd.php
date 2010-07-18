<?
	//page width a is narrow, b is wide
	$d = "a";

    // require common code
    require_once("includes/common.php");


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
      <h2>Change Password</h2>
      <form action="changepwd2.php" method="post">
        <table border="0" align="center">
          <tr>
            <td class="field">Email:</td>
            <td><input name="email" type="email" /></td>
          </tr>
          <tr>
            <td class="field">Old Password:</td>
            <td><input name="oldpassword" type="password" /></td>
          </tr>
          <tr>
            <td class="field">New Password:</td>
            <td><input name="newpassword" type="password" /></td>
          </tr>
          <tr>
            <td class="field">Retype Password:</td>
            <td><input name="newpasswordretype" type="password" /></td>
          </tr>
          <tr>
            <td></td>
            <td><input type="submit" value="Change Password" /></td>
          </tr>
          <tr>
            <td></td>
            <td>or return to the <a href="usercenter.php">User Center</a></td>
          </tr>
        </table>
      </form>
    </div>

	<? rightnavwrite($d) ?>

</div>

<? footwrite($d) ?>

</body>

</html>
