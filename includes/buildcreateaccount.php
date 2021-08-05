<?php
class createAccount
{
	
	function buildCreateAccount()
{
	$site = '';
	$ec = 0;
	if(isset($_SESSION['loggedIn']))
	{
		$site .= '<h1>You\'re Account Has Already Been Created!</h1>';
		$site .= '<a href="index.php"><img src="img/main.jpg" alt="acme" class="center"></a>';
	}
	else
	{
		if(empty($_POST['create'])) //button not clicked
		{
			$site .= createForm();
		}
		else // button clicked
		{
			if(!empty($_POST['user'])) {$user = strtolower($_POST['user']);} else {$user = ''; $ec = 1;}
			if(!empty($_POST['pass'])) {$pass = $_POST['pass'];} else {$user = ''; $ec = 1;}
			if($ec)
			{
				$site .= createForm($ec);
			}
			else
			{
				$ec = intoDB($user, $pass);
				if($ec === 'granted')
				{
					//let them know their account is good to go
					$site .= '<h1>Your Account Has been Created</h1>';
					$site .= '<h1>Please go to the <a href="index.php">Login</a> Page to Login</h1>';
				}
				elseif ($ec === 'user')
				{
					$site .= createForm($ec);
				}
			}
		}
	
	}
	return $site;
}


/************************************************CREATES FORM TO CREATE ACCOUNT*****************************************************/
function createForm($ec = 0)
{
	$site = '';
	$site .= '<h2>Create Your Account</h2>';
	$site .= '<form method="post" action="?s=">';
		$site .= '<input type="text" name="user" placeholder="Username" >';
		$site .= '<input type="password" name="pass" placeholder="Password" oninput="verify()" id="pass" >';
		$site .= '<input type="password" placeholder="Verify Password" oninput="verify()" id="vpass">';
		$site .= '<input type="submit" value="Create Account" name="create" disabled id="submit">';
		$site .= '<input type="reset" value="Reset Form" name="create">';
		$site .= '<p id="verify" class="hide"></p>'; 
	$site .= '</form>';
	$site .= '<div class="error">'.createError($ec).'</div>';
	$site .= '<img src="img/create.png" alt="coyote" class="center">';
	return $site;
}

function createError($ec)
{
	$error = '';

	switch($ec)
	{
		case '0':
			$error .= '';
			break;
		case '1':
			$error = 'Please Fill in all Fields';
			break;
		case 'user':
			$error .= 'That Username Has Already Been Used';
	}
	return $error;
}

/***************************************************************CREATE THE ACCOUNT IN THE DB*********************************************/
function intoDB($username, $password)
{
	$conn = mysqli_connect(HOST, USER, PASS, BASE);
			
	// Create Command
	$sql = 'SELECT * FROM `user1`';
	
	$results = mysqli_query($conn, $sql) or die("Something's wrong: ".mysqli_connect_error());
	while($final = mysqli_fetch_array($results, MYSQLI_ASSOC))
	{
		if ($username == $final['username'])
		{
			return 'user';
		}
	}
	$salt1 = hash("SHA256", $username);
	$salt2 = hash("SHA512", $username.$username.$username);
	$password = $salt1.$password.$salt2;
	$password = hash("SHA512",$password);
	$insert = 'INSERT INTO `user1`(`username`, `password`) VALUES ("'.$username.'", "'.$password.'")';
	mysqli_query($conn, $insert);
	return 'granted';
}
}

?>