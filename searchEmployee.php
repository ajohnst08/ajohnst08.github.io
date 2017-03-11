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
if (isset ($_GET['emp']))
{
$sub = true;
$ein=$_GET['emp'];
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

<h2>Search by Employee</h2>
<div>
<form action="" method="get" >
<select name="emp">
<?php
//get choices from database
	 if(!($stmt = $mysqli->prepare("SELECT employee_id,name FROM employee ORDER BY d_id;"))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		 }
	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	if(!$stmt->bind_result($eid, $ename)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	//generate returned table from query
	while($stmt->fetch()){
		echo '<option value="' . $eid . ' "> ' . $ename . "</option>";
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
<th>Employee</th>
<th>Site</th>
<th>Customer</th>
</tr>
<?php
//if form has been submitted, run query on input
if ($sub) {
if(!($stmt = $mysqli->prepare("SELECT employee.name, site.name, sale.customer
FROM employee
INNER JOIN sale ON sale.e_id = employee.employee_id
LEFT JOIN review ON review.s_id = sale.sale_id
INNER JOIN site ON site.site_id = review.t_id
WHERE employee.employee_id=$ein;")))
	{
		 echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	if(!$stmt->bind_result($ename,$site,$customer)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	while($stmt->fetch()){
		echo "<tr><td>" . $ename . "</td><td>" . $site . "</td><td>" . $customer . "</td></tr>";
		}

		$stmt->close();
}
?>
</table>

</body>
</html>