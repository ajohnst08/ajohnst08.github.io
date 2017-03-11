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
if (isset ($_GET['sname']))
{
$sname=mysqli_real_escape_string($mysqli,$_GET['sname']);
$val=$_GET['dollar'];

//add input into table
if(!($stmt = $mysqli->prepare("INSERT INTO site(name,value) VALUES ('$sname',$val)")))
	{
		 echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}

echo "<p>Successfully added new site " . $sname . "!</p><br>";
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
<h2>View/Add Site</h2>
<form action="" method="get" >
<p>Site Name:<br><input type="text" name="sname"/></p>
<p>Review Value:<br><input type="number" name="dollar"/></p>
<br>
<input type="submit" value="Add"/>
</form>
<br><br>
<table>
<tr>
<th>Name</th>
<th>Value</th>
</tr>
<?php
//display current table
if(!($stmt = $mysqli->prepare("SELECT name,value
FROM site")))
	{
		 echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	if(!$stmt->bind_result($sname,$val)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	while($stmt->fetch()){
		echo "<tr><td>" . $sname . "</td><td>$" . $val . "</td></tr>";
		}

		$stmt->close();

?>
</table>

</body>
</html>