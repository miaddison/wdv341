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
$displayMsg = "";
	
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
			$displayMsg = "<p class = center><img src='images/getcooking.png' alt='Let's Get Cooking!' style='max-height:200px; max-width:200px;'></p>";	
			$displayMsg .= "<table>";
			$displayMsg .= "<tr>";
			$displayMsg .= "<th>Meal Name</th>";
			//$displayMsg .= "<th>View</th>";
			//$displayMsg .= "<th>Update</th>";
			//$displayMsg .= "<th>Delete</th>";
			$displayMsg .= "</tr>";
			
			while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
				$displayMsg .= "<tr><td>";
				$meal_name = $row["mealname"];
				//$displayMsg .= "</td><td>";
				$meal_id = $row["id"];
				$displayMsg .= "<a href='viewMeal.php?meal_id=$meal_id'> $meal_name </a>";
				//$displayMsg .= "</td><td>";
				//$displayMsg .= "<a href='mealForm.php?meal_id=$meal_id'>  Update  </a>";
				//$displayMsg .= "</td><td>";
				//$displayMsg .= "<a href='deleteMeal.php?meal_id=$meal_id'>  Delete  </a>";
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
	<?php echo $displayMsg;?>
</main>
<footer>
	<p>&copy; 2017 Merna Addison</p>
</footer>
</body>
</html>

