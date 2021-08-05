<?php
	include_once("includes/all.php");
	include_once("includes/content.php");
	include_once("includes/navigation.php");
	include_once("includes/buildindex.php");
	$filename = basename(__FILE__);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo title($filename)?></title>
		<meta charset="UTF-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<link type="text/css" rel="stylesheet" href="css/style.css">
		<link type="text/css" rel="stylesheet" href="css/index.css">
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
				$index = new index();
				echo $index->buildIndex();
			?>
			<div class="main-wrap">
				<img src="img/main.jpg" alt="acme" class="acme-pic">
				<div class="text">
					<p>Welcome to our site. On this site you'll be able to buy a variety of different items of which you can create schemes or plans with. All of these products have been tested by Wiley Coyote from the Looney Tunes. Our resident expert the Road Runner stands behind all his products. So if you're ready to catch your bird, then get to shopping!</p>
				</div>
			</div>
		</main>
	</body>
</html>
