<?php

    // require common code
    require_once("includes/common.php"); 
    
    // remember user
    $user = $_SESSION["uid"];

    // escape email and password for safety
    $email = mysql_real_escape_string($_POST["email"]);
    $oldpassword = md5(mysql_real_escape_string($_POST["oldpassword"]));
	$newpassword = md5(mysql_real_escape_string($_POST["newpassword"]));
	$newpasswordretype = md5(mysql_real_escape_string($_POST["newpasswordretype"]));

	// prepare SQL
    $sql = "SELECT password, username FROM login WHERE uid=$user";

    // execute query
    $result = mysql_query($sql);
    $oldpwdcheck = mysql_result($result,0,0);
    $usernamecheck = mysql_result($result,0,1);

    // if we found user, update password
    if ($oldpassword == $oldpwdcheck && $newpassword != NULL && $usernamecheck == $email)
    {   
    	// insert newpassword into mysql table
        mysql_query("UPDATE login SET password='$newpassword' WHERE uid=$user");

        // redirect to portfolio
        redirect("index.php");
    }

    // report no email
    else if ($email == "")
    {
    	apologize("please enter an email");
    }
    
    // report wrong email
    else if ($usernamecheck != $email)
    {
    	apologize("wrong email!");
    }
    
    // report wrong old password
    else if ($oldpwdcheck != $oldpassword)
    {
    	apologize("wrong old password!");
    }
    
    // report no password
    else if ($oldpassword == "" || $newpassword == "")
    {
    	apologize("please enter a password");
    }
    
    // report password mismatch
    else if ($newpassword != $newpasswordretype)
    {
    	apologize("passwords do not match!");
    }
    
    // other errors?        
    else
        apologize("Invalid email and/or password!");
?>
