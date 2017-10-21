<?php
	
	$className = "limegreen";
	
	//$error = true;

	$error = false;

	/*if($error){
		$className = "red";
	}else{
		$className = "";
	}*/

	function processMessage($inError){
		
		global $className;
		/*global $error;*/
		
		if($inError){
			$className = "red";
		}else{
			$className = "";
		}
	}

	processMessage($error); //calls function

?>

<!doctype html>
<html>
<head>
<meta charset="UTF-8">
<title>Untitled Document</title>

<style>
	.red {color:red;}
	.limegreen {color:limegreen;}
</style
	
</head>
	
	
	<!--p </*?php echo "class='red'";? */>>This is a paragraph.</p-->

	<p <?php echo "class='red'";?>>This is a paragraph.</p>
	
	<p <?php echo "class='$className'";?>>This is another paragraph.</p>

<body>
</body>
</html>