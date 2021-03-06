<?php
session_start();
//The following section of PHP acts as the Controller.  It contains the processing logic
//needed to gather the data from the database table.  Format the data into a presentation
//format that can be viewed on the client's browser.
if ($_SESSION['validUser'] == "yes") {
	$displayMsg = "";
	$username = $_SESSION['username'];			
	$userMessage = "Welcome Back $username!";	//Create greeting for VIEW area		
		
	try
	{
		$meal_id = $_GET['meal_id']; // retrieve id from GET parameter

		include 'dbConnectPDO.php';				//connects to the database

		// Remote SQL
		$sql = "DELETE FROM miaddison_meals.meals WHERE id = :id";
		$sql2 = "DELETE FROM miaddison_meals.ingredients WHERE id = :id";
		$sql3 = "DELETE FROM miaddison_meals.directions WHERE id = :id";

		// Local SQL
		//$sql = "DELETE FROM meals.meals WHERE id = :id";
		//$sql2 = "DELETE FROM meals.ingredients WHERE id = :id";
		//$sql3 = "DELETE FROM meals.directions WHERE id = :id";

		//PREPARE the SQL statements
		$stmt = $conn->prepare($sql);
		$stmt->bindParam(':id',$meal_id);
		$stmt->execute();

		$stmt2 = $conn->prepare($sql2);
		$stmt2->bindParam(':id',$meal_id);
		$stmt2->execute();

		$stmt3 = $conn->prepare($sql3);
		$stmt3->bindParam(':id',$meal_id);
		$stmt3->execute();

		if($stmt && $stmt2 && $stmt3) // test that query was made
		{
			// test that query was made
			if($stmt){
				$displayMsg =  "<h2>Your recipe has been successfully deleted.</h2>";
				$displayMsg .= "<p>Please <a href='adminSelectMeals.php'>view</a> your recipes.</p>";
			}	
		}
		else
		{
			//display error message for DEVELOPMENT purposes
			$displayMsg .= "<h3>Sorry there has been a problem</h3>";
		}
	}
	catch(PDOException $e)
	{
		$displayMsg .= "<h3>There has been a problem. The system administrator has been contacted. Please try again later.</h3>";

		error_log($e->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
		error_log(var_dump(debug_backtrace()));								
	}
	finally
	{
		$conn = null;
	}
}else{
	header('Location: login.php');
}

	
//The following HTML or markup is the VIEW.  This will be sent to the client for display in their browser.
//Notice that we echo the $displayMsg variable which contains the formatted output that we created in the 
//Controller area above.  	
?>
<html>
<head>
	<title>Menu Planner Admin-Delete Recipes</title>
	<link href= "adminstyle.css" rel= "stylesheet" type= "text/css"/>
	<link href = "printstyle.css" rel = "stylesheet" type = "text/css" media = "print" />
	<script src = "scripts.js"></script>
</head>
<body>
<div id = "container">
<div id = "login">
	<a href = "adminSelectMeals.php"><?php echo $userMessage; ?></a>
	<a href = "login.php"><img src="images/login.png" alt="Login" style="width:25px;hieght:25px;"></a>
	<a href = "logout.php"><img src="images/logout.png" alt="Logout" style="width:25px;hieght:25px;"></a>
</div>
<header>
	<h1>Menu Planner Admin</h1>
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
	<?php echo $displayMsg;?>
</main>
<footer>
	<p>&copy; 2017 Merna Addison</p>
</footer>
</body>
</html>

