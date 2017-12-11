<?php
session_start();
if ($_SESSION['validUser'] == "yes")	//If this is a valid user allow access to this page
{
	//The following section of PHP acts as the Controller.  It contains the processing logic
	//needed to gather the data from the database table.  Format the data into a presentation
	//format that can be viewed on the client's browser.
	$displayMsg = "";
	$username = $_SESSION['username'];			
	$userMessage = "Welcome Back $username!";	//Create greeting for VIEW area		
		

	try
	{
		include 'dbConnectPDO.php';				//connects to the database
		$sql = "SELECT id, mealname FROM miaddison_meals.meals";
		//$sql = "SELECT id, mealname FROM meals.meals";
		//PREPARE the SQL statement
		$stmt = $conn->prepare($sql);
		$stmt->execute();

		if($stmt) // test that query was made
		{
			//process the result
			if ($stmt->rowCount() > 0) 
			{
				$displayMsg = "<h1 class = center>" . $stmt->rowCount() . " Recipes have been found</h1>";	
				$displayMsg .= "<table>";
				$displayMsg .= "<tr>";
				$displayMsg .= "<th>Meal Name</th>";
				$displayMsg .= "<th>View</th>";
				$displayMsg .= "<th>Update</th>";
				$displayMsg .= "<th>Delete</th>";
				$displayMsg .= "</tr>";

				while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
					$displayMsg .= "<tr><td>";
					$displayMsg .= $row["mealname"];
					$displayMsg .= "</td><td>";
					$meal_id = $row["id"];
					$displayMsg .= "<a href='adminViewMeal.php?meal_id=$meal_id'>  View  </a>";
					$displayMsg .= "</td><td>";
					$displayMsg .= "<a href='adminMealForm.php?meal_id=$meal_id'>  Modify  </a>";
					$displayMsg .= "</td><td>";
					$displayMsg .= "<a href='adminDeleteMeal.php?meal_id=$meal_id'>  Delete  </a>";
					$displayMsg .= "</td></tr>";
				}

				$displayMsg .= "</table>";
			} 
			else 
			{
				$displayMsg .= "0 results";
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



	//The following HTML or markup is the VIEW.  This will be sent to the client for display in their browser.
	//Notice that we echo the $displayMsg variable which contains the formatted output that we created in the 
	//Controller area above.  	
}else{
	header('Location: login.php');
}
?>
<html>
<head>
	<title>Meal Planner Admin-Select Recipes</title>
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
	<?php echo $displayMsg;?>
</main>
<footer>
	<p>&copy; 2017 Merna Addison</p>
</footer>
</body>
</html>

