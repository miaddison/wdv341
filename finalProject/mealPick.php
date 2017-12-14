<?php
session_start();
//The following section of PHP acts as the Controller.  It contains the processing logic
//needed to gather the data from the database table.  Format the data into a presentation
//format that can be viewed on the client's browser.
$userMessage = "";

	if (isset($_SESSION['validUser']) && $_SESSION['validUser'] == "yes")				//is this already a valid user?
	{			//User is already signed on.  Skip the rest.
		$username = $_SESSION['username'];			
		$userMessage = "Welcome Back $username!";	//Create greeting for VIEW area		
	}	

	$num_meals = "";
	$roboTest = "";
	$message = "";

	$num_meals_err = "";

	$validForm = false;
	
	if(isset($_POST["submit"]) && !empty($_POST["submit"]))
	{
		$num_meals = $_POST['mealnum'];
		$roboTest = $_POST['robotest'];


		// Validate num_meals
		function validateNumMeals($num_meals){
			global $validForm, $num_meals_err, $message;
			$num_meals = filter_var($num_meals, FILTER_SANITIZE_STRING);
			if(!filter_var($num_meals, FILTER_VALIDATE_INT)){
				$num_meals_err = "*Please enter a whole number";
				$validForm = false;	
			}	
		}

		$validForm = true;
		validateNumMeals($num_meals);
		$_SESSION['mealNum'] = $num_meals;

		// validate not robot
		if($roboTest){
			//$validForm = false;
		}
		if($validForm==true){
			header('Location: mealList.php');
		}


	} // end if submit
	else{
		//display form
	}	
	
	
//The following HTML or markup is the VIEW.  This will be sent to the client for display in their browser.
//Notice that we echo the $displayMsg variable which contains the formatted output that we created in the 
//Controller area above.  	
?>
<html>
<head>
	<title>Meal Planner-Select Recipes</title>
	<link href= "style.css" rel= "stylesheet" type= "text/css"/>
</head>
<body>
<div id = "container">
<div id = "login">
	<a href = "adminSelectMeals.php"><?php echo $userMessage; ?></a>
	<a href = "login.php"><img src="images/login.png" alt="Login" style="width:25px;hieght:25px;"></a>
	<a href = "logout.php"><img src="images/logout.png" alt="Logout" style="width:25px;hieght:25px;"></a>
</div>
<header>
	<h1>Meal Planner</h1>
</header>
<nav>
	<ul>
		<li><a href = "index.php">Home</a></li>
		<li><a href = "mealPick.php">Meal Planner</a></li>
		<li><a href = "selectMeals.php">View All Recipes</a></li>
		<li><a href = "contactForm.php">Contact Us</a></li>
	</ul>
</nav>
<main>
	<?php echo $message; ?>
	<form name="mealNumForm" method="post" action="mealPick.php" >
		<h3>Please enter the number of meals you would like to plan below.</h3>

			<p>
			<label for = "mealnum">Number of Meals:</label> 
			<input type = "text" name = "mealnum" id = "mealnum" size = "3" value="<?php echo $num_meals ?>" required><span class="error" style="color:red; padding-left:2em"><?php echo $num_meals_err ?></span>
			<input type="hidden" name="robotest" id="robotest">
		</p>
		<p>
			<input type = "reset" name="reset" id="reset" value = "Reset" />
			<input type="submit" name="submit" id="submit" value="Submit">
		</p>
	</form>
</main>
<footer>
	<p>&copy; 2017 Merna Addison</p>
</footer>
</body>
</html>

