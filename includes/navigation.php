<?php

/**************************************************************BUILD NAVIGATION AUTOMATED************************************************************/
/*
function nav($file)
{
	//if it's the index page create a blank urls array else put the fixed filename into the array
	$file = substr($file, 0, -4);
	if ($file === 'index') {$urls = [];} else { $urls[] = $file;} 
	$fileName = scandir('.');
	
	
	for ($x = 0; $x < sizeof($fileName); $x++)
	{
		if (substr($fileName[$x], -4) == '.php' && $fileName[$x] != $file.'.php' && $fileName[$x] != 'index.php')
		{
			$urls[] =  substr($fileName[$x], 0, -4);
		}
	}
	
	//if logged in takes out pages that shouldn't show
	if ($_SESSION['loggedIn'] == True)
	{
		$dead = array('login', 'create-account');
		$urls = array_diff($urls, $dead);
	}
	else// is not logged in
	{
		$dead = array('cart', 'logout', 'login');
		$urls = array_diff($urls, $dead);
	}

	$nav = '';
	$nav .= '<nav>';
		$nav .= '<ul>';
			$nav .= '<li><a href="index.php">Home</a></li>';
			foreach ($urls as $url)
			{
				//So this will change depending on if you are logged in or not
				if($url !== $file) {$nav .= '<li><a href="'.$url.'.php">'.ucwords(clean($url)).'</a></li>';}
			}
			if ($_SESSION['loggedIn'] == False) {$nav .= '<li><a href="index.php">Login</a></li>';}
		$nav .= '</ul>';
	$nav .= '</nav>';
	
	
	
	return $nav;

}

*/
/***********************************************************FIXES FILES TO NOT SHOW DASHES***FOR DISPLAY ONLY*************************************/

function clean($word)
{

	$reg = '/[^a-zA-Z0-9]/';
	$word = preg_replace($reg, ' ', $word);
	
	return $word;
}

/*******************************************************CREATES TITLE******************************************************************************/
function title($file)
{
	if($file != 'index.php')
	{
		$file = substr($file, 0, -4);
		return ucwords(clean($file));
	}
	else if ($file == 'index.php')
	{
		return 'Home Page';
	}
	else if ($file == 'catalog.php')
	{
		return 'Products Page';
	}
}


//unautomated function to create pages but I have to put less logic into it so rip typing things in by hand
function navi($file)
{
	if(isset($_SESSION['loggedIn'])) //logged in
	{
		$links= array('index.php', 'catalog.php', 'cart.php', 'logout.php');
		$namesofPages = array('Home', 'Products', 'Cart', 'Logout');
	}
	else // not logged in
	{
		$links= array('index.php', 'catalog.php', 'create-account.php');
		$namesofPages = array('Home', 'Products', 'Create Account');
	}
	
	$nav = '';
	$nav .= '<a href="index.php"><img src="img/acme.jpg" alt="logo" class="logo-pic" ></a>';
		$nav .= '<ul class="nav">';
			for($x = 0; $x < sizeof($links); $x++)
			{
				if($links[$x] == $file)//current page
				{
					$nav .= '<li class="active"><a class="nav-links">'.$namesofPages[$x].'</a></li>';
				}
				else
				{
					$nav .= '<li><a href="'.$links[$x].'" class="nav-links">'.$namesofPages[$x].'</a></li>';
				}
			}
			if(!isset($_SESSION['loggedIn'])) //not logged in show Login link
			{
				if($file == 'index.php') //don't allow to be clickable on index page
				{
					$nav .= '<li><a class="nav-links">Login</a></li>';
				}
				else
				{
					$nav .= '<li><a href="index.php" class="nav-links">Login</a></li>';
				}
			}
		$nav .= '</ul>';
	return $nav;
}



?>