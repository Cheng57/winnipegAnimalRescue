<?php

/*******w******** 
    
    Name: Cheng Wu
    Date: 2023-03-18
    Description: Insert data into the database on the server

****************/

require('connect.php');
require('ImageResize.php');
require('ImageResizeException.php');

    // file_upload_path() - Safely build a path String that uses slashes appropriate for our OS.
    // Default upload path is an 'uploads' sub-folder in the current folder.
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

if ($_POST && !empty(trim($_POST['name'])) && 
	!empty(trim($_POST['age'])) && !empty(trim($_POST['sex'])) && !empty(trim($_POST['breed'])) )
{
	$name = filter_input(INPUT_POST, 'name', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$age = filter_input(INPUT_POST, 'age', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$sex = filter_input(INPUT_POST, 'sex', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	$breed = filter_input(INPUT_POST, 'breed', FILTER_SANITIZE_FULL_SPECIAL_CHARS);
	

	$query = "INSERT INTO animallisting(name, age, sex, breed) 
				VALUES(:name, :age, :sex, :breed)";
	$statement = $db->prepare($query);

	$statement->bindValue(':name', $name);
	$statement->bindValue(':age', $age);
	$statement->bindValue(':sex', $sex);
	$statement->bindValue(':breed', $breed);

	$statement->execute();

	$animal_id = $db->lastInsertId();

	if($image_upload_detected){ 
	    $image_filename        = $_FILES['image']['name'];
	    $temporary_image_path  = $_FILES['image']['tmp_name'];
	    $new_image_path        = file_upload_path($image_filename);
	    
	    if(file_is_an_image($temporary_image_path, $new_image_path)){
	        
	        
	        
	       


			

			$image = new \Gumlet\ImageResize($temporary_image_path);

            $image->resizeToWidth(400);
            
            $image->save($new_image_path);	

    	
	        
	        


	        $query = "INSERT INTO animalphoto(path, animal_id)
	                    VALUES(:path, :animal_id)";
	        $statement = $db->prepare($query);

	        $statement ->bindValue(':path', join(DIRECTORY_SEPARATOR, ['uploads', basename($_FILES['image']['name'])]));
	        $statement ->bindValue(':animal_id', $animal_id);

	        $statement->execute();       
	    } else {
            header("Location: imageErrorMsg.php?id=$animal_id");
            exit;	    	
	    }
	}
    	header("Location: index.php");
		exit;  
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
	<?php if (empty(trim($_POST['name']))
		|| empty(trim($_POST['age'])) || empty(trim($_POST['sex'])) || empty(trim($_POST['breed']))): ?> 
		<div>
			<h1>An error occured while processing your post.</h1>
			<p>Please fill up all text input.</p>
			<a href="post.php">Return Post</a>
		</div>
	<?php endif ?>

	<div id="footer"> Copywrong 2023 - No Rights Reserved </div>
</body>
</html>