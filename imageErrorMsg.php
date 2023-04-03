<?php
	if (isset($_GET['id'])){
	    if (filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)){
	        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);		
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Listing Post</title>
	<link rel="stylesheet" href="main.css" type="text/css">
</head>
<body>


		<div>
			<h1>An error occured while processing your post.</h1>
			<p>Please upload an valid image.</p>
			<a href="edit.php?id=<?=$id ?>">Return Edit</a>		
		</div>
	

	<div id="footer"> Copywrong 2023 - No Rights Reserved </div>
</body>
</html>