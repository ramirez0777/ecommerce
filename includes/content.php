<?php
/***********************************************CONSTANTS*************************************************************************/
	if ($_SERVER['HTTP_HOST'] == 'localhost')
	{
		define("HOST", "localhost");
		define("USER", "root");
		define("PASS", "1550");
		define("BASE", "less-insecure");
	}
	else
	{
		define("HOST", "sql107.freesite.vip");
		define("USER", "frsiv_25076911");
		define("PASS", "Not1550");
		define("BASE", "frsiv_25076911_lessinsecure");
	}
	
/***********************************************************************************************************************************/
/***********************************************************************************************************************************/
/***********************************************************************************************************************************/
/******************************************** INDEX PAGE **********************************************************/
/***********************************************************************************************************************************/
/***********************************************************************************************************************************/

/*****************************BUILD INDEX PAGE*****************************************************************/
function index()
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
/***********************************************************************************************************************************/
/***********************************************************************************************************************************/
/***********************************************************************************************************************************/




/***********************************************************************************************************************************/
/***********************************************************************************************************************************/
/******************************************** CREATE ACCOUNT PAGE ******************************************************************/
/***********************************************************************************************************************************/
/***********************************************************************************************************************************/

/**************************************************DECIDES WHAT TO SHOW DEPENDING IF YOU'RE LOGGED IN********************************/
function createAccount()
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

/***********************************************************************************************************************************/
/***********************************************************************************************************************************/
/***********************************************************************************************************************************/




/***********************************************************************************************************************************/
/***********************************************************************************************************************************/
/******************************************** CATALOG PAGE ******************************************************************/
/***********************************************************************************************************************************/
/***********************************************************************************************************************************/

function catalog()
{
	$site = '';
	$id = 1;
	$conn = mysqli_connect(HOST, USER, PASS, BASE);
	$sql = 'SELECT * FROM `product`';
	$results = mysqli_query($conn, $sql) or die("Something's wrong: ".mysqli_connect_error());
	if(!isset($_SESSION['loggedIn'])){$site .= '<h1>Please <a href="index.php" class="login">Login</a> To Shop</h1>';}
	$site .= '<div class="main-wrap">';
	while($final = mysqli_fetch_array($results, MYSQLI_ASSOC))
	{
		$site .= '<div class="wrap">';
			$site .= '<img src="img/'.$final['image'].'" alt="Product Picture" class="product-pic">';
			$site .= '<div class="text-wrap">';
				$site .= '<div class="name">'.$final['name'].'</div>';
				$site .= '<div class="description">'.$final['description'].'</div>';
				$site .= '<div class="price">$'.$final['price'].'</div>';
				if(isset($_SESSION['loggedIn'])){$site .= '<a class="link" href="product.php?id='.$id.'">View Product</a>';}
			$site .= '</div>';
		$site .= '</div>';
		$id++;
	}
	$site .= '</div>';
	return $site;
}


/***********************************************************************************************************************************/
/***********************************************************************************************************************************/
/***********************************************************************************************************************************/

/***********************************************************************************************************************************/
/***********************************************************************************************************************************/
/************************************************** PRODUCTS PAGE ******************************************************************/
/***********************************************************************************************************************************/
/***********************************************************************************************************************************/

