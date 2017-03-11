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
if (isset ($_GET['ename']))
{
$ename=mysqli_real_escape_string($mysqli,$_GET['ename']);
$mgr=$_GET['mgr'];
$dealerid = $_GET['dealer'];

//add input into table
if(!($stmt = $mysqli->prepare("INSERT INTO employee(name,Mgr,d_id) VALUES ('$ename',$mgr,(SELECT dealership_id FROM dealership WHERE dealership_id=$dealerid))")))
	{
		 echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}


echo "<p>Successfully added new employee " . $ename . "!</p><br>";
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
<h2>View/Add Employees</h2>
<form action="" method="get" >
<p>Full Name:<br><input type="text" name="ename"/></p>
<p>Manager?<br><select name="mgr">
<option value=0>No</option>
<option value=1>Yes</option>
</select><br><br>
Dealership:<br>
<select name="dealer">
<?php
//get select choices from database
	 if(!($stmt = $mysqli->prepare("SELECT dealership.dealership_id, dealership.name FROM dealership;"))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		 }
	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	if(!$stmt->bind_result($did, $dname)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	while($stmt->fetch()){
		echo '<option value="' . $did . ' "> ' . $dname . "</option>";
		}
		if (!$sub) {
			$stmt->close();
		}
?>
</select>
</p>
<br>
<input type="submit" value="Add"/>
</form>
<br><br>
<table>
<tr>
<th>Name</th>
<th>Manager</th>
<th>Dealer ID</th>
</tr>
<?php
//display current table
if(!($stmt = $mysqli->prepare("SELECT name,Mgr,d_id
FROM employee")))
	{
		 echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	if(!$stmt->bind_result($en,$mg,$did)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	while($stmt->fetch()){
		if ($mg==0)		$mg="No";
		else 	$mg="Yes";
		echo "<tr><td>" . $en . "</td><td>" . $mg . "</td><td>" . $did . "</td></tr>";
		}

		$stmt->close();

?>
</table>

</body>
</html>