<?php 

/*******w******** 
    
    Name: Cheng Wu
    Date: 2023-03-18
    Description: Post an animal listing

****************/

require('connect.php');
require('authenticate.php');

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css" type="text/css">
    <title>Animal Listing Post</title>
</head>
	
<body>
	<div id="wrapper">
		<div id="header">
			<h1>
				<a href="index.php">Winnipeg Animal Rescue - New Post</a>
			</h1>
		</div>
		<div id="nav">
			<ul id="menu">
				<li><a href="index.php">Home</a></li>
				<li><a href="post.php">New Post</a></li>
			</ul>
	    </div>
		<div id="all_listings">
			<form action="insert.php" method="post" enctype="multipart/form-data">
				<fieldset>
					<legend>New Listing Post</legend>
					<ul>
						<li>
							<label for="name">Name:</label>
							<input type="text" name="name" id="name">					
						</li>

						<li>
							<label>Age:</label>
							<input type="text" name="age" id="age">						
						</li>
						<li>
							<label>Sex:</label>
							<input type="text" name="sex" id="sex">						
						</li>				
						<li>
							<label>Breed:</label>
							<input type="text" name="breed" id="breed">						
						</li>
						<li>
				         	<label for='image'>Image (.gif/.jpg/.jpeg/.png):</label>
				         	<input type='file' name='image' id='image'>
				         	<input type='submit' name='submit' value='Create'>						
						</li>						
					</ul>				
				</fieldset>
			</form>
		</div>
		<div id="footer"> Copywrong 2023 - No Rights Reserved </div>		
	</div>
</body>
</html>