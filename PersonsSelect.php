<?php
require_once('./library.php');
$con = new mysqli($SERVER, $USERNAME, $PASSWORD, $DATABASE);
// Check connection
if (mysqli_connect_errno()) {
echo("Can't connect to MySQL Server. Error code: " .
mysqli_connect_error());
return null;
}
// Form the SQL query (a SELECT query)
$sql="SELECT * FROM proj_job";
$result = mysqli_query($con,$sql);
// Print the data from the table row by row
echo "<table border=1><th>Job Title</th><th>Employer Name</th><th>Hours per Week</th><th>Hourly Wages</th><th>Location</th>\n";
while($row = mysqli_fetch_array($result)) {
    $jobtitle=$row['title'];
    $ename=$row['name'];
    $hours = $row['hrs'];
    $wages = $row['wages'];
    $location = $row['location'];
    echo "<tr><td>$jobtitle</td><td>$ename</td><td>$hours</td><td>$wages</td><td>$location</td></tr>";
}
echo "</table>";
mysqli_close($con);
?>