<?php

$dbhost = 'oniddb.cws.oregonstate.edu';
$dbname = 'johnalas-db';
$dbuser = 'johnalas-db';
$dbpass = 'ImuVrMeHo84cXvwd';

//connect to database
$mysqli = new mysqli($dbhost, $dbuser, $dbpass, $dbname)
    or die("Error connecting to database server");

if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
} 

//check for form submission
if (isset ($_GET['cname']))
{
$cname=mysqli_real_escape_string($mysqli,$_GET['cname']);
$year=$_GET['yr'];
$make=mysqli_real_escape_string($mysqli,$_GET['mk']);
$model=mysqli_real_escape_string($mysqli,$_GET['mod']);
$emid = $_GET['emp'];

//add input into table
if(!($stmt = $mysqli->prepare("INSERT INTO sale(customer,year,make,model,e_id) VALUES ('$cname',$year,'$make','$model',(SELECT employee_id FROM employee WHERE employee_id=$emid))")))
	{
		 echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}


echo "<p>Successfully added new sale for " . $cname . "!</p><br>";
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
<h2>View/Add Sale</h2>
<form action="" method="get" >
Employee:<br>
<select name="emp">
<?php
//get select choices from database
	 if(!($stmt = $mysqli->prepare("SELECT name, employee_id FROM employee;"))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		 }
	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	if(!$stmt->bind_result($ename, $eid)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	while($stmt->fetch()){
		echo '<option value="' . $eid . ' "> ' . $ename . "</option>";
		}
?>
</select>
<p>Cutomer First Name:<br><input type="text" name="cname"/></p>
<p>Purchased </p>
<p>Year: <input type="number" name="yr"/></p>
<p>Make: <input type="text" name="mk"/></p>
<p>Model: <input type="text" name="mod"/></p>
<br>
<input type="submit" value="Add"/>
</form>
<br><br>
<table>
<tr>
<th>Name</th>
<th>Year</th>
<th>Make</th>
<th>Model</th>
</tr>
<?php
//display current table
if(!($stmt = $mysqli->prepare("SELECT customer, year, make, model
FROM sale")))
	{
		 echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	if(!$stmt->bind_result($cust,$year,$make,$model)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	while($stmt->fetch()){
		echo "<tr><td>" . $cust . "</td><td>" . $year . "</td><td>" . $make .  "</td><td>" . $model . "</td></tr>";
		}

		$stmt->close();

?>
</table>

</body>
</html>