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
if (isset ($_GET['dealername']))
{
$dealername=mysqli_real_escape_string($mysqli,$_GET['dealername']);
$dgoal=$_GET['dgoal'];

//add input into table
if(!($stmt = $mysqli->prepare("INSERT INTO dealership(name,goal) VALUES ('$dealername',$dgoal)")))
	{
		 echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
echo "<p>Successfully added new dealership " . $dealername . "!</p><br>";
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
<h2>View/Add Dealerships</h2>
<form action="" method="get" >
<p>Dealership Name:<br><input type="text" name="dealername"/></p>
<p>Weekly Video Goal:<br><input type="number" name="dgoal"/></p>
<br>
<input type="submit" value="Add"/>
</form>
<br><br>
<table>
<tr>
<th>Name</th>
<th>Goal</th>
</tr>
<?php
//display current table
if(!($stmt = $mysqli->prepare("SELECT name, goal
FROM dealership")))
	{
		 echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	if(!$stmt->bind_result($dname,$goal)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	while($stmt->fetch()){
		echo "<tr><td>" . $dname . "</td><td>" . $goal . "</td></tr>";
		}

		$stmt->close();

?>
</table>

</body>
</html>