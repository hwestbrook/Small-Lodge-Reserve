<?

    // require common code
    require_once("includes/common.php"); 

    // escape email and password for safety
    $email = mysql_real_escape_string($_POST["email"]);
    $firstname = mysql_real_escape_string($_POST["firstname"]);
    $lastname = mysql_real_escape_string($_POST["lastname"]);
    $phoneunclean = mysql_real_escape_string($_POST["phone"]);
    $address1 = mysql_real_escape_string($_POST["address1"]);
    $address2 = mysql_real_escape_string($_POST["address2"]);
    $zipcode = mysql_real_escape_string($_POST["zipcode"]);
    $password = md5(mysql_real_escape_string($_POST["password"]));
	$passwordretype = md5(mysql_real_escape_string($_POST["passwordretype"]));

	// validate email address
	if (validEmail($email) != true) {
		apologize("Invalid email address");
	}
	
	// clean phone
	$clean = phoneClean($phoneunclean);
	$phone = $clean[0];
	
	// validate zip code
	if (validateUSAZip($zipcode) != true) {
		apologize("Invalid zip code");
	}

    // once registered, remember user and redirect to portfolio
    if ($password != "" && $password == $passwordretype && $email != "")
    {
        // insert data into mysql table
        mysql_query("INSERT INTO login (firstname, lastname, phonenumber, password, email, addressline1, addressline2, zipcode) VALUES('$firstname', '$lastname', '$phone', '$password', '$email', '$address1', '$address2', $zipcode)");

		// get the uid from the table
		$sql = "SELECT uid,admin FROM login WHERE email='$email' AND password='$password'";
		$result = mysql_query($sql);
		$row = mysql_fetch_array($result);
		
		// cache uid and admin status in session
        $_SESSION["uid"] = $row["uid"];
        $_SESSION["admin"] = $row["admin"];

        // redirect to portfolio
        redirect("usercenter.php");
    }
    
    // report no email
    else if ($email == "")
    {
    	apologize("please enter an email");
    }
    
    // report no password
    else if ($password == "")
    {
    	apologize("please enter a password");
    }
    
        // report password mismatch
    else if ($password != $passwordretype)
    {
    	apologize("passwords do not match!");
    }
        
    else
        apologize("Invalid email and/or password!");

?>
