<?php

$dbhost = 'oniddb.cws.oregonstate.edu';
$dbname = 'johnalas-db';
$dbuser = 'johnalas-db';
$dbpass = 'ImuVrMeHo84cXvwd';

$sub = false; //form has not been submitted yet

//connect to database
$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname)
    or die("Error connecting to database server");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
} 

//check for form, get information
if (isset ($_GET['sl']))
{
$sub = true; //form has been submitted
$sl=mysqli_real_escape_string($mysqli,$_GET['sl']);
}

?>

<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>johnalas final</title>
<link rel="stylesheet" type="text/css" href="style.css" />
</head>
<body>
<div class="button"> <a href="index.html"> Go Back</a></div>

<h2>Search by Sale</h2>
<div>
<form action="" method="get" >
<p>Customer Name: </p>
<input type="text" name="sl"/>
<br><br>
<input type="submit" value="search"/>
</form>
<br><br>
<table>
<tr>
<th>Customer</th>
<th>Year</th>
<th>Make</th>
<th>Model</th>
</tr>
<?php
//if form has been submitted, run query on input
if ($sub) {
if(!($stmt = $mysqli->prepare("SELECT customer,year,make,model
FROM sale
WHERE sale.customer='$sl';")))
	{
		 echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	if(!$stmt->execute()){
		echo "<p>Customer not found. Err </p>"  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	if(!$stmt->bind_result($cname,$yr,$mk,$md)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		
	//generate returned table from query
	while($stmt->fetch()){
		echo "<tr><td>" . $cname . "</td><td>" . $yr . "</td><td>" . $mk . "</td><td>" . $md . "</td></tr>";
		}

		$stmt->close();
}
?>
</table>

</body>
</html>