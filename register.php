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
      <h2>Please Enter Your information</h2>
      We will never share any personal information with outsiders.
      <br /><br /><br />
      <form action="register2.php" method="post">
        <table border="0" align="left">
          <tr>
            <td class="field">Email:</td>
            <td><input name="email" type="email" /></td>
          </tr>
          <tr>
            <td><br /></td>
          </tr>
          <tr>
            <td class="field">First Name:</td>
            <td><input name="firstname" type="text" /></td>
          </tr>
          <tr>
            <td class="field">Last Name:</td>
            <td><input name="lastname" type="text" /></td>
          </tr>
          <tr>
            <td><br /></td>
          </tr>
          <tr>
            <td class="field">Phone:</td>
            <td><input name="phone" type="text" /></td>
          </tr>
          <tr>
            <td><br /></td>
          </tr>
          <tr>
            <td class="field">Address Line 1:</td>
            <td><input name="address1" type="text" /></td>
          </tr>
          <tr>
            <td class="field">Address Line 2:</td>
            <td><input name="address2" type="text" /></td>
          </tr>
          <tr>
            <td class="field">Zip Code:</td>
            <td><input name="zipcode" type="text" /></td>
          </tr>
          <tr>
            <td><br /></td>
          </tr>
          <tr>
            <td class="field">Password:</td>
            <td><input name="password" type="password" /></td>
          </tr>
          <tr>
            <td class="field">Retype Password:</td>
            <td><input name="passwordretype" type="password" /></td>
          </tr>
          <tr>
            <td><br /></td>
          </tr>
          <tr>
            <td></td>
            <td><input type="submit" value="Register" /></td>
          </tr>
          <tr>
            <td></td>
            <td>return to <a href="login.php">Login</a></td>
          </tr>
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
