<?php
session_start();
	include_once('content.php');
	// $_SESSION['loggedIn'];
	
	function granted()
	{
		if(!isset($_SESSION['loggedIn']))//not logged in
		{
			$login = verifyLogin();
			if($login)
			{
				$_SESSION['loggedIn'] = True;
			}
		}
	}
	granted();
	// include_once("includes/navigation.php");
	//Check on every page if they are logged in by this function
	//If the session already exists good to go
	//else verify info through table
		//if not correct send back not correct
		//else send back correct and define the session variable
?>