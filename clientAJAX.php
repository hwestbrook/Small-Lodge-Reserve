<?

    // constants
    require_once("includes/common.php");
	
	// prepare an array to encapsulate the new dates
    $clients = array();
	
    // pull the corresponding dates from the transaction table
    $sql = "SELECT uid,CASE WHEN admin=0 THEN 'N' ELSE 'Y' END,firstname,lastname,phonenumber,email,addressline1,addressline2,city,state,login.zipcode FROM login,zips WHERE login.zipcode=zips.ZipCode ORDER BY lastname";
    $result = mysql_query($sql);

    // iterate over result set and put into array
    while ($row = mysql_fetch_row($result)) {
    	// prepare an array for the client
    	$client = $row;

    	$aaData[] = $client;
    }
	$clients["aaData"] = $aaData;
    // output clients
    header("Content-type: text/plain");
    print(json_encode($clients));
    
?>
