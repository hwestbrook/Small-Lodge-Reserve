<?php
    
    // constants
    require_once("includes/common.php"); 
    
    // scrub the values for problems
    $comment = mysql_real_escape_string($_POST["newvalue"]);
    $transid = mysql_real_escape_string($_POST["transid"]);
	
	// insert the new invoice number everywhere the transid is
	$sql = "UPDATE rsrvtrans SET comments='$comment' WHERE transactionid='$transid'";
	mysql_query($sql);
	
	// to tell the user the server has been updated.
	echo $comment.' (updated)';
?>