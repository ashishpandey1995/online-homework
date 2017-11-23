<?php
require 'dbconnect.php';
session_start();
$_SESSION['con']='fail';
$username = $_GET['email'];
$_SESSION['username']=$username;
$_SESSION['con']='success'; 

/*
$query = "SELECT username FROM student WHERE username='$username'"; 
$answer = mysqli_query($con,$query);
$noofrow = mysqli_num_rows($answer);
if($noofrow!=0)
{*/

$insert_path="INSERT INTO student(username) VALUES('$username')"; 
mysqli_query($con,$insert_path);
/*
// to maintain .csv file on server.
$array = array_map('str_getcsv', file("eduimages/username.csv"));

$list = array (
    array($username),
    
);

$fp = fopen("eduimages/username.csv", 'w');

foreach ($list as $fields) {
    fputcsv($fp, $fields);
}
foreach ($array as $fields) {
    fputcsv($fp, $fields);
}

fclose($fp);
// to maintain username.csv file.
*/
$sqltablecreatedforuser = "CREATE TABLE `$username`(
qid Integer NOT NULL PRIMARY KEY,                                          
question VARCHAR(100),                                
anspiclink VARCHAR(150),
answervalue VARCHAR(50) 
)";


$result = mysqli_query($con,$sqltablecreatedforuser);

if($result)
{
 header('Location: secondpage.html');
}
else
	echo "fail";
/*}
else{
	 header('Location: secondpage.html');
}*/

?>