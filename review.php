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

//check for form submission, set values
if (isset ($_GET['cname']))
{
$cname=mysqli_real_escape_string($mysqli,$_GET['cname']);
$site=$_GET['site'];

//add input into table
if(!($stmt = $mysqli->prepare("INSERT INTO review(t_id,s_id) VALUES ((SELECT site_id FROM site WHERE site_id=$site), (SELECT sale_id FROM sale WHERE customer='$cname'))")))
	{
		 echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	if(!$stmt->execute()){
		echo '<p>Customer does not exist. Please view <a style="color:blue;" href="sale.php"> Sales »</a>.</p>'  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
else
echo "<p>Successfully added new review!</p><br>";
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
<h2>View/Add Reviews</h2>
<form action="" method="get" >
<p>Customer Name:<br><input type="text" name="cname"/></p>
<p>Site:<br>
<select name="site">
<?php
//get select choices from database
	 if(!($stmt = $mysqli->prepare("SELECT name, site_id FROM site;"))){
		echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
		 }
	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	if(!$stmt->bind_result($sname, $sid)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	while($stmt->fetch()){
		echo '<option value="' . $sid . ' "> ' . $sname . "</option>";
		}
?>
</select>
<br>
</p>
<input type="submit" value="Add"/>
</form>
<br><br>
<table>
<tr>
<th>Name</th>
<th>Customer</th>
<th>Date</th>
</tr>
<?php
//display current table
if(!($stmt = $mysqli->prepare("SELECT site.name,sale.customer,review.date
FROM review
INNER JOIN sale ON sale.sale_id=review.s_id
INNER JOIN site ON site.site_id=review.t_id")))
	{
		 echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	if(!$stmt->bind_result($sname,$cust,$date)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	while($stmt->fetch()){
		echo "<tr><td>" . $sname . "</td><td>" . $cust . "</td><td>" . $date . "</td></tr>";
		}

		$stmt->close();

?>
</table>

</body>
</html>