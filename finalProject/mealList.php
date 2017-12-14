<?php
session_start();
//The following section of PHP acts as the Controller.  It contains the processing logic
//needed to gather the data from the database table.  Format the data into a presentation
//format that can be viewed on the client's browser.
$userMessage = "";
$displayMsg = "";
$num_meals = 0;

	if (isset($_SESSION['validUser']) && $_SESSION['validUser'] == "yes")				//is this already a valid user?
	{			//User is already signed on. 
		$username = $_SESSION['username'];			
		$userMessage = "Welcome Back $username!";	//Create greeting for VIEW area		
	}	
	if(isset($_SESSION['mealNum'])){
		$num_meals = (int)$_SESSION['mealNum'];
		//echo $num_meals;
	}else{
		header('Location: mealPick.php');
	}


	
		try
		{
			include 'dbConnectPDO.php';				//connects to the database
			//$sql = "SELECT id, mealname FROM miaddison_meals.meals ORDER BY RAND() LIMIT :numMeals";
			$sql = "SELECT id, mealname FROM meals.meals ORDER BY RAND() LIMIT :numMeals";
	
				//PREPARE the SQL statement
				$stmt = $conn->prepare($sql);
				$stmt->bindParam(':numMeals', $num_meals, PDO::PARAM_INT);
				$stmt->execute();
			
				if($stmt) // test that query was made
				{
						$displayMsg .='<form name="selectedMeals" method="post" action="selectedMeals.php">';
						$displayMsg .= "<table>";
						$displayMsg .= "<tr>";
						$displayMsg .= "<th>Select</th>";
						$displayMsg .= "<th>Meal Name</th>";
						$displayMsg .= "</tr>";
						
						// display meal names with link to recipe and checkbox to select
						while($row = $stmt->fetch(PDO::FETCH_ASSOC)){
							$meal_name = $row["mealname"];
							$meal_id = $row["id"];
							$displayMsg .= "<tr>";
							$displayMsg .= "<td><input type='checkbox' name='mealId[]' id='mealId' checked='checked' value='$meal_id'></td>";
							$displayMsg .= "<td><a href='viewMeal.php?meal_id=$meal_id' target='_blank'> $meal_name </a>";
							$displayMsg .= "</td></tr>";
						}
						
						$displayMsg .= "</table>";
						$displayMsg .= "<p><button formaction='mealPick.php' type='submit'>Try again</button>";
						$displayMsg .='<input type="submit" name="submit" id="submit" value="Submit"></p>';
						$displayMsg .="</form>";
				}
				else
				{
					//display error message for DEVELOPMENT purposes
					$displayMsg .= "<h3>Sorry there has been a problem</h3>";
				}
			}// end of try
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

