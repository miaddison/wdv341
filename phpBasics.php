<!--
	Name: Merna Addison		Date: 9/6/17
	Course: Intro to PHP	Email: merna.addison@gmail.com
	
	Assignment: PHP Basics	
	Create a PHP page for this assignment. Use a combination of PHP, HTML and Javascript to perform the following processes.
	Create a variable called yourName.  Assign it a value of your name.
	Display the assignment name in an h1 element on the page. Include the elements in your output. 
	Use HTML to put an h2 element on the page. Use PHP to display your name inside the element using the variable.
	Create the following variables:  number1, number2 and total.  Assign a value to them.  
	Display the value of each variable and the total variable when you add them together. 
	Use PHP to create a Javascript array with the following values: PHP,HTML,Javascript.  Output this array using PHP.  Create a script that will display the values of this array on your page.  NOTE:  Remember PHP is building the array not running it.  Javascript will use the array once the page is processed and returned to the client's browser where HTML and Javascript will do their thing.-->

<?php
	$yourName = "Merna Addison";		//Create a variable called yourName.  Assign it a value of your name.
	$assignmentName = "<h1>PHP Basics</h1>"; //Display the assignment name in an h1 element on the page. Include the elements in your output.
	$number1 = 4;	//Create the following variables:  number1, number2 and total.  Assign a value to them.
	$number2 = 9;
	$total = $number1 + $number2;
	
	$array = array("PHP", "HTML", "Javascript"); //Use PHP to create a Javascript array with the following values: PHP,HTML,Javascript.  Output this array using PHP. 
	$scriptArray = "";
	foreach($array as $value)		//This will loop through each value in the php array
	{
		$scriptArray .= "'$value', ";	//dispaly the value 
	}
?>
<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>PHP Basics</title>
</head>
<body>
	<?php echo $assignmentName ?> <!--Display the assignment name in an h1 element on the page. Include the elements in your output.-->
	<h2><?php echo $yourName ?></h2> <!--Use HTML to put an h2 element on the page. Use PHP to display your name inside the element using the variable.-->
	<p><?php echo $number1 ?> + <?php echo $number2 ?> = <?php echo $total ?> </p> <!--Display the value of each variable and the total variable when you add them together.-->
	<p id = jsOut></p>
	<script>		/*Javascript will use the array once the page is processed and returned to the client's browser where HTML and Javascript will do their thing.*/
		var jsArray = [<?php echo $scriptArray ?>];
		document.getElementById("jsOut").innerHTML = jsArray;
	</script>
	
</body>
</html>