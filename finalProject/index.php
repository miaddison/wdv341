<?php
session_start();

$userMessage = "";

	if (isset($_SESSION['validUser']) && $_SESSION['validUser'] == "yes")				//is this already a valid user?
	{			//User is already signed on.  Skip the rest.
		$username = $_SESSION['username'];			
		$userMessage = "Welcome Back $username!";	//Create greeting for VIEW area		
	}	
?>
<html>
<head>
	<title>Meal Planner-Home</title>
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
	
	<h2>Welcome to the meal planner!</h2> 
	<p class = center><img src="images/getcooking.png" alt="Let's Get Cooking!" style="max-height:400px; max-width:400px;"/></p>
	<p>You can click <a href = "mealPick.php">here</a> and have us 
	plan your meals for you or you can also browse our entire selection 
	of <a href = "selectMeals.php">recipes.</a></p>
	
</main>
<footer>
	<p>&copy; 2017 Merna Addison</p>
</footer>
</body>
</html>

