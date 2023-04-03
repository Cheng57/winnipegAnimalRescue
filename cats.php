<?php

/*******w******** 
    
    Name: Cheng Wu
    Date: 2023-03-18
    Description: cat category page

****************/

require('connect.php');
    
$query = "SELECT animallisting.*, animalphoto.path 
          FROM  animalphoto
          JOIN animallisting ON animallisting.id = animalphoto.animal_id
          JOIN animalcategory ON animallisting.category_id = animalcategory.id
          WHERE animalcategory.name = 'cat'
          ORDER BY animallisting.id DESC 
          LIMIT 10";

    $statement = $db->prepare($query);

    $statement->execute();
?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="main.css" type="text/css">
	<title>Welcome to Winnipeg Animal Rescue</title>
</head>
<body>
    <div id="wrapper">
       <div id="header">
            <h1>
            	<a href="index.php">Winnipeg Animal Rescue - Index</a>
        	</h1>           
       </div>
       <div id="nav">
           <ul id="menu">
               <li>
                   <a href="index.php" class="active">Home</a>
               </li>
               <li>
                   <a href="post.php">New Post</a>
               </li>
               <li>
                    <a href="index.php">All</a>
               </li>
               <li>
                    <a href="cats.php">Cats</a>
               </li>
               <li>
                    <a href="dogs.php">Dogs</a>
               </li>
           </ul>
       </div>
       <?php if($statement->rowcount() == 0): ?>
            <h1>No post found.</h1>
       <?php else: ?>
       <div id="all_listings">
            <?php while($row = $statement->fetch()): ?>
                <div class="listing">
                    <?php if($row['path']): ?>
	                   <img src="<?= $row["path"] ?>" alt="<?= $row["name"] ?>">
                    <?php endif ?>
	                <ul>
	                  	<li id="name"><h1><?= $row["name"] ?></h1></li>
	                  	<li>Age: <?= $row["age"] ?></li>
	                  	<li>Sex: <?= $row["sex"] ?></li>
	                  	<li>Breed: <?= $row["breed"] ?></li>                 	
	                </ul>
                    <a href="edit.php?id=<?= $row["id"] ?>">--Edit--</a>   
              	</div>
            <?php endwhile ?>
       </div>
       <?php endif ?>
	   <div id="footer"> Copywrong 2023 - No Rights Reserved </div>	       
	</div>
</body>
</html>