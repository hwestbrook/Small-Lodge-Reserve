
<!DOCTYPE html 
     PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
     "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml">

  <head>
    <? realheadwrite() ?>
  </head>

  <body>

    <? simpleheadwrite() ?>
 

    <div id="content">
      <h1>Sorry!</h1>
      	<br />
      	<br />
		<div align="right">
		  <?php echo $message; ?>
		</div>
		<div align="right" style="margin: 20px;">
		  <a href="javascript:history.go(-1);">Back</a>
		</div>
    </div>


  </body>

</html>
