<?php

	// require common code
    require_once("includes/common.php"); 


/* Function to write the REAL HTML header! */
function realheadwrite() {

	echo <<<EOT

	<title>Reservations</title>
	<link href="../mainstyle.css" rel="stylesheet" type="text/css" />
    
    <!-- jQuery & jQuery UI & CSS to go with the UI-->
    <link type="text/css" href="css/flick/jquery-ui-1.8.2.custom.css" rel="Stylesheet" />	
	<script type="text/javascript" src="otherjs/jquery-1.4.2.min.js"></script>
	<script type="text/javascript" src="otherjs/jquery-ui-1.8.2.custom.min.js"></script>	
	
	<!-- JSON javascript-->
	<script type="text/javascript" src="otherjs/json2.min.js"></script>
	
	<!-- reserve's JavaScript -->
    <script src="reserve.js" type="text/javascript"></script>
    
    <!-- change reserve's JavaScript -->
    <script src="changeres.js" type="text/javascript"></script>
    
EOT;

}

/* Function to write the navigation! */
function navwrite($d) {

echo <<<EOT
 		<div class="c1$d" style="background:none;">
			<div id="sidebar1">
            	<br/><br/>
                <ul id="widgets1">
                		<li class="widget widget_recent_entries">
                		<h2>Pages</h2>
						<ul>
							<h3><li><a href='../index.php' title='Home'>Home</a></li></h3>
							<h3><li><a href='../photos' title='Photos'>Photos</a></li></h3>
							<h3><li><a href='../rooms.php' title='Rooms'>Rooms</a></li></h3>
							<h3><li><a href='../todo.php' title='To Do'>To Do</a></li></h3>
							<h3><li><a href='../about.php' title='About'>About</a></li></h3>
							<h3><li><a href='../contact.php' title='Contact'>Contact</a></li></h3>
							<h3><li><a href='usercenter.php' title='Contact'>Book It!</a></li></h3>
						</ul>
					</li>
                    </ul>       
        	</div>
		</div>
		
		<div class="c2$d">
			
			<div id="bodytext$d">
EOT;
}

/* Function to write the navigation! */
function rightnavwrite($d) {

echo <<<EOT
 				  	</div>
		
			</div>
			<div class="c3$d" style="background:none;">
    			<div class="midtext"></div>
    		</div>
    	</div>
EOT;
}

/* Function to write the navigation! */
function adminnavwrite($d) {

echo <<<EOT
 				  	</div>
		
			</div>
			<div class="c3$d" style="background:none;">
				<div id="sidebar1">
	            	<br/><br/>
	                <ul id="widgets3">
	                		<li class="widget widget_recent_entries">
	                		<h2>Admin</h2>
							<ul>
								<h3><li><a href='adminclients.php' title='Clients'>Clients</a></li></h3>
								<h3><li><a href='admincenter.php' title='Admin Center'>Admin Center</a></li></h3>
							</ul>
						</li>
	                    </ul>       
	        	</div>
			</div>
    	</div>
EOT;
}


/* Function to write the header! */
function headwrite($d) {

	echo <<<EOT
<div id="head">
	
 </div>
 <div id="midbar">
		
		<div class="c1$d" style="background:none;">
			<div class="toptext"><a href="mailto:hilofishing@yahoo.com"><img src="/images/graphics/mail.png" width="16" height="16" alt="" border="0" align="right" style="padding-left:5px;" />Contact</a></div>
		</div>

		<div class="c2$d" style="background:none; text-align:center;">
    		<div class="midtext"></div>
		</div>
		
		<div class="c3$d" style="background:none;"></div>

	</div>
	<div>
EOT;

}

/* Function to write the header! */
function simpleheadwrite() {

	echo <<<EOT
<div id="head">
	
 </div>
 <div id="midbar">
		
		<div class="c1a" style="background:none;">
			<div class="toptext"><a href="mailto:hilofishing@yahoo.com"><img src="/images/graphics/mail.png" width="16" height="16" alt="" border="0" align="right" style="padding-left:5px;" />Contact</a></div>
		</div>

		<div class="c2a" style="background:none; text-align:center;">
    		<div class="midtext"></div>
		</div>
		
		<div class="c3a" style="background:none;"></div>

	</div>
	<div>
EOT;
}

/* Function to write the footer! */
function footwrite($d) {

$adminlink = "";

if ($_SESSION["admin"] == 1) {
	$adminlink = "<a href='admincenter.php'>Admin Center</a><strong> * </strong>";
}


	echo <<<EOT
<div id="footer$d">
	
	<div class="c1$d">
  		<div align="left" class="style2"><br /></div>
	</div>

	<div class="c2$d">
		<div>"That's Experience You Can Trust!"</div>
		$adminlink
		<a href='usercenter.php'>User Center</a>
		<strong>*</strong>
		<a href='myinfo.php'>My Information</a>
		<strong>*</strong>
		<a href='logout.php'>Log Out</a>
	</div>
	
	<div class="c3$d">
  		<div align="right"><br /></div>
	</div>

</div>
EOT;
}

/* Function to write the outsider footer! */
function outsidefootwrite($d) {

	echo <<<EOT
<div id="footera">
	
	<div class="c1$d">
  		<div align="left" class="style2"><br /></div>
	</div>

	<div class="c2$d">
		<div>"That's Experience You Can Trust!"</div>
	</div>
	
	<div class="c3$d">
  		<div align="right"><br /></div>
	</div>

</div>
EOT;
}

/* Function to write the footer! */
function progresswrite() {

	echo <<<EOT
<div id="progress" style="display: none;">
  <img alt="Fetching information..." src="includes/progress.gif" />
</div>
EOT;
}

?>