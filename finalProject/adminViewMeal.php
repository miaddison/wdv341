<?php

//The following section of PHP acts as the Controller.  It contains the processing logic
//needed to gather the data from the database table.  Format the data into a presentation
//format that can be viewed on the client's browser.
	$displayMsg = "";
	
try
{
	$meal_id = $_GET['meal_id']; // retrieve id from GET parameter
	
	include 'dbConnectPDO.php';				//connects to the database
	
	// Remote SQL
	$sql = "SELECT mealname, photo FROM miaddison_meals.meals WHERE id = :id";
	$sql2 = "SELECT ingredient FROM miaddison_meals.ingredients WHERE id = :id";
	$sql3 = "SELECT direction FROM miaddison_meals.directions WHERE id = :id";
	
	// Local SQL
	//$sql = "SELECT mealname photo FROM meals.meals WHERE id = :id";
	//$sql2 = "SELECT ingredient FROM meals.ingredients WHERE id = :id";
	//$sql3 = "SELECT direction FROM meals.directions WHERE id = :id";

	//PREPARE the SQL statement
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
		//process the result
		if ($stmt->rowCount() > 0) 
		{
			//display recipe name
			$row = $stmt->fetch(PDO::FETCH_ASSOC);
			$displayMsg = '<p class = center><img src = "images/'.$row["photo"].'" alt="recipe photo" style="max-height:400px; max-width:400px"></p>';
			$displayMsg .= "<h2 class = center>".$row["mealname"]."</h2>";	
					
			//begin bullet list of ingredients
			$displayMsg .= "<ul>";
			while($row2 = $stmt2->fetch(PDO::FETCH_ASSOC)){
				$displayMsg .= "<li>";
				$displayMsg .= $row2["ingredient"];
				$displayMsg .= "</li>";
			}
			$displayMsg .= "</ul>";
			
			//begin bullet list of ingredients
			$displayMsg .= "<ol>";
			while($row3 = $stmt3->fetch(PDO::FETCH_ASSOC)){
				$displayMsg .= "<li>";
				$displayMsg .= $row3["direction"];
				$displayMsg .= "</li>";
			}
			$displayMsg .= "</ol>";
			
		} 
		else 
		{
			$displayMsg .= "0 results";
			$displayMsg .= "<p>Please <a href='selectMeals.php'>view</a> your recipes.</p>";
		}		
	}
	else
	{
		//display error message for DEVELOPMENT purposes
		$displayMsg .= "<h3>Sorry there has been a problem</h3>";
		$displayMsg .= "<p>Please <a href='selectMeals.php'>view</a> your recipes.</p>";
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
	<title>View Recipe</title>
	<link href= "adminstyle.css" rel= "stylesheet" type= "text/css"/>
	<link href = "printstyle.css" rel = "stylesheet" type = "text/css" media = "print" />
</head>
<body>
<div id = "container">
<div id = "login">
	<a href = "login.php">Login</a>
</div>
<header>
	<h1>View Recipe</h1>
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
</div><!--end container-->
</body>
</html>

