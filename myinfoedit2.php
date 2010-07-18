<?

    // require common code
    require_once("includes/common.php"); 
    
    // get the user 
	$user = $_SESSION["uid"];

    // escape email and password for safety
    $email = mysql_real_escape_string($_POST["email"]);
    $firstname = mysql_real_escape_string($_POST["firstname"]);
    $lastname = mysql_real_escape_string($_POST["lastname"]);
    $phoneunclean = mysql_real_escape_string($_POST["phonenumber"]);
    $address1 = mysql_real_escape_string($_POST["addressline1"]);
    $address2 = mysql_real_escape_string($_POST["addressline2"]);
    $zipcode = mysql_real_escape_string($_POST["zipcode"]);

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

    // once registered, remember user and redirect to myinfo.php
    if ( $email != "")
    {
        // insert data into mysql table
        mysql_query("UPDATE login SET firstname='$firstname',lastname='$lastname',phonenumber='$phone',email='$email',addressline1='$address1',addressline2='$address2',zipcode=$zipcode WHERE uid=$user");

        // redirect to portfolio
        redirect("myinfo.php");
    }
    
    // report no email
    else if ($email == "")
    {
    	apologize("please enter an email");
    }
        
    else
        apologize("Invalid email");

?>