function product()
{
	$conn = mysqli_connect(HOST, USER, PASS, BASE);


	$site = '';
	$id = $_GET['id'];
	if(isset($_GET['id']) & $_GET['id'] < 25 & $_GET['id'] > 0)
	{
		$sql = 'SELECT * FROM `product` WHERE `id` = '.$_GET['id'].'';
		$results = mysqli_query($conn, $sql) or die("Something's wrong: ".mysqli_connect_error());
		while($final = mysqli_fetch_array($results, MYSQLI_ASSOC))
		{

			$site .= '<div class="main-wrap">';
				$site .= '<div class="wrap">';
					$site .= '<img src="img/'.$final['image'].'" alt="Product Picture" class="product-pic">';
					$site .= '<div class="text-wrap">';
						$site .= '<div class="name">'.$final['name'].'</div>';
						$site .= '<div class="description">'.$final['description'].'</div>';
						$site .= '<div class="price">$'.$final['price'].'</div>';
						$site .= '<form method="post" action="?id='.$id.'&s=true">';
							$site .= '<input type="hidden" value="'.$_GET['id'].'" name="id">';
							$site .= orderNums();
							$site .= '<input type="submit" value="Add To Cart" name="sub" class="add">';
							if(isset($_GET['s'])){$site .= '<div class="green">That has been added to your Cart</div>';}
						$site .= '</form>';
					$site .= '</div>';
				$site .= '</div>';
			$site .= '</div>';
		}
		
		$site .= '<img src="img/order.jpg" alt="order" class="pic">';
	}
	else
	{
		$site .= '<h1>Please Return To The Products Page to Select an Item</h1>';
		$site .= '<img src="img/error.webp" alt="error" class="error-pic">';
	}
	
	if(isset($_POST['sub'])) //button clicked
	{
		if(!isset($_SESSION['product-id']))
		{

				$_SESSION['product-id'] = array($_POST['id']);
				$_SESSION['product-qty'] = array($_POST['qty']);
				$_SESSION['product-price'] = array(DBinfo('price', $_POST['id']));
				$_SESSION['product-name'] = array(DBinfo('name', $_POST['id']));

		}
		else
		{
			if(!in_array($_POST['id'], $_SESSION['product-id']))
			{
			array_push($_SESSION['product-id'], $_POST['id']);
			array_push($_SESSION['product-qty'], $_POST['qty']);
			array_push($_SESSION['product-price'], DBinfo('price', $_POST['id']));
			array_push($_SESSION['product-name'], DBinfo('name', $_POST['id']));
			}
			else
			{
				$position = array_search($_POST['id'], $_SESSION['product-id']);
				$_SESSION['product-qty'][$position] = $_POST['qty']; 
			}
		}
	}
	else
	{
		NULL;
	}
	return $site;
}

/****************************************************MAKE A DROP DOWN LIST FOR ORDERING NUMBER**********************************/
function orderNum($qty = '', $whichbox = '')
{
	$form = '';
	$form .= '<select name="qty'.$whichbox.'" class="num">';
		for($x = 0; $x < 21; $x++)
		{
			$form .= '<option value="'.$x.'"';
			if($qty == $x) {$form .= 'selected';}
			$form .= '>'.$x.'</option>';
		}
	$form .= '</select>';
	return $form;
}
function orderNums($qty = '', $whichbox = '')
{
	$form = '';
	$form .= '<select name="qty'.$whichbox.'" class="num">';
		for($x = 1; $x < 21; $x++)
		{
			$form .= '<option value="'.$x.'"';
			if($qty == $x) {$form .= 'selected';}
			$form .= '>'.$x.'</option>';
		}
	$form .= '</select>';
	return $form;
}

function DBinfo($info, $id)
{
	$conn = mysqli_connect(HOST, USER, PASS, BASE);
	$sql = 'SELECT * FROM `product` WHERE `id` = '.$id.' LIMIT 1';
	$results = mysqli_query($conn, $sql) or die("Something's wrong: ".mysqli_connect_error());
	
	while($final = mysqli_fetch_array($results, MYSQLI_ASSOC))
	{
		if($info == 'price')
		{
			$value = $final['price'];
		}
		elseif($info == 'name')
		{
			$value = $final['name'];
		}
	}
	return $value;
	// print_r($value);
}

/***********************************************************************************************************************************/
/***********************************************************************************************************************************/
/***********************************************************************************************************************************/
/***********************************************************************************************************************************/

