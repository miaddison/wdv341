// JavaScript Document
function resetForm(){
		document.getElementById('meal_mealname').value = "";
		document.getElementById('meal_ingredients').value = "";
		document.getElementById('meal_directions').value = "";
		//document.getElementById('event_date').value = "";
		//document.getElementById('event_time').value = "";
}
function printDiv(divID) {
            //Get the HTML of div
            var divElements = document.getElementById(divID).innerHTML;
            //Get the HTML of whole page
            var oldPage = document.body.innerHTML;

            //Reset the page's HTML with div's HTML only
            document.body.innerHTML = 
              "<html><head><title></title></head><body>" + 
              divElements + "</body>";

            //Print Page
            window.print();

            //Restore orignal HTML
            document.body.innerHTML = oldPage;
}
function checkDelete(){
	return confirm('Are you sure you want to delete this?');
}
	function addFields(){
     	// Number of inputs to create
     	var number = document.getElementById("num_meal_ingredients").value;
		var number2 = document.getElementById("num_meal_directions").value;
     	// Container <div> where dynamic content will be placed
     	var container = document.getElementById("jscontainer");
     	// Clear previous contents of the container
		while (container.hasChildNodes()) {
			container.removeChild(container.lastChild);
     	}
     	container.appendChild(document.createElement("br"));
     	for (i=0;i<number;i++){
			// Append a node with a random text
			container.appendChild(document.createTextNode("Ingredient " + (i+1) + ": "));
			// Create an <input> element, set its type and name attributes
			var input = document.createElement("input");
			input.type = "text";
			input.name = ("meal_ingredients".concat(i+1));
			container.appendChild(input);
			container.appendChild(document.createElement("br"));
			container.appendChild(document.createElement("br"));
    	}
    	for (i=0;i<number2;i++){
			// Append a node with a random text
			container.appendChild(document.createTextNode("Step " + (i+1) + ": "));
			// Create an <input> element, set its type and name attributes
			var input = document.createElement("input");
        	input.type = "text";
        	input.name = ("meal_directions".concat(i+1));
        	container.appendChild(input);
			container.appendChild(document.createElement("br"));
			container.appendChild(document.createElement("br"));
    	}
	}
	/*function addRow(tableID) {
		var table = document.getElementById(tableID);
		var rowCount = table.rows.length;
		if(rowCount < 5){                            // limit the user from creating fields more than your limits
			var row = table.insertRow(rowCount);
			var colCount = table.rows[0].cells.length;
			for(var i=0; i <colCount; i++) {
				var newcell = row.insertCell(i);
				newcell.innerHTML = table.rows[0].cells[i].innerHTML;
			}
		}else{
			 alert("Maximum Passenger per ticket is 5");

		}
	}

	function deleteRow(tableID) {
		var table = document.getElementById(tableID);
		var rowCount = table.rows.length;
		for(var i=0; i<rowCount; i++) {
			var row = table.rows[i];
			var chkbox = row.cells[0].childNodes[0];
			if(null != chkbox && true == chkbox.checked) {
				if(rowCount <= 1) {               // limit the user from removing all the fields
					alert("Cannot Remove all the Passenger.");
					break;
				}
				table.deleteRow(i);
				rowCount--;
				i--;
			}
		}*/
	