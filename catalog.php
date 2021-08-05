<?php
	include_once("includes/all.php");
	include_once("includes/navigation.php");
	include_once("includes/buildcatalog.php");
	$filename = basename(__FILE__);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo title($filename)?></title>
		<meta charset="UTF-8" />
		<link type="text/css" rel="stylesheet" href="css/style.css">
		<link type="text/css" rel="stylesheet" href="css/catalog.css">
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
			$catalog = new catalog();
			echo $catalog->buildCatalog();
		?>
		</main>
	</body>
</html>
