<?

    // require common code
    require_once("includes/common.php"); 

    // escape email and password for safety
    $email = mysql_real_escape_string($_POST["email"]);
    $password = md5(mysql_real_escape_string($_POST["password"]));

	// validate email address
	if (validEmail($email) != true) {
		apologize("Invalid email address");
	}

    // prepare SQL
    $sql = "SELECT uid,admin FROM login WHERE email='$email' AND password='$password'";

    // execute query
    $result = mysql_query($sql);

    // if we found a row, remember user and redirect to portfolio
    if (mysql_num_rows($result) == 1)
    {
        // grab row
        $row = mysql_fetch_array($result);

        // cache uid and admin status in session
        $_SESSION["uid"] = $row["uid"];
        $_SESSION["admin"] = $row["admin"];

        // redirect to portfolio
        redirect("usercenter.php");
    }

    // else report error
    else
        apologize("Invalid email and/or password!");

?>
