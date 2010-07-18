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
      Thank you for visiting our booking site. Feel free to:
      <br /><br />
      <a href="/photos">Browse</a> our site and blog,
      <br /><br />
      check out <a href="index.php">Availability</a> (No Login Required)
      <br /><br />
      or <a href="login.php">Return</a> to the user center (Login Required).
    </div>

	<? rightnavwrite($d) ?>

</div>

<? outsidefootwrite($d) ?>

  </body>

</html>
