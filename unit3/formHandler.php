<?php
//Model-Controller Area.  The PHP processing code goes in this area. 


	//Method 1.  This uses a loop to read each set of name-value pairs stored in the $_POST array
	$tableBody = "";		//use a variable to store the body of the table being built by the script
	
	foreach($_POST as $key => $value)		//This will loop through each name-value in the $_POST array
	{
		$tableBody .= "<tr>";				//formats beginning of the row
		$tableBody .= "<td>$key</td>";		//dsiplay the name of the name-value pair from the form
		$tableBody .= "<td>$value</td>";	//display the value of the name-value pair from the form
		$tableBody .= "</tr>";				//End this row
		$tableBody .= "\n";
		$tableBody .= "\t";
	} 
	
	
	//Method 2.  This method pulls the individual name-value pairs from the $_POST using the name
	//as the key in an associative array.  
	
	$inFirstName = $_POST["firstName"];		//Get the value entered in the first name field
	$inLastName = $_POST["lastName"];		//Get the value entered in the last name field
	$inSchool = $_POST["school"];			//Get the value entered in the school field
	$inGender = $_POST["gender"];			//Get the value entered for gender
	$inEmployment1 = $_POST["employment1"];   //Get the value entered for employment
	$inEmployment2 = $_POST["employment2"];   //Get the value entered for employment
	$inGraduation = $_POST["graduation"];   //Get the value entered for graduation

?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WDV 341 Intro PHP - Code Example</title>
</head>

<body>
<h1>WDV341 Intro PHP</h1>
<h2>Form Handler Result Page - Code Example</h2>
<p>This page displays the results of the Server side processing. </p>
<p>The PHP page has been formatted to use the Model-View-Controller (MVC) concepts. </p>
<h3>Display the values from the form using Method 1. Uses a loop to process through the $_POST array</h3>
<p>
	<table border='a'>
    <tr>
    	<th>Field Name</th>
        <th>Value of Field</th>
    </tr>
	<?php echo $tableBody;  ?>
	</table>
</p>
<h3>Display the values from the form using Method 2. Displays the individual values.</h3>
<p>School: <?php echo $inSchool; ?></p>
<p>First Name: <?php echo $inFirstName; ?></p>
<p>Last Name: <?php echo $inLastName; ?></p>
<p>Gender: <?php echo $inGender; ?></p>
<p>Employment1: <?php echo $inEmployment1; ?></p>
<p>Employment2: <?php echo $inEmployment2; ?></p>
<p>Graduation: <?php echo $inGraduation; ?></p>



</body>
</html>
