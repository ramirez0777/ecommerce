<?php
	include_once("includes/all.php");
	include_once("includes/navigation.php");
	include_once("includes/content.php");
	include_once("includes/createaccount.php");
	$filename = basename(__FILE__);
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<title><?php echo title($filename)?></title>
		<meta charset="UTF-8" />
		<link type="text/css" rel="stylesheet" href="css/style.css">
		<link type="text/css" rel="stylesheet" href="css/create.css">
		<link href="img/icon.jpg" rel="icon" type="image/x-icon">
		<script src="js/script.js"></script>
		<script src="js/create.js"></script>
	</head>
	<body>
		<nav>
			<?php
				echo navi($filename); 
			?>
		</nav>
		<main>
			<?php
				$site = new createAccount();
				echo $site->buildCreateAccount();
			?>
		</main>
	</body>
</html>
