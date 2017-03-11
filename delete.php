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
if (isset ($_GET['rid']))
{
$rid = $_GET['rid'];

//delete selected review upon button press
if(!($stmt = $mysqli->prepare("DELETE FROM review WHERE review_id=$rid")))
	{
		 echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
else
echo "<p>Successfully deleted review!</p><br>";
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
<h2>Delete Reviews</h2>
<form action="" method="get" style="background-color:#FFFFFF;border:none;border-collapse:collapse;padding:0 0 0 0;">
<table>
<tr>
<th>Name</th>
<th>Customer</th>
<th>Date</th>
<th>Delete?</th>
</tr>
<?php
//get list of reviews from database
if(!($stmt = $mysqli->prepare("SELECT sale.customer, review.review_id, site.name,review.date
FROM review
INNER JOIN sale ON sale.sale_id=review.s_id
INNER JOIN site ON site.site_id=review.t_id")))
	{
		 echo "Prepare failed: "  . $stmt->errno . " " . $stmt->error;
	}
	if(!$stmt->execute()){
		echo "Execute failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	if(!$stmt->bind_result($cust,$rid,$sname,$date)){
		echo "Bind failed: "  . $mysqli->connect_errno . " " . $mysqli->connect_error;
		}
	//generate returned table from query, including button for deletion
	while($stmt->fetch()){
		echo "<tr><td>" . $cust . "</td><td>" . $sname . "</td><td>" . $date . '</td><td style="border-collapse:collapse; text-align:center;"><button style="width:30px; border:none;" type="submit" name=rid value="'. $rid . '"> X </button> </td></tr>';
		}

		$stmt->close();

?>
</table>
<form>
</body>
</html>