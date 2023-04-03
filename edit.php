<?php

/*******w******** 
    
    Name: Cheng Wu
    Date: 2023-03-18
    Description: Update or delete a listing 

****************/

require('connect.php');
require('authenticate.php');

    function file_upload_path($original_filename, $upload_subfolder_name = 'uploads') {
       $current_folder = dirname(__FILE__);
       
       // Build an array of paths segment names to be joins using OS specific slashes.
       $path_segments = [$current_folder, $upload_subfolder_name, basename($original_filename)];
       
       // The DIRECTORY_SEPARATOR constant is OS specific.
       return join(DIRECTORY_SEPARATOR, $path_segments);
    }


    // file_is_an_image() - Checks the mime-type & extension of the uploaded file for "image-ness".
    function file_is_an_image($temporary_path, $new_path) {
        $allowed_mime_types      = ['image/gif', 'image/jpeg', 'image/png'];
        $allowed_file_extensions = ['gif', 'jpg', 'jpeg', 'png'];
        
        $actual_file_extension   = pathinfo($new_path, PATHINFO_EXTENSION);
        $actual_mime_type = getimagesize($temporary_path);
        if (!$actual_mime_type){
            return false;
        }
        $actual_mime_type = $actual_mime_type['mime'];
        
        $file_extension_is_valid = in_array($actual_file_extension, $allowed_file_extensions);
        $mime_type_is_valid      = in_array($actual_mime_type, $allowed_mime_types);
        
        return $file_extension_is_valid && $mime_type_is_valid;
    }
    
    $image_upload_detected = isset($_FILES['image']) && ($_FILES['image']['error'] === 0);
    $upload_error_detected = isset($_FILES['image']) && ($_FILES['image']['error'] > 0);

if (isset($_GET['id'])){
    if (filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT)){
        $id = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
        $query = "SELECT animallisting.*, animalphoto.path 
          FROM animallisting 
          LEFT JOIN animalphoto ON animallisting.id = animalphoto.animal_id 
          WHERE animallisting.id = :id";
        $statement = $db->prepare($query);

        $statement->bindValue('id', $id, PDO::PARAM_INT);
        $statement->execute();

        $row = $statement->fetch();          
    }
   else{
        header("Location: index.php");
        exit;   
   }
}

if(isset($_POST['command2'])){

        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

        $query = "SELECT path
                  FROM animalphoto WHERE animal_id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $id);

        $statement->execute();
        $row = $statement->fetch();
        unlink($row['path']);

        $query = "DELETE FROM animallisting WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $id);

        $statement->execute();


        header("Location: index.php");
        exit;        
}

if(isset($_POST['command1'])){
    if(!empty(trim($_POST['name'])) && !empty(trim($_POST['age'])) && 
        !empty(trim($_POST['sex'])) && !empty(trim($_POST['breed']))){
        $name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $age = filter_input(INPUT_POST, 'age', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $sex = filter_input(INPUT_POST, 'sex', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $breed = filter_input(INPUT_POST, 'breed', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

        $query = "UPDATE animallisting SET name = :name, age = :age, sex = :sex, breed = :breed 
                 WHERE id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':name', $name);
        $statement->bindValue(':age', $age);
        $statement->bindValue(':sex', $sex);
        $statement->bindValue(':breed', $breed);
        $statement->bindValue(':id', $id, PDO::PARAM_INT);

        $statement->execute();

        $animal_id = $id;

    if ($image_upload_detected) { 
        $image_filename        = $_FILES['image']['name'];
        $temporary_image_path  = $_FILES['image']['tmp_name'];
        $new_image_path        = file_upload_path($image_filename);
        
        if (file_is_an_image($temporary_image_path, $new_image_path)) {
            move_uploaded_file($temporary_image_path, $new_image_path);
            
            $query = "INSERT INTO animalphoto(path, animal_id)
                        VALUES(:path, :animal_id)";
            $statement = $db->prepare($query);

            $statement ->bindValue(':path', join(DIRECTORY_SEPARATOR, ['uploads', basename($_FILES['image']['name'])]));
            $statement ->bindValue(':animal_id', $animal_id);

            $statement->execute();       
        } else {
            header("Location: updateImgErrorMsg.php?id=$id");
            exit;           
        }
    }

    if(isset($_POST['deleteimg']) && !empty($_POST['deleteimg'])){
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);

        $query = "SELECT path
                  FROM animalphoto WHERE animal_id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $id);

        $statement->execute();
        $row = $statement->fetch();
        unlink($row['path']);

        $query = "DELETE FROM animalphoto WHERE animal_id = :id";
        $statement = $db->prepare($query);
        $statement->bindValue(':id', $id);

        $statement->execute();       
    }  
        header("Location: index.php");
        exit;             
    }

    else{
        $id = filter_input(INPUT_POST, 'id', FILTER_SANITIZE_NUMBER_INT);
        header("Location: noTextInputErrorMsg.php?id=$id");
        exit;       
    }
 
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="main.css" type="text/css">
    <title>Edit this Post</title>
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
            <form action="edit.php" method="post" enctype="multipart/form-data">
                <fieldset>
                    <legend>Edit Listing Post</legend>
                    <?php if($row['path']): ?>
                       <img src="<?= $row["path"] ?>" alt="<?= $row["name"] ?>">
                       <div id="checkbox">
                       <input type="checkbox" id="deleteimg" name="deleteimg" class="inline-checkbox" value="isChecked">
                       <label for="deleteimg" class="inline-label">Delete the image</label>                           
                       </div>

                       
                    <?php endif ?>
                    <ul>
                        <li>
                            <label for="image">Image:</label>
                            <input type="file" name="image" id="image">
                        </li>                          
                        <li>
                            <label for="name">Name:</label>
                            <input type="text" name="name" id="name" value="<?= $row['name'] ?>
                            ">                   
                        </li>
                        <li>
                            <label>Age:</label>
                            <input type="text" name="age" id="age" value="<?= $row['age'] ?>
                            ">                     
                        </li>
                        <li>
                            <label>Sex:</label>
                            <input type="text" name="sex" id="sex" value="<?= $row['sex'] ?>
                            ">                     
                        </li>               
                        <li>
                            <label>Breed:</label>
                            <input type="text" name="breed" id="breed" value="<?= $row['breed'] ?>
                            ">                     
                        </li>

                    </ul>
                    <p>
                        <input type="hidden" name="id" value="<?= $row['id'] ?>">
                        <input type="submit" name="command1" value="Update">
                        <input type="submit" name="command2" value="Delete" onclick="return confirm('Are you sure you wish to delete this post?')">
                    </p>                                  
                </fieldset>
            </form>
        </div>
        <div id="footer"> Copywrong 2023 - No Rights Reserved </div>        
    </div>
</body>
</html>