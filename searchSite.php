<?php

$dbhost = 'oniddb.cws.oregonstate.edu';
$dbname = 'johnalas-db';
$dbuser = 'johnalas-db';
$dbpass = 'ImuVrMeHo84cXvwd';

$sub = false;//form has not been submitted yet

//connect to database
$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname)
    or die("Error connecting to database server");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
} 
//check for form, get information
if (isset ($_GET['st']))
{
$sub = true;
$st=$_GET['st'];
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

<h2>Search by Site</h2>
<div>
<form action="" method="get" >
<select name="st">
<?php
//get choices from database
	 if(!($stmt = $mysqli->prepare("SELECT site_id,name FROM site;"))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		 }
	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	if(!$stmt->bind_result($sid, $sname)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	//generate returned table from query
	while($stmt->fetch()){
		echo '<option value="' . $sid . ' "> ' . $sname . "</option>";
		}
		if (!$sub) {
			$stmt->close();
		}
?>
</select>
<br><br>
<input type="submit" value="search"/>
</form>
<br><br>
<table>
<tr>
<th>Site</th>
<th>Employee</th>
<th>Customer</th>
</tr>
<?php
//if form has been submitted, run query on input
if ($sub) {
if(!($stmt = $mysqli->prepare("SELECT site.name, employee.name, sale.customer
FROM review
INNER JOIN site ON site.site_id = review.t_id
INNER JOIN sale ON sale.sale_id = review.s_id
INNER JOIN employee ON employee.employee_id = sale.e_id
WHERE site.site_id=$st;")))
	{
		 echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	if(!$stmt->bind_result($sname,$emp,$customer)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	//generate returned table from query
	while($stmt->fetch()){
		echo "<tr><td>" . $sname . "</td><td>" . $emp . "</td><td>" . $customer . "</td></tr>";
		}

		$stmt->close();
}
?>
</table>

</body>
</html>