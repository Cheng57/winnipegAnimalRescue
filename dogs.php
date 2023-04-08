<?php

/*******w******** 
    
    Name: Cheng Wu
    Date: 2023-03-18
    Description: cat category page

****************/

require('connect.php');
    
$query = "SELECT animallisting.*, animalphoto.path 
          FROM  animalphoto
          RIGHT JOIN animallisting ON animallisting.id = animalphoto.animal_id
          JOIN animalcategory ON animallisting.category_id = animalcategory.id
          WHERE animalcategory.name = 'dog'
          ORDER BY animallisting.id DESC 
          LIMIT 10";

    $statement = $db->prepare($query);

    $statement->execute();
?>

       <?php include("header.php"); ?>
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
       <?php include("footer.php"); ?>