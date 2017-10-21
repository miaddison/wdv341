<?php
$departmentName = "DMACC Mailing Department";	//global scope variable

$name1 = "Jane";		//global scope variable
$name2 = "Smith";		//global scope variable

//Define the function.  THis is the code that will run when the function is called.  No parameters/arguments
function printDepartment()
{
	global $departmentName;	//tells the function to use the global scope version of this variable
	echo $departmentName;	
}


//Define the function.  Note this function expects one piece of information to be sent to it.  The local variable $inName will contain the
// value that is passed into the function when the function is called. 
function printName($inName)
{
	echo $inName;	//NOTE $inName is a Local scopre variable defined within this function.  It is gone when the function ends
}

//This function has two parameters/arguments.  It expects two pieces of information to be passed to when it is called.  The first piece of information
//will be stored in the local variable $inFirstName and the second piece of information will be stored in the $inLastName.  The order of the pieces of 
//information will determine where the value is stored. 
function printFullName($inFirstName,$inLastName)
{
	echo $inFirstName . " " . $inLastName;
}


function printNameListing($inFirstName,$inLastName)
{
	echo $inLastName . ", " . $inFirstName;	
}

function characterCount($inString)
{
	return 	strlen($inString);	//Provides the number of characters in the input string
}

function todaysDate()
{
	$mydate=getdate(date("U"));
	return date('l F, d Y');	//Should format the output as Monday January 1, 2016
}


?>
<!DOCTYPE html >
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>WDV341 Intro PHP Function Examples</title>
</head>

<body>
<h1>WDV341 Intro PHP</h1>
<h2>PHP Functions - Example Code</h2>
<p>&nbsp;</p>
<h3>Example Letter 1</h3>
<p>Dear <?php printFullName("Mary", "Smith"); //Calling or activating the function ?> </p>
<p>Please send us your address so that we may mail you your degree award. </p>
<p>Thank You</p>
<p><?php printDepartment() ?></p>
<p>&nbsp;</p>
<h3>Example Letter 2</h3>
<p>Dear <?php printFullName("Anderson","Mike"); //Note PHP takes the information in the order YOU provide. It does not try to fix your mistake.?> </p>
<p>Please send us your address so that we may mail you your degree award. </p>
<p>Thank You</p>
<p><?php printDepartment() ?></p>
<p>&nbsp;</p>
<h3>Example Letter 3</h3>
<p>Dear <?php printFullName($name1,$name2); //You can pass the value of a variable as well.  The value stored in the variable is sent to the function?> </p>
<p>Please send us your address so that we may mail you your degree award. </p>
<p>Thank You</p>
<p><?php printDepartment() ?></p>
<p>&nbsp;</p>
<h3>Example Letter 4</h3>
<p><?php printDepartment() ?></p>
<p>The following people have been contacted.</p>
<p><?php echo printNameListing("Mary","Smith"); ?></p>
<p><?php echo printNameListing("Mike","Anderson"); ?></p>
<p><?php echo printNameListing($name1,$name2); ?></p>
<p>Thank You</p>
<h3>String functions Example
</h3>
<p>The Department name <strong><?php printDepartment(); ?></strong> has <?php echo characterCount($departmentName); ?> characters. 
<h3>Date functions Example
</h3>
<p>Today is: <?php echo getdate(); ?></p>
<p>Today is: <?php print_r( getdate()) ; ?></p>
<p>Today is: <?php echo todaysDate(); ?></p>
<p>&nbsp;</p>
</body>
</html>