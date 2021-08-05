<?php
	include_once("includes/all.php");
	include_once("includes/navigation.php");
	include_once("includes/buildproduct.php");
	$filename = basename(__FILE__);

?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo title($filename)?></title>
		<meta charset="UTF-8" />
		<link type="text/css" rel="stylesheet" href="css/style.css">
		<link type="text/css" rel="stylesheet" href="css/product.css">
		<link href="img/icon.jpg" rel="icon" type="image/x-icon">
		<script src="js/script.js"></script>
	</head>
	<body>
		<nav>
			<?php
				echo navi($filename); 
			?>
		</nav>
		<main>
			<?php
				$product = new product();
				echo $product->buildProduct();
			?>
		</main>
	</body>
</html>
