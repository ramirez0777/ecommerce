<?php
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
	
?>

<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Add a Product</title>
		<meta charset="UTF-8"/>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link type="text/css" rel="stylesheet" href="css/apstyle.css">
		<link href="../img/icon.jpg" rel="icon" type="image/x-icon">
		<script src="js/script.js"></script>
	</head>
	<body>
		<nav>
		</nav>
		<body>
			<form method="post" enctype="multipart/form-data" action="?s=good">
				<input type="text" name="name" placeholder="Product Name">
				<textarea type="textarea" name="description" placeholder="Product Description"></textarea>
				<input type="text" name="price" placeholder="Price $$">
				<input type="text" name="id" placeholder="Product ID">
				<input type="file" name="fileToUpload" id="fileToUpload">
				<input type="submit" name="sub">
			</form>
			
			<?php
				if(isset($_POST['sub']))
				{
					$sql = 'INSERT INTO `product`(`name`, `description`, `image`, `price`) VALUES ("'.$_POST['name'].'","'.$_POST['description'].'","product/'.$_POST['id'].'.jpg","'.$_POST['price'].'")';
					$conn = mysqli_connect(HOST, USER, PASS, BASE);
					mysqli_query($conn, $sql);
					// print_r($sql);
					
					$placeForPic = '../img/product/';
					$filename = $placeForPic . basename($_FILES['fileToUpload']['name']);
					move_uploaded_file($_FILES['fileToUpload']['tmp_name'], $filename);
				}
			?>
		</body>
	</body>
</html>
