<!--
	Name: Merna Addison
	Email: merna.addison@gmail.com
	Date: 11/18/17
	Course: WDV341 Intro to PHP
-->
<?php
session_start(); // join current session equivalent to cookies in browser
//Only allow a valid user access to this page
if ($_SESSION['validUser'] == "yes") {
		$username = $_SESSION['username'];			
		$userMessage = "Welcome Back $username!";	//Create greeting for VIEW area		
		
	//Setup variables used by the page	
		// field data
		$meal_id = "";
		$meal_mealname = "";
		$photo_name = "";
		$meal_ingredients = "";
		$num_meal_ingredients = 0;
		$meal_directions = "";
		$num_meal_directions = 0;
		$roboTest = "";
		$displayMsg = "";
		$update_meal_id = "";
		$dateTime = new DateTime("now", new DateTimeZone('America/Chicago'));
		$update_date = $dateTime->format('Y-m-d');
		$update_time = $dateTime->format('H:i:s');

		// Error messages
		$meal_mealname_Err = "";
		$meal_ingredients_Err = "";
		$meal_directions_Err = "";	
		
		// flags for sucessful form submission
		$validForm = false;
		
		//for($i = 0; $i < $num_meal_ingredients; $i++){
			//$GLOBALS['$meal_ingredients'.$i] = null;
			//$var = ${'meal_ingredients'.$i};
			//global $$var;
		//}
		//for($i = 0; $i < $num_meal_directions; $i++){
			//$GLOBALS['$meal_directions'.$i] = null;
			//$var = ${'meal_directions'.$i};
			//global $$var;
		//}
	
	if(isset($_POST["submit"]))
	{
		// the form has been sumitted and needs to be processed
		
		// check that photo was uploaded without errors
		if(isset($_FILES['photo']) && $_FILES['photo']['error'] == 0){
			$allowed = array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png");
			$photo_name = $_FILES['photo']['name'];
			//echo $photo_name;
			$photo_type = $_FILES['photo']['type'];
			
			// Verify file extension
			$ext = pathinfo($photo_name, PATHINFO_EXTENSION);
			if(!array_key_exists($ext, $allowed)) die("Error: Please select a valid file format.");
				
			// Verify file type
			if(in_array($photo_type, $allowed)){
				// Check whether file exists before uploading
				if(file_exists("images/".$_FILES['photo']['name'])){
					//echo $_FILES['photo']['name']." already exists.";
				}else{
					move_uploaded_file($_FILES['photo']['tmp_name'], "images/".$_FILES['photo']['name']);
					//echo "Your file was uploaded successfully.";
				}
			}else{
				$displayMsg .= "<h2>Error: there was a problem uploading your file. Please try again.</h2>";
			}
		}else{
			//echo "Error: ".$_FILES['photo']['error'];
		}
		
		$num_meal_ingredients = $_POST['num_meal_ingredients'];
		$num_meal_directions = $_POST['num_meal_directions'];
		$meal_mealname = $_POST['meal_mealname'];
		//echo $num_meal_ingredients;
		//echo $num_meal_directions;
		for($i = 1; $i <= $num_meal_ingredients; $i++){
			//global ${'meal_ingredients'.$i};
			//${'meal_ingredients'.$i} = $_POST['meal_ingredients'.$i];
			//$meal_ingredients.$i = $_POST['meal_ingredients'.$i];
			$var = "meal_ingredients".$i;
			$GLOBALS['meal_ingredients'.$i] = $_POST[$var];
		}
		for($i = 1; $i <= $num_meal_directions; $i++){
			//${'meal_directions'.$i} = $_POST['meal_directions'.$i];
			//global ${'meal_ingredients'.$i};
			$var = "meal_directions".$i;
			$GLOBALS['meal_directions'.$i] = $_POST[$var];
		}
		$update_meal_id = $_POST['update_meal_id'];
		$roboTest = $_POST['robotest'];

		/*	FORM VALIDATION PLAN
		
			FIELD NAME			VALIDATION TESTS & VALID RESPONSES
			Meal Name			Required Field		May not be empty
			
			Meal ingredients	Optional
			Meal directions		Optional
		*/
		
		// Validation Functions
		// Validate name - required and check HTML special characters
		function validateSanitizeMealName($meal_mealname){
			global $validForm, $meal_mealname_Err;	//Use the GLOBAL Version of these variables instead of making them local
			if($meal_mealname==""){
				$meal_mealname_Err = "*Meal name is required";
				$validForm = false;
			}
			// Remove all illegal characters from meal name
			$sanitized_meal_mealname = filter_var($meal_mealname, FILTER_SANITIZE_STRING);
			return $sanitized_meal_mealname;
		}
		
		// Validate Meal Ingredients - remove all illegal characters
		function validateSanitizeMealIngredients($meal_ingredients){
			global $validForm, $meal_ingredients_Err;
			$sanitized_meal_ingredients = filter_var($meal_ingredients, FILTER_SANITIZE_STRING);
			return $sanitized_meal_ingredients;
		}
		
		// Validate Meal Directions
		function validateSanitizeMealDirections($meal_directions){
			global $validForm, $meal_directions_Err;
			$sanitized_meal_directions = filter_var($meal_directions, FILTER_SANITIZE_STRING);
			return $sanitized_meal_directions;
		}
		
		// validate not robot
		if($roboTest){
			$displayMsg = "<h2>*No bots allowed! Email not sent!</h2>";
			$validForm = false;
		}
		
		// Set form valid and run methods to check if anything is invalid
		$validForm = true;
		$meal_mealname = validateSanitizeMealName($meal_mealname); 
		if($meal_mealname == ""){
			$validForm = false;
			$meal_mealname_Err = "*You must enter a name for the recipe.";
		}
		for($i = 1; $i <= $num_meal_ingredients; $i++){
			${'meal_ingredients'.$i} = validateSanitizeMealIngredients(${'meal_ingredients'.$i});
		}
		for($i = 1; $i <= $num_meal_directions; $i++){
			${'meal_directions'.$i} = validateSanitizeMealDirections(${'meal_directions'.$i});
		}
		
		if($validForm)
		{
			$displayMsg = "<h2>All good</h2>";
			try 
			{
				
				require 'dbConnectPDO.php'; // connect to the database
	
				if($update_meal_id!=""){
						$sql = "UPDATE miaddison_meals.meals SET";
						//$sql = "UPDATE meals.meals SET";
						$sql .= " mealname=:mealName,";
						if($photo_name!=""){
							$sql .= " photo=:mealPhoto,";
						}
						$sql .= " update_date=:updateDate,";
						$sql .= " update_time=:updateTime";
						$sql .=" WHERE id=:update_meal_id";

						//PREPARE the SQL statement
						$stmt = $conn->prepare($sql);
						$stmt->bindParam(':mealName', $meal_mealname);
						if($photo_name!=""){
							$stmt->bindParam(':mealPhoto', $photo_name);
						}
						$stmt->bindParam(':updateDate', $update_date);
						$stmt->bindParam(':updateTime', $update_time);
						$stmt->bindParam(':update_meal_id', $update_meal_id);
						$stmt->execute();
					
					for($i = 1; $i <= $num_meal_ingredients; $i++){
						$sql2 = "UPDATE miaddison_meals.ingredients SET";
						//$sql2 = "UPDATE meals.ingredients SET";
						$sql2 .= " ingredient=:mealIngredients";
						$sql2 .= " WHERE id=:update_meal_id AND num=:number";
						
						$stmt2 = $conn->prepare($sql2);
						$stmt2->bindParam(':mealIngredients', ${'meal_ingredients'.$i});
						$stmt2->bindParam(':number', $i);
						$stmt2->bindParam(':update_meal_id', $update_meal_id);
						$stmt2->execute();
					}
					
					for($i = 1; $i <= $num_meal_directions; $i++){
						$sql3 = "UPDATE miaddison_meals.directions SET";
						//$sql3 = "UPDATE meals.directions SET";
						$sql3 .= " direction=:mealDirections";
						$sql3 .= " WHERE id=:update_meal_id AND step=:number";
						$stmt3 = $conn->prepare($sql3);
						$stmt3->bindParam(':number', $i);
						$stmt3->bindParam(':mealDirections', ${'meal_directions'.$i});
						$stmt3->bindParam(':update_meal_id', $update_meal_id);
						$stmt3->execute();
					}
					
					$displayMsg = "<h2>Your recipe has been updated.</h2>";
					$displayMsg .= "<h3>Click <a href='adminViewMeal.php?meal_id=$update_meal_id'>here</a> to view your updated recipe.</h3>";
					$displayMsg .= "<h3>You can click <a href='adminSelectMeals.php'>here</a> to view all recipes.</h3>";
					
				}else{
					$sql = "INSERT INTO miaddison_meals.meals ( mealname, photo, update_date, update_time ) VALUES (:mealName, :photoName, :updateDate, :updateTime)";
					//$sql = "INSERT INTO meals.meals ( mealname, photo ) VALUES (:mealName, :photoName)";
				
					//PREPARE the SQL statement
					$stmt = $conn->prepare($sql);
					$stmt->bindParam(':mealName', $meal_mealname);
					$stmt->bindParam(':photoName', $photo_name);
					$stmt->bindParam(':updateDate', $update_date);
					$stmt->bindParam(':updateTime', $update_time);
					$stmt->execute();
					
					$sql4 = "SELECT id FROM miaddison_meals.meals WHERE mealname= :mealName";
					//$sql4 = "SELECT id FROM meals.meals WHERE mealname= :mealName";
					//echo(" SQL4: ".$sql4);
					//echo(" prepare statement ");
					
					//PREPARE the SQL statement
					$stmt4 = $conn->prepare($sql4);
					$stmt4->bindParam(':mealName', $meal_mealname);
					$stmt4->execute();
					 
					// get new meals id
					$row4=$stmt4->fetch(PDO::FETCH_ASSOC);
					$meal_id = $row4['id'];
					//echo(" meal id: ".$meal_id);
					for($mi = 1; $mi <= $num_meal_ingredients; $mi++){
						$sql2 = "INSERT INTO miaddison_meals.ingredients (";
						//$sql2 = "INSERT INTO meals.ingredients (";
						$sql2 .= " id, num, ingredient";
						$sql2 .= ") VALUES (:id, :num, :mealIngredients)";
						$stmt2 = $conn->prepare($sql2);
						$stmt2->bindParam(':id', $meal_id);
						$stmt2->bindParam(':num', $mi);
						$stmt2->bindParam(':mealIngredients', ${'meal_ingredients'.$mi});
						$stmt2->execute();
						//echo $sql2;
					}
					//echo $num_meal_directions;
					for($di = 1; $di <= $num_meal_directions; $di++){
						$sql3 = "INSERT INTO miaddison_meals.directions (";
						//$sql3 = "INSERT INTO meals.directions (";
						$sql3 .= " id, step, direction";
						$sql3 .= ") VALUES (:id, :step, :mealDirections)";
						$stmt3 = $conn->prepare($sql3);
						$stmt3->bindParam(':id', $meal_id);
						$stmt3->bindParam(':step', $di);
						$stmt3->bindParam(':mealDirections', ${'meal_directions'.$di});
						$stmt3->execute();
						//echo $sql3;
					}
					
					$displayMsg = "<h2>Your recipe has been added.</h2>";
					$displayMsg .= "<h3>Click <a href='adminViewMeal.php?meal_id=$meal_id'>here</a> to view your updated recipe.</h3>";
					$displayMsg .= "<h3>You can click <a href='adminSelectMeals.php'>here</a> to view all recipes.</h3>";
				
				}
				
				
				
			}
			
			catch(PDOException $e)
			{
				$displayMsg = "<h2>There has been a problem. The system administrator has been contacted. Please try again later.</h2>";
				
				error_log($e->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
				
				error_log(var_dump(debug_backtrace()));
				
			}
		}
		else
		{
			$displayMsg = "<h2>Something went wrong</h2>";
			$displayMsg .= "<h2>Update Recipe</h2>";
			// Meal name input
			$displayMsg .= "<form name='mealForm' method='post' action='adminMealForm.php' enctype='multipart/form-data'>";
			$displayMsg .= "<p>&nbsp</p><table id='dataTable'>";
			$displayMsg .= "<p><tr>";
			$displayMsg .= "<label>Meal Name: ";
			$displayMsg .= "<input type='text' name='meal_mealname' id='meal_mealname' value='$meal_mealname'><span class='error' style='color:red; padding-left:2em'>$meal_mealname_Err</span>";
			$displayMsg .= "</label>";
			$displayMsg .= "</tr></p>";
			
			$displayMsg .= "<p><tr>";
			$displayMsg .= "<label>Meal Photo:";
			$displayMsg .= "<input type='file' name='photo' id='photo'><span class='error' style='color:red; padding-left:2em'></span>";
			$displayMsg .= "</label>";
			$displayMsg .= "</tr></p>";
					// ingredients
					//$displayMsg .= "<input type='button' value='Add Ingredient' onClick='addRow('dataTable')' />"; 
  					//$displayMsg .= "<input type='button' value='Remove Ingredient' onClick='deleteRow('dataTable')/>"; 
  					//$displayMsg .= "<div style = 'font-size: .5em;'>(All actions apply only to entries with check marked check boxes only.)</div>";

			//$count = 0;
			//while($row2=$stmt2->fetch(PDO::FETCH_ASSOC)){
			for($i = 1; $i <= $num_meal_ingredients; $i++){
				//$count++;
				//$meal_ingredients = $row2['ingredient'];
				$displayMsg .= "<p><tr>";						
				//$displayMsg .= "<input type='checkbox' name='chkIng[]' style = 'float:left;'/>";
				$displayMsg .= "<label style = 'float:left;'>Ingredient $i: </label></tr><br>";
				$displayMsg .= "<tr><textarea name='meal_ingredients$i' id='meal_ingredients$i' cols='45' rows='5'>${'meal_ingredients'.$i}</textarea><span class='error' style='color:red; padding-left:2em'>$meal_ingredients_Err</span>";
				//$displayMsg .= "</p>";
				$displayMsg .= "</tr></p>";
			}
			// directions
			//$count = 0;
			//while($row3=$stmt3->fetch(PDO::FETCH_ASSOC || count)){
			for($i = 1; $i <= $num_meal_directions; $i++){
				//$count++;
				//$meal_directions = $row3['direction'];
				$displayMsg .= "<p><tr>";
				//$displayMsg .= "<input type='checkbox' name='chkDir[]' style = 'float:left;'/>";
				$displayMsg .= "<label style = 'float:left;'>Step $i: </label></tr><br>";
				$displayMsg .= "<tr><textarea name='meal_directions$i' id='meal_directions$i' cols='45' rows='5'>${'meal_directions'.$i}</textarea><span class='error' style='color:red; padding-left:2em'>$meal_directions_Err</span>";
				$displayMsg .= "</tr></p>";
			}
			// robotest and meal id
			$displayMsg .= "<input type='hidden' name='update_meal_id' id='update_meal_id' value='$update_meal_id'>";
			$displayMsg .= "<input type='hidden' name='num_meal_ingredients' id='num_meal_ingredients' value='$num_meal_ingredients;'>";
			$displayMsg .= "<input type='hidden' name='num_meal_directions' id='num_meal_directions' value='$num_meal_directions'>";
			$displayMsg .= "<input type='hidden' name='robotest' id='robotest'>";
			$displayMsg .= "<p>";
			// buttons
			$displayMsg .= "<input type='submit' name='submit' id='submit' value='Submit'>";
			$displayMsg .= "<input type='button' name='button2' id='button2' value='Reset' onClick = resetForm()>";
			$displayMsg .= "</p></table></form>";
		}
	}
	else
	{
		try{
			//user has not seen form yet
			if(isset($_GET['meal_id'])){
				$update_meal_id=$_GET['meal_id']; // user updating 
			}
			if($update_meal_id != ""){
				require 'dbConnectPDO.php'; // connect to the database
				
				// Remote SQL 
				$sql = "SELECT mealname, photo FROM miaddison_meals.meals WHERE id=:id";
				$sql2 = "SELECT ingredient FROM miaddison_meals.ingredients WHERE id=:id";
				$sql3 = "SELECT direction FROM miaddison_meals.directions WHERE id=:id";
				
				// Local SQL
				//$sql = "SELECT mealname, photo FROM meals.meals WHERE id=:id";
				//$sql2 = "SELECT ingredient FROM meals.ingredients WHERE id=:id";
				//$sql3 = "SELECT direction FROM meals.directions WHERE id=:id";
				
				//PREPARE the SQL statement
				$stmt = $conn->prepare($sql);
				$stmt->bindParam(':id', $update_meal_id);
				$stmt->execute();

				$stmt2 = $conn->prepare($sql2);
				$stmt2->bindParam(':id', $update_meal_id);
				$stmt2->execute();
				$num_meal_ingredients = $stmt2->rowCount();
 
				$stmt3 = $conn->prepare($sql3);
				$stmt3->bindParam(':id', $update_meal_id);
				$stmt3->execute();
				$num_meal_directions = $stmt3->rowCount();


				if($stmt && $stmt2 && $stmt3) // test that query was made
				{
					$displayMsg .= "<h2>Update Recipe</h2>";
					$row=$stmt->fetch(PDO::FETCH_ASSOC);
					$meal_mealname = $row['mealname'];
					// Meal name input
					$displayMsg .= "<form name='mealForm' method='post' action='adminMealForm.php' enctype='multipart/form-data'>";
					$displayMsg .= "<p>&nbsp</p><table id='dataTable'>";
					$displayMsg .= "<p><tr>";
					$displayMsg .= "<label>Meal Name: ";
					$displayMsg .= "<input type='text' name='meal_mealname' id='meal_mealname' value='$meal_mealname'><span class='error' style='color:red; padding-left:2em'>$meal_mealname_Err</span>";
					$displayMsg .= "</label>";
					$displayMsg .= "</tr></p>";
					
					$displayMsg .= "<p><tr>";
					$displayMsg .= "<label>Meal Photo:";
					$displayMsg .= "<input type='file' name='photo' id='photo'><span class='error' style='color:red; padding-left:2em'></span>";
					$displayMsg .= "</label>";
					$displayMsg .= "</tr></p>";
					
					// ingredients
					//$displayMsg .= "<input type='button' value='Add Ingredient' onClick='addRow('dataTable')' />"; 
  					//$displayMsg .= "<input type='button' value='Remove Ingredient' onClick='deleteRow('dataTable')/>"; 
  					//$displayMsg .= "<div style = 'font-size: .5em;'>(All actions apply only to entries with check marked check boxes only.)</div>";

					$count = 0;
					while($row2=$stmt2->fetch(PDO::FETCH_ASSOC)){
						$count++;
						$meal_ingredients = $row2['ingredient'];
						$displayMsg .= "<p><tr>";
						//$displayMsg .= "<input type='checkbox' name='chkIng[]' style = 'float:left;'/>";
						$displayMsg .= "<label style = 'float:left;'>Ingredient $count: </label></tr><br>";
						$displayMsg .= "<tr><textarea name='meal_ingredients$count' id='meal_ingredients$count' cols='45' rows='5'>$meal_ingredients</textarea><span class='error' style='color:red; padding-left:2em'>$meal_ingredients_Err</span>";
						//$displayMsg .= "</p>";
						$displayMsg .= "</tr></p>";
					}
					// directions
					$count = 0;
					while($row3=$stmt3->fetch(PDO::FETCH_ASSOC || count)){
						$count++;
						$meal_directions = $row3['direction'];
						$displayMsg .= "<p><tr>";
						//$displayMsg .= "<input type='checkbox' name='chkDir[]' style = 'float:left;'/>";
						$displayMsg .= "<label style = 'float:left;'>Step $count: </label></tr><br>";
						$displayMsg .= "<tr><textarea name='meal_directions$count' id='meal_directions$count' cols='45' rows='5'>$meal_directions</textarea><span class='error' style='color:red; padding-left:2em'>$meal_directions_Err</span>";
						$displayMsg .= "</tr></p>";
					}
					// robotest and meal id
					$displayMsg .= "<input type='hidden' name='update_meal_id' id='update_meal_id' value='$update_meal_id'>";
					$displayMsg .= "<input type='hidden' name='num_meal_ingredients' id='num_meal_ingredients' value='$num_meal_ingredients;'>";
					$displayMsg .= "<input type='hidden' name='num_meal_directions' id='num_meal_directions' value='$num_meal_directions'>";
					$displayMsg .= "<input type='hidden' name='robotest' id='robotest'>";
					$displayMsg .= "<p>";
					// buttons
					$displayMsg .= "<input type='submit' name='submit' id='submit' value='Submit'>";
					$displayMsg .= "<input type='button' name='button2' id='button2' value='Reset' onClick = resetForm()>";
					$displayMsg .= "</p></table></form>";
				}else{
					$message = "No record found";
				}
			}else{
				$displayMsg .= "<h2>Add new Recipe</h2>";
				// Meal name input
				$displayMsg .= "<form name='mealForm' method='post' action='adminMealForm.php' enctype='multipart/form-data'>";
				$displayMsg .= "<div id = forminput>";
				$displayMsg .= "<p>";
				$displayMsg .= "<label>Meal Name:";
				$displayMsg .= "<input type='text' name='meal_mealname' id='meal_mealname'><span class='error' style='color:red; padding-left:2em'></span>";
				$displayMsg .= "</label>";
				$displayMsg .= "</p>";
				
				$displayMsg .= "<label>Meal Photo:";
				$displayMsg .= "<input type='file' name='photo' id='photo'><span class='error' style='color:red; padding-left:2em'></span>";
				$displayMsg .= "</label>";
				$displayMsg .= "</p>";
				
				$displayMsg .= "<p><label for = 'number'>Number of Ingredients:</label>
				<input id = 'num_meal_ingredients' name = 'num_meal_ingredients' value = ''></p>";
				
				$displayMsg .= "<p><label for = 'number'>Number of Steps:</label>
				<input id = 'num_meal_directions' name = 'num_meal_directions' value = ''></p>";
				
				$displayMsg .= "<p><a href='#' id='filldetails' onclick='addFields()' style = 'text-decoration: underline; font-size: 1em;'>Click here to submit number of ingredients and steps</a></p>";
				
				$displayMsg .= "<div id='jscontainer'></div>";
				
				$displayMsg .= "<p><input type='hidden' name='robotest' id='robotest'></p>";
				$displayMsg .= "<p><input type='hidden' name='update_meal_id' id='update_meal_id'>";
				$displayMsg .= "</p><p>";
				// buttons
				$displayMsg .= "<input type='submit' name='submit' id='submit' value='Submit'>";
				$displayMsg .= "<input type='button' name='button2' id='button2' value='Reset' onClick = resetForm()>";
				$displayMsg .= "</p></div><!--end form input--></form>";
			}
		}
		catch(PDOException $e)
		{
			$message = "There has been a problem. The system administrator has been contacted. Please try again later.";
				
			error_log($e->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
				
			//error_log($e->errorInfo());
			error_log($e->getMessage());
			//error_log($conn->errorCode());
			//error_log($conn->connect_error);
			error_log(var_dump(debug_backtrace()));
								
			//Clean up any variables or connections that have been left hanging by this error.	
			
			//header('Location: files/505_error_response_page.php');	
			//sends control to a User friendly page					
		}
		finally
		{
			$conn = null;
		}		
				
	}
}else{
	header('Location: login.php');
}
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>Meal Planner Admin-Add or Modify Meal</title>
	<script src = "scripts.js"></script>
<link href= "adminstyle.css" rel= "stylesheet" type= "text/css"/>
</head>

<body>
<div id = "container">
<div id = "login">
	<a href = "adminSelectMeals.php"><?php echo $userMessage; ?></a>
	<a href = "login.php"><img src="images/login.png" alt="Login" style="width:25px;hieght:25px;"></a>
	<a href = "logout.php"><img src="images/logout.png" alt="Logout" style="width:25px;hieght:25px;"></a>
</div>
<header>
	<h1>Meal Planner Admin</h1>
</header>
<nav>
	<ul>
		<li><a href = "index.php">Home</a></li>
		<li><a href = "mealPick.php">Meal Planner</a></li>
		<li><a href = "selectMeals.php">View All Recipes</a></li>
		<li><a href = "contactForm.php">Contact Us</a></li>
		<li><a href = "adminSelectMeals.php">View and Modify Recipes</a></li>
		<li><a href = "adminMealForm.php">Add New Recipe</a></li>
	</ul>
</nav>
<main>
		  <?php echo $displayMsg?>
<!--p>&nbsp;</p-->
</main>
<footer>
	<p>&copy; 2017 Merna Addison</p>
</footer>
</div><!--end container-->
</body>
</html>