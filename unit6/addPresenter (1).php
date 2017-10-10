<?php
session_start();
//if ($_SESSION['validUser'] == "yes")	//If this is a valid user allow access to this page
//{
		
	//Setup the variables used by the page
		//field data
		$presenter_first_name = "";
		$presenter_last_name = "";
		$presenter_city = "";
		$presenter_st = "";
		$presenter_zip = "";
		$presenter_email = "";
		//error messages
		$firstNameErrMsg = "";
		$lastNameErrMsg = "";
		$cityErrMsg = "";
		$stErrMsg = "";
		$zipErrMsg = "";
		$emailErrMsg = "";
		
		$validForm = false;
				
	if(isset($_POST["submit"]))
	{	
		//The form has been submitted and needs to be processed
		
		
		//Validate the form data here!
	
		//Get the name value pairs from the $_POST variable into PHP variables
		//This example uses PHP variables with the same name as the name atribute from the HTML form
		$presenter_first_name = $_POST['presenter_first_name'];
		$presenter_last_name = $_POST['presenter_last_name'];
		$presenter_city = $_POST['presenter_city'];
		$presenter_st = $_POST['presenter_st'];
		$presenter_zip = $_POST['presenter_zip'];
		$presenter_email = $_POST['presenter_email'];

		/*	FORM VALIDATION PLAN
		
			FIELD NAME		VALIDATION TESTS & VALID RESPONSES
			First Name		Required Field		May not be empty
			Last Name		Required Field		May not be empty
			
			City			Optional
			State			Optional
			
			Zip Code		Required Field		Format and Numeric 
			Email			Required Field		Format
		*/
		
		//VALIDATION FUNCTIONS		Use functions to contain the code for the field validations.  
			function validateFirstName($inName)
			{
				global $validForm, $firstNameErrMsg;		//Use the GLOBAL Version of these variables instead of making them local
				$firstNameErrMsg = "";
				
				if($inName == "")
				{
					$validForm = false;
					$firstNameErrMsg = "Name cannot be spaces";
				}
			}//end validateName()

			function validateLastName($inName)
			{
				global $validForm, $lastNameErrMsg;		//Use the GLOBAL Version of these variables instead of making them local
				$lastNameErrMsg = "";
				
				if($inName == "")
				{
					$validForm = false;
					$lastNameErrMsg = "Name cannot be spaces";
				}
			}//end validateName()			
			
			function validateZip($inZip)
			{
				global $validForm, $zipErrMsg;
				$zipErrMsg = "";
				
				if(empty($inZip))
				{
					$validForm = false;
					$zipErrMsg = "Zip Code required"; 					
				}
				else
				{
					 if(!preg_match('/^[0-9]{5}([- ]?[0-9]{4})?$/', $inZip))
					 {
						$validForm = false;
						$zipErrMsg = "Invalid Zip Code"; 
					 }
				}
			}//end validateZip()	
					
			function validateEmail($inEmail)
			{
				global $validForm, $emailErrMsg;			//Use the GLOBAL Version of these variables instead of making them local
				$emailErrMsg = "";							//Clear the error message. 
				
				// Remove all illegal characters from email
				$inEmail = filter_var($inEmail, FILTER_SANITIZE_EMAIL);

				// Validate e-mail
				$inEmail = filter_var($inEmail, FILTER_VALIDATE_EMAIL);

				if($inEmail === false)
				{
					$validForm = false;
					$emailErrMsg = "Invalid email"; 					
				}
			}//end validateEmail()		
		
		//VALIDATE FORM DATA  using functions defined above
		$validForm = true;		//switch for keeping track of any form validation errors
		
		validateFirstName($presenter_first_name);
		validateLastName($presenter_last_name);
		validateZip($presenter_zip);
		validateEmail($presenter_email);
		
		if($validForm)
		{
			$message = "All good";	
		}
		else
		{
			$message = "Something went wrong";
		}


/*
	This form is self-posting in order to process the validations.   It will use the following algorithm or process
	in order to properly display and validate the form and its data.
	
	if the form has been submitted		(The user has filled out the form and hit the submit button)
		{
		then validate the form data		(The form data is ready to be validated)
		
		//Validation Algorithm				(The validation process will follow the following process or set of steps)
		set validForm = true					Set a flag or switch to true.  This assumes the form data is valid. 
			perform validateName()				This will validate the data from the Name field
			perform validateQuantity()			This will validate the data from the Quantity field
			perform validateEmail()				This will validate the data from the Email field
			perform validateShipping()			This will validate the data from the Shipping field
		if (validForm==true)				If the flag is still true no errors were found, the form is valid
			{
			move form data into database		The form data is good so it should be INSERTED into the database
			}
		else								The flag is false because errors were found in the data
			{
			load data back into the form		Put the data back into the form fiels so the user can see what was in the fields
			load the error messages				Place the appropriate error messages on the form so the user knows what to fix
			display the form					Display the form with its original data and error messages to the user.
			}
		}
	else
		{
		display the form				(The user needs to enter data on the form so it can be validated and processed)
		}
*/

/*
	field validation algorithm		This process is done for each field validation function.  The details change for each field but the
									same steps are processed in the same order for each validation.  

		clear the error messages for this validation.  Set to ""		(Cleans up from previous errors and assumes there will not be an error)
		check the variable for the field against the expected values
		if it meets those values 
			{
			the field is valid
			nothing else needs done
			}
		else
			{
			the field is invalid
			set the validForm=false			(A data validation error has been found.  The form is no longer valid)
			set the error message variable for this field to the appropriate message
			}
*/		
		//include 'dbConnect.php';	//connects to the database			
		//Create the SQL command string
		$sql = "INSERT INTO presenters (";
		$sql .= "presenter_first_name, ";
		$sql .= "presenter_last_name, ";
		$sql .= "presenter_city, ";
		$sql .= "presenter_st, ";
		$sql .= "presenter_zip, ";
		$sql .= "presenter_email ";	//Last column does NOT have a comma after it.
		$sql .= ") VALUES (?,?,?,?,?,?)";	//? Are placeholders for variables 
	
		//Display the SQL command to see if it correctly formatted.
		//echo "<p>$sql</p>";		
	
		//$query = $connection->prepare($sql);	//Prepares the query statement 
				
		//Binds the parameters to the query.  
		//The ssssis are the data types of the variables in order.		
		//$query->bind_param("ssssis",$presenter_first_name,$presenter_last_name,$presenter_city,$presenter_st,$presenter_zip,$presenter_email);
	
		//Run the SQL prepared statements
		//if ( $query->execute() )
		//{
		//	$message = "<h1>Your record has been successfully added to the database.</h1>";
		//	$message .= "<p>Please <a href='presentersSelectView.php'>view</a> your records.</p>";
		//}
		//else
		//{
		//	$message = "<h1>You have encountered a problem.</h1>";
		//	$message .= "<h2 style='color:red'>" . mysqli_error($link) . "</h2>";	//remove this for production purposes
		//}
	
		//$query->close();
		//$connection->close();	//closes the connection to the database once this page is complete.
		
	}// ends if submit 
	else
	{
		//Form has not been seen by the user.  display the form
	}
