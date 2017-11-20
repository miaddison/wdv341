<!--
	Name: Merna Addison
	Email: merna.addison@gmail.com
	Date: 11/18/17
	Course: WDV341 Intro to PHP
-->
<?php
//session_start(); // join current session equivalent to cookies in browser
//Only allow a valid user access to this page
//if ($_SESSION['validUser'] !== "yes") {
//	header('Location: index.php'); // if not valid user redirect to homepage
//}
	//Setup variables used by the page	
		// field data
		$meal_mealname = "";
		$meal_ingredients = "";
		$num_meal_ingredients = "";
		$meal_directions = "";
		$num_meal_directions = "";
		$roboTest = "";
		$displayMsg = "";
		$update_meal_id = "";

		// Error messages
		$meal_mealname_Err = "";
		$meal_ingredients_Err = "";
		$meal_directions_Err = "";	
		
		// flags for sucessful form submission
		$validForm = false;
	
	
	if(isset($_POST["submit"]))
	{
		// the form has been sumitted and needs to be processed
		$meal_mealname = $_POST['meal_mealname'];
		$meal_ingredients = $_POST['meal_ingredients'];
		$meal_directions = $_POST['meal_directions'];
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
		$meal_ingredients = validateSanitizeMealIngredients($meal_ingredients[]);
		$meal_directions = validateSanitizeMealDirections($meal_directions[]);
		
		if($validForm)
		{
			$displayMsg = "<h2>All good</h2>";
			try 
			{
				
				require 'dbConnectPDO.php'; // connect to the database
	
				if($update_meal_id!=""){
					$sql = "UPDATE miaddison_meals.meals SET";
					$sql .= " mealname=:mealName";
					$sql .=" WHERE id=:update_meal_id";
					//echo($sql); // testing
					
					//PREPARE the SQL statement
					$stmt = $conn->prepare($sql);
					$stmt->bindParam(':mealName', $meal_mealname);
					$stmt->execute();
					
					for($i = 0; $i < $num_meal_ingredients; $i++){
						$sql2 = "UPDATE miaddison_meals.ingredients SET";
						$sql2 .= " ingredient=:mealIngredients";
						$sql2 .= " WHERE id=:update_meal_id AND num=$i";
						
						$stmt2 = $conn->prepare($sql2);
						$stmt2->bindParam(':mealIngredients', $meal_ingredients[]);
						$stmt2->execute();
					}
					
						
					$stmt->bindParam(':mealDirections', $meal_directions);		
					$stmt->bindParam(':eventDate', $event_date);		
					$stmt->bindParam(':eventTime', $event_time);
					$stmt->bindParam(':update_meal_id', $update_meal_id);
					
					$stmt->execute();	

					$displayMsg = "<h2>Your event has been updated.</h2>";
					$displayMsg .= "<h2>Please <a href='selectEvents.php'>view</a> your records.</h2>";
					
				}else{
					//$sql = "INSERT INTO wdv341.wdv341_event(";
					$sql = "INSERT INTO miaddison_wdv.wdv341_event(";
					$sql .= "meal_mealname, ";					
					$sql .= " meal_ingredients, ";
					$sql .= " meal_directions, ";
					$sql .= " event_date, ";
					$sql .= " event_time"; //Last column does NOT have a comma after it.
					$sql .= ") VALUES (:mealName, :mealIngredients, :mealDirections, :eventDate, :eventTime)";
					
					//PREPARE the SQL statement
					$stmt = $conn->prepare($sql);

					//if(!$stmt){
					//	echo"\nPDO::errorInfo():\n";
					//	print_r($conn->errorInfo());
					//}

					//BIND the values to the input parameters of the prepared statement
					$stmt->bindParam(':mealName', $meal_mealname);
					$stmt->bindParam(':mealIngredients', $meal_ingredients);		
					$stmt->bindParam(':mealDirections', $meal_directions);		
					$stmt->bindParam(':eventDate', $event_date);		
					$stmt->bindParam(':eventTime', $event_time);

					//EXECUTE the prepared statement
					$stmt->execute();	

					$displayMsg = "<h2>Your event has been added.</h2>";
					$displayMsg .= "<h2>Please <a href='selectEvents.php'>view</a> your records.</h2>";
				
				}
				
				
				
			}
			
			catch(PDOException $e)
			{
				$displayMsg = "<h2>There has been a problem. The system administrator has been contacted. Please try again later.</h2>";
				
				error_log($e->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
				
				//error_log($e->errorInfo());
				error_log($e->getMessage());
				//error_log($conn->errorCode());
				//error_log($conn->connect_error);
				error_log(var_dump(debug_backtrace()));
				
				//Clean up any variables or connections that have been left hanging by this error.		
			
				//header('Location: files/505_error_response_page.php');	//sends control to a User friendly page					
			}
		}
		else
		{
			$displayMsg = "<h2>Something went wrong</h2>";
		}//end check for valid form
		
	}
	else
	{
		try{
			//user has not seen form yet
			$update_meal_id=$_GET['meal_id']; // user updating 
			if($update_meal_id != ""){
				require 'dbConnectPDO.php'; // connect to the database

				$sql = "SELECT id, mealname FROM miaddison_meals.meals WHERE id=:id";

				//PREPARE the SQL statement
				$stmt = $conn->prepare($sql);
				$stmt->bindParam(':id', $update_meal_id);
				$stmt->execute();

				$sql2 = "SELECT ingredient FROM miaddison_meals.ingredients WHERE id=:id";

				//PREPARE the SQL statement
				$stmt2 = $conn->prepare($sql2);
				$stmt2->bindParam(':id', $update_meal_id);
				$stmt2->execute();

				$sql3 = "SELECT direction FROM miaddison_meals.directions WHERE id=:id";

				//PREPARE the SQL statement
				$stmt3 = $conn->prepare($sql3);
				$stmt3->bindParam(':id', $update_meal_id);
				$stmt3->execute();



				if($stmt && $stmt2 && $stmt3) // test that query was made
				{
					$row=$stmt->fetch(PDO::FETCH_ASSOC);
					$meal_mealname = $row['mealname'];
					// Meal name input
					$displayMsg .= "<form name='eventForm' method='post' action='eventsForm.php'>";
					$displayMsg .= "<p>&nbsp;</p>";
					$displayMsg .= "<p>";
					$displayMsg .= "<label>Meal Name:";
					$displayMsg .= "<input type='text' name='meal_mealname' id='meal_mealname' value='$meal_mealname'><span class='error' style='color:red; padding-left:2em'>$meal_mealname_Err</span>";
					$displayMsg .= "</label>";
					$displayMsg .= "</p>";
					// ingredients
					$count = 0;
					while($row2=$stmt2->fetch(PDO::FETCH_ASSOC)){
						$count++;
						$meal_ingredients = $row2['ingredient'];
						$displayMsg .= "<p>Ingredient Number $count:";
						$displayMsg .= "<textarea name='meal_ingredients' id='meal_ingredients' cols='45' rows='5'>$meal_ingredients</textarea><span class='error' style='color:red; padding-left:2em'>$meal_ingredients_Err</span>";
						$displayMsg .= "</p>";
					}
					// directions
					$count = 0;
					while($row3=$stmt3->fetch(PDO::FETCH_ASSOC)){
						$count++;
						$meal_directions = $row3['direction'];
						$displayMsg .= "<p>Direction Number $count:";
						$displayMsg .= "<textarea name='meal_directions' id='meal_directions' cols='45' rows='5'>$meal_directions</textarea><span class='error' style='color:red; padding-left:2em'>$meal_directions_Err</span>";
						$displayMsg .= "</p>";
					}
					// robotest
					$displayMsg .= "<p><input type='hidden' name='update_meal_id' id='update_meal_id' value='$update_meal_id'>";
					$displayMsg .= "</p><p>";
					$displayMsg .= "<input type='hidden' name='robotest' id='robotest'>";
					$displayMsg .= "</p><p>";
					// buttons
					$displayMsg .= "<input type='submit' name='submit' id='submit' value='Submit'>";
					$displayMsg .= "<input type='button' name='button2' id='button2' value='Reset' onClick = resetForm()>";
					$displayMsg .= "</p></form>";
				}else{
					$message = "No record found";
				}
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
?>
<!DOCTYPE HTML>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<title>WDV341 Intro PHP - Create a form page for the events table</title>
<script>
	function resetForm(){
		document.getElementById('meal_mealname').value = "";
		document.getElementById('meal_ingredients').value = "";
		document.getElementById('meal_directions').value = "";
		document.getElementById('event_date').value = "";
		document.getElementById('event_time').value = "";
	}
	function addIngFields(){
            // Number of inputs to create
            var number = document.getElementById("num_meal_ingredients").value;
            // Container <div> where dynamic content will be placed
            var container = document.getElementById("container");
            // Clear previous contents of the container
            while (container.hasChildNodes()) {
                container.removeChild(container.lastChild);
            }
            container.appendChild(document.createElement("br"));
            for (i=0;i<number;i++){
                // Append a node with a random text
                container.appendChild(document.createTextNode("Ingredient " + (i+1) + ": "));
                // Create an <input> element, set its type and name attributes
                var input = document.createElement("input");
                input.type = "text";
                input.name = ("meal_ingredients" + (i+1));
                container.appendChild(input);
                // Append a line break 
                container.appendChild(document.createElement("br"));
                container.appendChild(document.createElement("br"));
            }
	}
</script>
<link href= "style.css" rel= "stylesheet" type= "text/css"/>
</head>

<body>
<div id = "container">
<header>
	<h1>Add or Update Recipe</h1>
</header>
<nav>
	<ul>
		<li><a href = "selectMeals.php">Home</a></li>
	</ul>
</nav>
<main>
<?php
    //If the form was submitted and valid and properly put into database display the INSERT result message
	if($validForm)
	{
?>
		  <h1><?php echo $displayMsg?></h1>
		  
<?php
	}
	else	//display form
	{
?>
		<h2><?php echo $displayMsg?></h2>
<?php
	} // end else
?>
<p>&nbsp;</p>
</main>
<footer>
	<p>&copy; 2017 Merna Addison</p>
</footer>
</div><!--end container-->
</body>
</html>