<?php
require 'dbconnect.php';
session_start();
$username =$_SESSION['username'];

$query = "SELECT * FROM `$username`";
$result = mysqli_query($con,$query);
$noofrow = mysqli_num_rows($result);
//$row=mysql_fetch_array($result);
$output="<table> <tr> <td><span>qid</span></td><td><span>question</span></td><td><span>anspiclink</span></td><td><span>answervalue</span></td></tr>";
while($row=mysqli_fetch_array($result))
{
	 $qid = $row['qid'];
	 $question = $row['question'];
	 $anspiclink = $row['anspiclink'];
	 $answervalue = $row['answervalue'];
	 $output = $output."<tr> <td>".$qid."</td><td>".$question."</td><td>".$anspiclink."</td><td>".$answervalue."</td></tr>";
	
	
}
 $output = $output."</table>";
echo $output;
?>