//}//end Valid User True
//else
//{
	//Invalid User attempting to access this page. Send person to Login Page
//	header('Location: presentersLogin.php');
//}
?>
<!doctype html>
<html>
<head>
	<meta charset="utf-8">
	<title>Presenting Information Technology</title>

	<link rel="stylesheet" href="css/pit.css">

</head>

<body>

<div id="container">

	<header>
    	<h1>Presenting Information Technology</h1>
    </header>
    
    <nav>
    
    	<ul>
        	<li><a href="index.html">Home</a></li>
            <li><a href="#">Presentations</a></li>
            <li><a href="#">Presenters</a></li>
            <li><a href="addPresenter.php">Add Presenter</a></li>
        	<li><a href="#">Sign On</a></li>
            <li><a href="#">Contact Us</a></li>
        </ul>
    	<div class="clearFloat"></div>
    
    </nav>
    
    <main>
        <h1>Add a new Presenter</h1>
		<?php
            //If the form was submitted and valid and properly put into database display the INSERT result message
			if($validForm)
			{
        ?>
            <h1><?php echo $message ?></h1>
        
        <?php
			}
			else	//display form
			{
        ?>
        
        <p>Once the form is submitted and validated it will call the addPresenters.php page. That page will pull the form data into the PHP and <br>
		add a new record to the database.</p>
        <form id="presentersForm" name="presentersForm" method="post" action="addPresenter.php">
        	<fieldset>
              <legend>Add a Presenter</legend>
              <p>
                <label for="presenter_first_name">First Name:</label> 
                <input type="text" name="presenter_first_name" id="presenter_first_name" value="<?php echo $presenter_first_name;  ?>" />
                <span class="errMsg"><?php echo $firstNameErrMsg; ?></span>
              </p>
              <p>
                <label for="presenter_last_name">Last Name:</label>  
                <input type="text" name="presenter_last_name" id="presenter_last_name" value="<?php echo $presenter_last_name;  ?>" />
                <span class="errMsg"><?php echo $lastNameErrMsg; ?></span>                
              </p>
              <p>
                <label for="presenter_city">City:</label>
                <input type="text" name="presenter_city" id="presenter_city" value="<?php echo $presenter_city;  ?>" />
              </p>
              <p>
                <label for="presenter_st">State:</label> 
                <input type="text" name="presenter_st" id="presenter_st" value="<?php echo $presenter_st;  ?>"/>
              </p>
              <p>
                <label for="presenter_zip">Zip Code:</label> 
                <input type="text" name="presenter_zip" id="presenter_zip" value="<?php echo $presenter_zip;  ?>"/>
                <span class="errMsg"><?php echo $zipErrMsg; ?></span>                
              </p>
              <p>
                <label for="presenter_email">Email Address:</label> 
                <input type="text" name="presenter_email" id="presenter_email" value="<?php echo $presenter_email;  ?>"/>
                <span class="errMsg"><?php echo $emailErrMsg; ?></span>                
              </p>
            </fieldset>
         	<p>
            	<input type="submit" name="submit" id="submit" value="Add Presenter" />
            	<input type="reset" name="button2" id="button2" value="Clear Form" onClick="clearForm()" />
        	</p>      
        </form>
        <?php
			}//end else
        ?>    	
        
        
        
        
        
        
        
  </main>
    
    
    
    <footer>
    	<p>Copyright &copy; <script> var d = new Date(); document.write (d.getFullYear());</script> All Rights Reserved</p>
    
    </footer>




</div>
</body>
</html>
