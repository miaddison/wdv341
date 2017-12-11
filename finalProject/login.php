<?php 
session_cache_limiter('none');			//This prevents a Chrome error when using the back button to return to this page. May not still need this.
session_start();			// attaches page to current session
 	
	$message = "";

	if (isset($_SESSION['validUser']) && $_SESSION['validUser'] == "yes")				//is this already a valid user?
	{
		//User is already signed on.  Skip the rest.
		$username = $_SESSION['username'];
		$message = "Welcome Back! $username";	//Create greeting for VIEW area		
	}
	else
	{
		//try{
			if (isset($_POST['submitLogin']) )			//Was this page called from a submitted form?
			{
				$inUsername = $_POST['loginUsername'];	//pull the username from the form
				$inPassword = $_POST['loginPassword'];	//pull the password from the form

				include 'dbConnectPDO.php';				//Connect to the database

				//$sql = "SELECT user_name, user_password FROM wdv341.user WHERE user_name = :username AND user_password = :password";	
				$sql = "SELECT user_name, user_password FROM miaddison_meals.users WHERE user_name = :username AND user_password = :password";		
				
				$query = $conn->prepare($sql);	//prepare the query
				$query->bindParam(':username',$inUsername);	//bind parameters to prepared statement
				$query->bindParam(':password',$inPassword);
				$query->execute();



				$row = $query->fetch(PDO::FETCH_ASSOC);	

				//echo "<h2>userName: $userName</h2>";
				//echo "<h2>password: $passWord</h2>";

				//echo "<h2>Number of rows affected " . $connection->affected_rows . "</h2>";	//best for Update,Insert,Delete			
				//echo "<h2>Number of rows found " . $query->num_rows . "</h2>";				//best for SELECT

				if ($query->rowCount() == 1 )		//If this is a valid user there should be ONE row only
				{
					$_SESSION['validUser'] = "yes";				//this is a valid user so set your SESSION variable
					$username = $row['user_name'];
					$_SESSION['username'] = $username;
					$message = "Welcome Back! $username";
					//Valid User can do the following things:
				}
				else
				{
					//error in processing login.  Logon Not Found...
					$_SESSION['validUser'] = "no";					
					$message = "Sorry, there was a problem with your username or password. Please try again.";
				}			

				$query = null;
				$conn = null;

			}//end if submitted
			else
			{
				//user needs to see form
			}//end else submitted
		//}catch(PDOException $e){
			//$message = "<h3>There has been a problem. The system administrator has been contacted. Please try again later.</h3>";

			//error_log($e->getMessage());			//Delivers a developer defined error message to the PHP log file at c:\xampp/php\logs\php_error_log
			//error_log(var_dump(debug_backtrace()));								
		//}
		
	}//end else valid user
	
//turn off PHP and turn on HTML
?>
<!DOCTYPE html>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Meal Planner Admin - Login</title>
<link href= "adminstyle.css" rel= "stylesheet" type= "text/css"/>

<!--  User Login Page
            
if user is valid (Session variable - already logged on)
	display admin options
else
    if form has been submitted
        Get input from $_POST
        Create SELECT QUERY
        Run SELECT to determine if they are valid username/password
        if user if valid
            set Session variable to true
            display admin options
        else
            display error message
            display login form
    else
    display login form
         
-->
</head>
<body>
<div id = "containter">
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

<?php
	if (isset($_SESSION['validUser']) && $_SESSION['validUser'] == "yes")	//This is a valid user.  Show them the Administrator Page
	{
		
//turn off PHP and turn on HTML
		header('Location: adminSelectMeals.php');
?>

		<li><a href = "adminSelectMeals.php">View and Modify Recipes</a></li>
		<li><a href = "adminMealForm.php">Add New Recipe</a></li>
        					
<?php
	}
	else									//The user needs to log in.  Display the Login Form
	{
?>	
</ul>
</nav>
<main>
			<h2>Please login</h2>
                <form method="post" name="loginForm" action="login.php" >
                  <p>Username: <input name="loginUsername" type="text" /></p>
                  <p>Password: <input name="loginPassword" type="password" /></p>
                  <p>Username: wdv341<br>
					  Password: wdv341</p>
                  <p><input name="reset" type="reset" /><input name="submitLogin" value="Submit" type="submit" /> &nbsp;</p>
                </form>
                
<?php //turn off HTML and turn on PHP
	}//end of checking for a valid user
			
//turn off PHP and begin HTML			
?>
</main>
<footer>
	<p>&copy; 2017 Merna Addison</p>
</footer>
</div>	<!-- end container -->	
</body>
</html>