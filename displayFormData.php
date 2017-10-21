<?php

	foreach($_POST as $key => $value){ 
		echo $key . $value;
		echo "<br>";
	}

	$inColor = $_POST["colors"];
	$inName = $_POST["inputName"];
	$inLanguage = $_POST["language"];

	echo "<p>Color: $inColor</p>";     // can't call global like this
	echo "<p>Name: $inName</p>";
	echo "<p>Technology: $inLanguage</p>";

	// Could do this though but don't have the variables to call later if needed
	//echo "<p>Color: " . $_POST["colors"] . "</p>";

	

?>


<label>For more information:
  		<select name="options">
  			<option value="1"><?php echo $inColor ?></option>
  			<option value="2"><?php echo $inName ?></option>
  			<option value="3"><?php echo $inLanguage ?></option>
		</select>
	  </label>
<br>	  
<label>For more information:
  		<select name="options">
  			<?php
				foreach($_POST as $key => $value)		// loose control here and submit shows
				{	
					echo "<option>";
					echo $value;
					echo "</option>";
				}
			?>
		</select>
	  </label>