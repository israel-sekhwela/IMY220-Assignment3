<?php
	// See all errors and warnings
	error_reporting(E_ALL);
	ini_set('error_reporting', E_ALL);

	// Your database details might be different
	$mysqli = mysqli_connect("localhost", "root", "", "dbUser");

	$email = isset($_POST["loginName"]) ? $_POST["loginName"] : false;
	$pass = isset($_POST["loginPassw"]) ? $_POST["loginPassw"] : false;
    
?>

<!DOCTYPE html>
<html>
<head>
	<title>IMY 220 - Assignment 3</title>
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
	<link rel="stylesheet" type="text/css" href="style.css" />
	<meta charset="utf-8" />
	<meta name="author" content="Israel Sekhwela">
	<!-- Replace Name Surname with your name and surname -->
</head>
<body>
	<div class="container">
		<?php
			if($email && $pass ){
				$query = "SELECT * FROM tbusers WHERE email = '$email' AND password = '$pass'";
				$res = $mysqli->query($query);
				if($row = mysqli_fetch_array($res)){
					echo 	"<table class='table table-bordered mt-3'>
								<tr>
									<td>Name</td>
									<td>" . $row['name'] . "</td>
								<tr>
								<tr>
									<td>Surname</td>
									<td>" . $row['surname'] . "</td>
								<tr>
								<tr>
									<td>Email Address</td>
									<td>" . $row['email'] . "</td>
								<tr>
								<tr>
									<td>Birthday</td>
									<td>" . $row['birthday'] . "</td>
								<tr>
							</table>";
				
					echo 	"<form action='login.php' method='post' enctype='multipart/form-data'>
								<div class='form-group'>
									<input type='file' class='form-control' name='picToUpload' id='picToUpload' multiple='multiple' /><br/>
									<input type='submit' class='btn btn-standard' value='Upload Image' name='submit' />
                                    <input type='hidden' id='loginPass' class='form-control' name='loginPassw' value='$pass' />
                                    <input type='hidden' id='loginName' class='form-control' name='loginName' value='$email' />
								</div>
						  	</form>";
                    
                    $id = $row["user_id"];
                    echo "<h2> Image Gallery </h2>
                    		<div class='row'>";
	                    		$select_images = "SELECT * FROM tbgallery WHERE user_id = '$id'";
	                    		$res_images = $mysqli->query($select_images);
	                    		while($row = mysqli_fetch_array($res_images)){
	                        		$img = $row["filename"];
	                        		echo "<div class='col-3' style='background-image: url(gallery/$img);'></div>";
	                    		}
                    
                    echo    "</div>";
                    
                    // uploading images
                    if (isset($_FILES["picToUpload"])){
                        $target_dir = "gallery/";
                        $uploadFile = $_FILES["picToUpload"];
                        $temp_file = $uploadFile["name"];
                        $target_file = $target_dir . basename($uploadFile["name"]);
                        if (($uploadFile["type"] == "image/jpeg" || $uploadFile["type"] == "image/jpg") && $uploadFile["size"] < 1048576){
                            if (isset($_POST["submit"])){
                                $check = getimagesize($uploadFile["tmp_name"]);
                                if ($check !== false){
                                    $insert_image = "INSERT INTO tbgallery (user_id, filename) VALUES ('$id', '$temp_file');";
                                    move_uploaded_file($uploadFile["tmp_name"], $target_file);
                                    $res = mysqli_query($mysqli, $insert_image);
                                } 
                            }
                        }
                    }                        
				}
				else{
					echo '<div class="alert alert-danger mt-3" role="alert">
							You are not registered on this site!
						 </div>';
				}
			} 
			else{
				echo '<div class="alert alert-danger mt-3" role="alert">
						Could not log you in
					 </div>';
			}
		?>
	</div>
</body>
</html>