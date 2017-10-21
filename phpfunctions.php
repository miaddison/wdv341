<p><?php echo date("n/j/y"); ?></p>
	
<p><?php
	
	$deadline = mktime(0,0,0,9,30,2017);

	echo date("n/d/Y", $deadline); ?></p>
	
	
	
<p>Object oriented style<br>
<?php 	//Object oriented style
try {
    $date = new DateTime('2000-01-01');
} catch (Exception $e) {
    echo $e->getMessage();
    exit(1);
}
echo $date->format('Y-m-d');
?></p>

<p>Procedural style<br>
<?php	//Procedural style
$date = date_create('2000-01-01');
if (!$date) {
    $e = date_get_last_errors();
    foreach ($e['errors'] as $error) {
        echo "$error\n";
    }
    exit(1);
}
echo date_format($date, 'Y-m-d');
?></p>

<?php 	//Object oriented style
try {
    $date = new DateTime('2000-01-01');
} catch (Exception $e) {
    echo $e->getMessage();
    exit(1);
}

try {
    $date1 = new DateTime();
} catch (Exception $e) {
    echo $e->getMessage();
    exit(1);
}

try {
    $date2 = new DateTime('2017-12-6');
} catch (Exception $e) {
    echo $e->getMessage();
    exit(1);
}

try {
    $date3 = new DateTime();
    $date3->modify('+1 day');
} catch (Exception $e) {
    echo $e->getMessage();
    exit(1);
}

try {			//Relative formatting
    $date4 = new DateTime('tomorrow');
} catch (Exception $e) {
    echo $e->getMessage();
    exit(1);
}

try {			//Relative formatting
    $date5 = new DateTime('+7 days');
} catch (Exception $e) {
    echo $e->getMessage();
    exit(1);
}

try {			//Relative formatting
    $date6 = new DateTime('-7 days');
} catch (Exception $e) {
    echo $e->getMessage();
    exit(1);
}


function changeDate($inDate, $inFormat){
	try {		
    	$date = new DateTime($inDate);
    	echo $date->format($inFormat);
	} catch (Exception $e) {
    	echo $e->getMessage();
    	exit(1);
	}
}
?></p>
<p>output todays date in text<br>
<?php echo $date->format('Y-m-d');?><br>
<?php echo $date1->format('l, F jS, Y');?><br>
<?php echo $date2->format('m-d-Y');?><br>
tomorrow's date using modify<br>
<?php echo $date3->format('m-d-Y');?><br>
tomorrow's date using relative formatting<br>
<?php echo $date4->format('m-d-Y');?><br>
Next week's date<br>
<?php echo $date5->format('m-d-Y');?><br>
Last week's date<br>
<?php echo $date6->format('m-d-Y');?><br>
Last week's date passed into method<br>
<?php changeDate('-7 days', 'm-d-Y');?><br>
</p>
