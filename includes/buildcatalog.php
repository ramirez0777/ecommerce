<?php
class catalog
{
	function buildCatalog()
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
}
?>