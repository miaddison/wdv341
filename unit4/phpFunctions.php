<!--Name: Merna Addison
	Course: WDV341 Intro to PHP
	Date: 9/27/17
	Email: merna.addison@gmail.com
-->
<?php 	//Object oriented style
$departmentName = "DMACC Mailing Department";	//global scope variable

try {
    $date = new DateTime();
} catch (Exception $e) {
    echo $e->getMessage();
    exit(1);
}
//Define the function.  THis is the code that will run when the function is called.  No parameters/arguments
function printDepartment()
{
	global $departmentName;	//tells the function to use the global scope version of this variable
	echo $departmentName;	
}
function characterCount($inString)
{
	return 	strlen($inString);	//Provides the number of characters in the input string
}
function trimWhite($inString){
	return trim($inString);		// Trims any leading or trailing withspace	
}
function lowerCase($inString){
	return strtolower($inString); // changes string to all lowercase
}
function findWord($inWord, $inString){
	$inWord = strtolower($inWord);		// take both values to lowercase to account for case
	$inString = strtolower($inString);
	if(strpos($inString, $inWord) !== false)
		return "true";
	else	
		return "false";
	
}
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>WDV341 PHP Functions-Merna Addison</title>
</head>
	<h1> WDV341 Into to PHP</h1>
	<h2>Unit Four-PHP Functions</h2

	<p>1. Create a function that will accept a date input and format it into mm/dd/yyyy format.<br><br>
		<?php echo $date->format('m-d-Y');?><br>
	</p>
	<p>2. Create a function that will accept a date input and format it into dd/mm/yyyy format to use when working with international dates.<br><br>
		<?php echo $date->format('d-m-Y');?><br>
	</p>
	<p>3. Create a function that will accept a string input.  It will do the following things to the string:<br>
	 a. Display the number of characters in the string<br><br>
	 The Department name <strong><?php printDepartment(); ?></strong> has <?php echo characterCount($departmentName); ?> characters.<br><br>
	 b. Trim any leading or trailing whitespace<br><br>
	 <?php
		$str = " Hello World! ";
		echo "Without trim: " . $str;
		echo "<br>";
		echo "With trim: " . trimWhite($str);
	 ?><br><br>
	 c. Display the string as all lowercase characters<br><br>
	 <?php echo lowerCase($departmentName);?><br><br>
	 d. Will display whether or not the string contains "DMACC" either upper or lowercase<br><br>
	 <?php $word = "DMACC"; echo "True or false, is ".$word." contained in ".$departmentName."? "; echo findWord($word, $departmentName);?><br>
	 <?php $word = "dmacc"; echo "True or false, is ".$word." contained in ".$departmentName."? "; echo findWord($word, $departmentName);?><br>
	 <?php $word = "bob"; echo "True or false, is ".$word." contained in ".$departmentName."? "; echo findWord($word, $departmentName);?><br><br>
	</p>

<body>
</body>
</html>