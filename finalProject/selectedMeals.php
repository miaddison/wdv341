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

if(isset($_POST["submit"]) && !empty($_POST["mealId"])){
	$meal_ids = $_POST['mealId'];
	
	try
	{
		include 'dbConnectPDO.php';				//connects to the database
		
		$displayMsg .="<h3>Here are the directions you requested.</h3>";
		$displayMsg .="<nav class='sub'>";
		//create sub nav bar
		foreach($_POST['mealId'] as $meal_id){
			if(isset($meal_id)){
				$sqlSubNav="SELECT mealname FROM miaddison_meals.meals WHERE id = :id";
				//$sqlSubNav="SELECT mealname FROM meals.meals WHERE id = :id";
				$stmtSubNav=$conn->prepare($sqlSubNav);
				$stmtSubNav->bindParam(':id',$meal_id);
				$stmtSubNav->execute();
		
				$rowSubNav = $stmtSubNav->fetch(PDO::FETCH_ASSOC);
				$displayMsg .="<li><a href='#$meal_id'>".$rowSubNav['mealname']."</a></li>";
			}
		}
		$displayMsg .="</nav>";
		foreach($_POST['mealId'] as $meal_id){
			if(isset($meal_id)){
				// Remote SQL
				$sql = "SELECT mealname, photo FROM miaddison_meals.meals WHERE id = :id";
				$sql2 = "SELECT ingredient FROM miaddison_meals.ingredients WHERE id = :id";
				$sql3 = "SELECT direction FROM miaddison_meals.directions WHERE id = :id";

				// Local SQL
				//$sql = "SELECT mealname, photo FROM meals.meals WHERE id = :id";
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
						$row = $stmt->fetch(PDO::FETCH_ASSOC);
						// div to create anchor for nav bar
						$displayMsg .="<div id='$meal_id'>";
					
						//display recipe photo 
						$displayMsg .= '<p class = center><img src = "images/'.$row["photo"].'" alt="recipe photo" style="max-height:400px; max-width:400px"></p>';
						
						$displayMsg .="<a href='#top'><button id='top' style = 'float: left; margin-left:15%'>Top of Page</button></a>";
						//button for printing each recipe, also has css formatting for printing
						$displayMsg .="<input style = 'float: right; margin-right:15%' type='button' value='Print Recipe' onclick='printDiv(".$meal_id.")'/>";
						
						// display recipe name
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
						$displayMsg .="</div>"; //end meal div
					} 
				}
				else 
				{
					$displayMsg .= "0 results";
					$displayMsg .= "<p>Please <a href='selectMeals.php'>view</a> your recipes.</p>";
				}
			}//end if($stmt && $stmt2 && $stmt3)
		
			else
			{
				//display error message for DEVELOPMENT purposes
				$displayMsg .= "<h3>Sorry there has been a problem</h3>";
			}
		}//end foreach($meal_ids as $meal_id)
	}//end try
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
}//if submit
else{
	$displayMsg = "<h3>There were no recipes selected. Click <a href='mealPick.php'>here</a> to try again.</h3>";
}	
	
//The following HTML or markup is the VIEW.  This will be sent to the client for display in their browser.
//Notice that we echo the $displayMsg variable which contains the formatted output that we created in the 
//Controller area above.  	
?>
<html>
<head>
	<title>Meal Planner-Select Recipes</title>
	<link href= "style.css" rel= "stylesheet" type= "text/css" media="screen"/>
	<link href= "printstyle.css" rel= "stylesheet" type= "text/css" media = "print"/>
	<script src = "scripts.js"></script>
</head>
<body>
<div id = "container">
<div id = "login">
	<a href = "adminSelectMeals.php"><?php echo $userMessage; ?></a>
	<a href = "login.php"><img src="images/login.png" alt="Login" style="width:25px;hieght:25px;"></a>
	<a href = "logout.php"><img src="images/logout.png" alt="Logout" style="width:25px;hieght:25px;"></a>
</div>
<header id="top">
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

