<?php

//The following section of PHP acts as the Controller.  It contains the processing logic
//needed to gather the data from the database table.  Format the data into a presentation
//format that can be viewed on the client's browser.
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
				$displayMsg .= "<a href='adminMealForm.php?meal_id=$meal_id'>  Update  </a>";
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
?>
<html>
<head>
	<title>Select Recipes</title>
	<link href= "adminstyle.css" rel= "stylesheet" type= "text/css"/>
</head>
<body>
<div id = "container">
<div id = "login">
	<a href = "login.php">Login</a>
</div>
<header>
	<h1>Select Recipes</h1>
</header>
<nav>
	<ul>
		<li><a href = "selectMeals.php">View All</a></li>
		<li><a href = "mealForm.php">Add New</a></li>
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

