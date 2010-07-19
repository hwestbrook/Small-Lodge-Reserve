<?

    // constants
    require_once("includes/common.php");

    // connect to database
    mysql_connect(DB_SERVER, DB_USER, DB_PASS);

    // select database
    mysql_select_db(DB_NAME);

    // prepare an array for the lookup dates
    $datesarray = array();


    // ensure each parameter is in lat,lng format
    if (!date_check($_GET["datein"]) ||
        !date_check($_GET["dateout"]))
    {
        header("Content-type: text/plain");
        print(json_encode($dates));
        exit;
    }
    
    // check for good inputs and make variables
    $datein = mysql_real_escape_string($_GET["datein"]);
    $dateout = mysql_real_escape_string($_GET["dateout"]);
    	
    // make sure date in is before date out
    if (strtotime($dateout) < strtotime($datein))
    {
        header("Content-type: text/plain");
        print(json_encode($dates));
        exit;
    }

	// figure out the size of the array needed
	$sqldatemath = "SELECT TIMESTAMPDIFF(DAY,'$datein','$dateout')";
	$numDays = mysql_result(mysql_query($sqldatemath),0,0);
	
	// $numDays = date('d',strtotime("$dateout") - strtotime("$datein"));
	
	// prepare an array to encapsulate the new dates
    $dates = array();
	
	// put the dates in the array
	for ($i = 0; $i < $numDays; $i++) {
		$datesarray[$i] = date('Y-m-d', strtotime($datein . " +" . $i . " day"));
	}
	
    // pull the corresponding dates from the transaction table
    $sql = "SELECT DISTINCT date FROM rsrvtrans WHERE date BETWEEN '$datein' AND '$dateout' ORDER BY date";
    $result = mysql_query($sql);

    // iterate over result set
    $row = mysql_fetch_assoc($result);
    
    for ($j = 0; $j < $numDays; $j++) {
    	// prepare an array for the date
    	$date = array();
    	
    	// put formatted date into the array
    	$date[] = date('m-d, l', strtotime($datesarray[$j]));
    	
    	// if no result, then no occupancy on this day, so have to fill blanks
    	if ($datesarray[$j] != $row["date"]) {
    		
    		// iteritate over all of the rooms on this day, making them empty
    		for ($k = 0; $k < 7; $k++) {
    			$date[] = "Vacant";
    		}
    	}
    	// if result, then put the result in the right place
    	else if ($datesarray[$j] == $row["date"]) {
    	
    		// query DB for the occupancy
			$sql_room = "SELECT room,transactionid FROM rsrvtrans WHERE date='$datesarray[$j]' ORDER BY room";
			$result_room = mysql_query($sql_room);
    		
    		// iteritate over all of the rooms on this day
    		$row_room = mysql_fetch_assoc($result_room);
    		for ($k = 0; $k < 7; $k++) {
    			if ($k + 1 == $row_room["room"]) {
    				$date[] = $row_room["transactionid"];
    				$row_room = mysql_fetch_assoc($result_room);
    			}
    			else
    				$date[] = "Vacant";
    		}
    		
    		// hit the mysql array again
    		$row = mysql_fetch_assoc($result);
    	}
    	$datesb[] = $date;
    }
	$dates["aaData"] = $datesb;
    // output cities
    header("Content-type: text/plain");
    print(json_encode($dates));
    
?>
