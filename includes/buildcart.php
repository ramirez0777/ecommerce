<?php
class cart
{
	function buildCart()
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
}
?>