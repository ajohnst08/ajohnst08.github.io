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
if (isset ($_GET['dealership']))
{
$sub = true;
$din=$_GET['dealership'];
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

<h2>Search by Dealership</h2>
<div>
<form action="" method="get" >
<select name="dealership">
<?php
//get choices from database
	 if(!($stmt = $mysqli->prepare("SELECT dealership.dealership_id, dealership.name FROM dealership;"))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		 }
	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	if(!$stmt->bind_result($did, $dname)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
		//generate returned table from query	
	while($stmt->fetch()){
		echo '<option value="' . $did . ' "> ' . $dname . "</option>";
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
<th>Dealership</th>
<th>Employee</th>
<th>Site</th>
<th>Sale ID</th>
</tr>
<?php
//if form has been submitted, run query on input
if ($sub) {
if(!($stmt = $mysqli->prepare("SELECT dealership.name, employee.name, site.name, sale.sale_id
FROM dealership
INNER JOIN employee ON employee.d_id = dealership.dealership_id
INNER JOIN sale ON sale.e_id = employee.employee_id
LEFT JOIN review ON review.s_id = sale.sale_id
INNER JOIN site ON site.site_id = review.t_id
WHERE dealership.dealership_id =  $din;")))
	{
		 echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	if(!$stmt->bind_result($dname,$ename,$sname,$s_id)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	//generate returned table from query
	while($stmt->fetch()){
		echo "<tr><td>" . $dname . "</td><td>" . $ename . "</td><td>" . $sname . "</td><td>" . $s_id . "</td></tr>";
		}

		$stmt->close();
}
?>
</table>

</body>
</html>