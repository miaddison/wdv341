<!--
	Name: Merna Addison
	Date: 10-13-17
	Email: merna.addison@gmail.com
-->
<?php
session_start();
//if ($_SESSION['validUser'] == "yes")	//If this is a valid user allow access to this page
//{
		
	//Setup the variables used by the page
		//field data
		$name = "";
		$socSec = "";
		$response = "";
		
		//error messages
		$nameErrMsg = "";
		$socSecErrMsg = "";
		$responseErrMsg = "";
		
		
		$validForm = false;
				
	if(isset($_POST["submit"]))
	{	
		//The form has been submitted and needs to be processed
		
		
		//Validate the form data here!
	
		//Get the name value pairs from the $_POST variable into PHP variables
		//This example uses PHP variables with the same name as the name atribute from the HTML form
		$name = $_POST['inName'];
		$socSec = $_POST['inSocSec'];
		$response = $_POST['inResponse'];
		

		//VALIDATION FUNCTIONS		Use functions to contain the code for the field validations.  
			function validateName($name)
			{
				global $validForm, $nameErrMsg;		//Use the GLOBAL Version of these variables instead of making them local
				$nameErrMsg = "";
				$name = trim($name); // remove any leading space
				
				if($name == "") // not blank and no leading space
				{
					$validForm = false;
					$nameErrMsg = "First name cannot be spaces";
				}
			}//end validateName()

			function validateSocSec($socSec)	
			{
				global $validForm, $socSecErrMsg;		// Use the GLOBAL Version of these variables instead of making them local
				$socSecErrMsg = "";

				if(!preg_match("/^[1-9][0-9]*$/", $socSec))	// Must be only numeric, 9 digits, Use Regular Expression 
				{
					$validForm = false;
					$socSecErrMsg = "Your social security number should only contain 9 digits. ";
				}
			}//end validateSocSec()	
			
			Function validateResponse($response)
			{
				global $validForm, $responseErrMsg;		// Use the GLOBAL Version of these variables instead of making them local
				
				if($response == ""){	// One must be selected.
					$validForm = false;
					$responseErrMsg = "Please choose a response.";
				}
				
			}
		
		//VALIDATE FORM DATA  using functions defined above
		$validForm = true;		//switch for keeping track of any form validation errors
		
		validateName($name);
		validateSocSec($socSec);
		validateResponse($response);	
	}
	else
	{
		//Form has not been seen by the user.  display the form
	}
	


?>
<!DOCTYPE html>
<html >
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WDV341 Intro PHP - Form Validation Example</title>
<style>

#orderArea	{
	width:600px;
	background-color:#CF9;
}

.error	{
	color:red;
	font-style:italic;	
}
</style>
</head>

<body>
<h1>WDV341 Intro PHP</h1>
<h2>Form Validation Assignment


</h2>
<div id="orderArea">
  <form id="form1" name="form1" method="post" action="formValidationAssignment.php">
  <h3>Customer Registration Form</h3>
  <table width="587" border="0">
    <tr>
      <td width="117">Name:</td>
      <td width="246"><input type="text" name="inName" id="inName" size="40" value=""/></td>
      <td width="210" class="error"> <?php echo $nameErrMsg;?></td>
    </tr>
    <tr>
      <td>Social Security</td>
      <td><input type="text" name="inSocSec" id="inSocSec" size="40" value="" /></td>
      <td class="error"><?php echo $socSecErrMsg;?></td>
    </tr>
    <tr>
      <td>Choose a Response</td>
      <td><p>
        <label>
          <input type="radio" name="inResponse" id="inResponse_Phone" value = "Phone">
          Phone</label>
        <br>
        <label>
          <input type="radio" name="inResponse" id="inResponse_Email" value = "Email">
          Email</label>
        <br>
        <label>
          <input type="radio" name="inResponse" id="inResponse_US_Mail" value = "US_Mail">
          US Mail</label>
        <br>
      </p></td>
      <td class="error"><?php echo $responseErrMsg; ?></td>
    </tr>
  </table>
  <p>
    <input type="submit" name="submit" id="button" value="Register" />
    <input type="reset" name="button2" id="button2" value="Clear Form" />
  </p>
</form>
</div>

</body>
</html>