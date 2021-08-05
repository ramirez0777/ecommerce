<?php
class index
{
function buildIndex()
{
	$index = '';
	if(empty($_POST['sub-button']) & !isset($_SESSION['loggedIn'])) //button not clicked or not logged in
	{ //build form
		$index .= loginForm(0);
	}
	if(!empty($_POST['sub-button'])) //button clicked
	{
		$ec = 0;
		if(!empty($_POST['user'])){$user = strtolower($_POST['user']);} else {$user = ''; $ec = 1;}
		if(!empty($_POST['pass'])){$pass = $_POST['pass'];} else {$pass = ''; $ec = 1;}
		
		if ($ec) //if something was empty
		{
			$index .= loginForm($ec);
		}
		else //nothing empty
		{
			$login = verifyLogin($user, $pass);
			if($login){header('location: catalog.php'); } else{$index .= loginForm(2);}
		}
	}
	elseif(isset($_SESSION['loggedIn'])) //logged in
	{
		$index .= welcome();
	}
	return $index;
}
/*****************************BUILD FORM TO LOGIN************************************************************************/
function loginForm($ec)
{
	$site = '';
	$site .= '<h2>Welcome please login or create an account</h2>';
	$site .= '<form method="post" action="?s=" class="">';
		$site .= '<input type="text" name="user" placeholder="Username">';
		$site .= '<input type="password" name="pass" placeholder="Password">';
		$site .= '<input type="submit" value="Login" class="submit" name="sub-button">';
		$site .= '<div class="error">';
		if ($ec) {$site .= loginError($ec);}
		$site .= '</div>';
	$site .= '</form>';
	return $site;
}

function loginError($ec)
{
	switch($ec)
	{
		case '1': 
			$error = 'Please Fill In All Fields';
			break;
		case '2':
			$error = 'Username Or Password Not Recognized';
			break;
	}
	return $error;
}


/*********************************INDEX PAGE IF LOGGED IN************************************************************/
function welcome()
{
	$site = '';
	$site .= '<h1>Welcome <span class="user">'.ucfirst($_SESSION['user']).'</span> to Acme</h1>';
	
	return $site;
}


/*********************************************************CHECK IF I SHOULD LOG THEM IN****************************************************/
function verifyLogin($username = '', $password = '')
{

	// Create Connection
	$conn = mysqli_connect(HOST, USER, PASS, BASE);
	
	$salt1 = hash("SHA256", $username);
	$salt2 = hash("SHA512", $username.$username.$username);
	$password = $salt1.$password.$salt2;
	$password = hash("SHA512",$password);
	// Create Command
	$sql = 'SELECT * FROM `user1`';
			
	// Run Command
	$results = mysqli_query($conn, $sql) or die("something's wrong: ".mysqli_connect_error());
	
	// $login = mysqli_num_rows($results);
	$login = False;
	while($final = mysqli_fetch_array($results, MYSQLI_ASSOC))
	{

		if ($final['username'] == $username & $final['password'] == $password)
		{
			$login = True;
			$_SESSION['user'] = $username;
			$_SESSION['loggedIn'] = True;
			break;
		}
		else
		{
			$login = False;
		}
	}
	

	return $login;
}
}

?>