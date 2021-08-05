<?php
class product
{
	function buildProduct()
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
	
}
?>