/***********************************************************************************************************************************/
/***********************************************************************************************************************************/
/************************************************** CART PAGE ******************************************************************/
/***********************************************************************************************************************************/
/***********************************************************************************************************************************/
function cart()
{
	$site = '';
	if(isset($_SESSION['loggedIn']))//logged in
	{
		if(!empty($_SESSION['product-id']))
		{
			if(!isset($_POST['buy']))
			{	
				if(!isset($_POST['update']))
				{
					$site .= '<form method="post">';
					$site .= '<div class="main-wrap">';
					$finaltotal = 0;
					for($x = 0; $x < sizeof($_SESSION['product-id']); $x++)
					{
						if($_SESSION['product-qty'][$x] != 0)
						{
							$total = $_SESSION['product-qty'][$x] * $_SESSION['product-price'][$x];
							$finaltotal += $total;
							// print_r($total);
							$site .= '<div class="wrap">';
								$site .= '<img src="img/product/'.$_SESSION['product-id'][$x].'.jpg" alt="product picture" class="product-pic">';
								$site .= '<div class="text-wrap">';
									$site .= '<div class="name">'.$_SESSION['product-name'][$x].'</div>';
									$site .= '<div class="price">$'.$_SESSION['product-price'][$x].'</div>';
									$site .= '<div class="description">You have '.$_SESSION['product-qty'][$x].' in your cart. The total amount for this product is $'.$total.'</div>';
									
										$site .= '<input type="hidden" value="'.$_SESSION['product-id'][$x].'" name="id">';
										// $site .= '<input value="'.$_SESSION['product-qty'][$x].'" name="qty'.$x.'" type="text">';
										$site .= orderNum($_SESSION['product-qty'][$x], $x);
								$site .= '</div>';
							$site .= '</div>';
						}	
					}
					
					
					$site .= '</div>';
					$site .= '<div><h2>Your Final Total is: $'.$finaltotal.'</h2></div>';
					$site .= '<input type="hidden" name="total" value="'.$finaltotal.'">';
					$site .= '<input type="submit" value="Update Cart" name="update" class="link">';
					$site .= '<input type="submit" value="Place Order" name="buy" class="link">';
					$site .= '</form>';
				}
				else // Update cart button pressed
				{
					for($x = 0; $x < sizeof($_SESSION['product-qty']); $x++)
					{
						
						if($_SESSION['product-qty'][$x] != 0)
						{
							$post = 'qty'.$x;
							$_SESSION['product-qty'][$x] = $_POST[$post];
						}
						else
						{
							array_splice($_SESSION['product-qty'], $x);
							array_splice($_SESSION['product-id'], $x);
							array_splice($_SESSION['product-price'], $x);
							array_splice($_SESSION['product-name'], $x);
						}
					}
					$site .= '<form method="post">';
					$site .= '<div class="main-wrap">';
					$finaltotal = 0;
					for($x = 0; $x < sizeof($_SESSION['product-id']); $x++)
					{
						if($_SESSION['product-qty'][$x] != 0)
						{
							$total = $_SESSION['product-qty'][$x] * $_SESSION['product-price'][$x];
							$finaltotal += $total;
							$site .= '<div class="wrap">';
								$site .= '<img src="img/product/'.$_SESSION['product-id'][$x].'.jpg" alt="product picture" class="product-pic">';
								$site .= '<div class="text-wrap">';
									$site .= '<div class="name">'.$_SESSION['product-name'][$x].'</div>';
									$site .= '<div class="price">$'.$_SESSION['product-price'][$x].'</div>';
									$site .= '<div class="description">You have '.$_SESSION['product-qty'][$x].' in your cart. The total amount for this product is $'.$total.'</div>';
									
										$site .= '<input type="hidden" value="'.$_SESSION['product-id'][$x].'" name="id">';
										// $site .= '<input value="'.$_SESSION['product-qty'][$x].'" name="qty'.$x.'" type="text">';
										$site .= orderNum($_SESSION['product-qty'][$x], $x);
								$site .= '</div>';
							$site .= '</div>';
						}
					}
					
					
					$site .= '</div>';
					$site .= '<div><h2>Your Final Total is: $'.$finaltotal.'</h2></div>';
					$site .= '<input type="hidden" name="total" value="'.$finaltotal.'">';
					$site .= '<input type="submit" value="Update Cart" name="update" class="link">';
					$site .= '<input type="submit" value="Place Order" name="buy" class="link">';
					$site .= '</form>';
				}		
			}
			else
			{
				$site .= '<h1>Your Purchase has been Processed</h1>';
				$site .= '<h1>Your total is $'.$_POST['total'].'</h1>';
				for($x = 0; $x < sizeof($_SESSION['product-id']); $x++)
				{
					$site .= '<div class="description-1">You Ordered <span class="blue">'.$_SESSION['product-qty'][$x].'</span> <span class="green">'.$_SESSION['product-name'][$x].'</span>';
				}
				$_SESSION['product-id'] = array();
				$_SESSION['product-qty'] = array();
				$_SESSION['product-price'] = array();
				$_SESSION['product-name'] = array();
			}
		}
		else
		{
			$site .= '<h1>Your Cart is Empty</h1>';
			$site .= '<h1>Go to the <a href="catalog.php">Products</a> Page to add to it</h1>';
		}
	}
	else
	{
		$site .= '<h1>Please <a href="index.php">Login</a> to Purchase Products</h1>';
	}
		// var_dump($_SESSION['product-id']);
	 // var_dump($_SESSION['product-qty']);
	 // var_dump($_SESSION['product-price']);
	 // var_dump($_SESSION['product-name']);
	return $site;
}


?>