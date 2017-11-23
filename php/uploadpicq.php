<?php
require 'dbconnect.php';
error_reporting(E_ERROR | E_PARSE);
session_start();
$username =$_SESSION['username'];


$target_dir = $_SERVER['DOCUMENT_ROOT'].'/sbmtimages/';
$imagename = $target_dir . basename($_FILES["fileToUpload"]["name"]);


    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $imagename)) {
       // echo "The file ". basename( $_FILES["fileToUpload"]["name"]). " has been uploaded.";
    } else {
        echo "Sorry, there was an error uploading your file.";
    }

/*
this code is going to access question number and question from html page using ids.
*/
$dochtml = new DOMDocument();
$dochtml->loadHTMLFile('secondpage.html');
if (isset($_POST["1"]))
{
	$qno = "1";
}
if (isset($_POST["2"]))
{
	$qno = "2";
}
$questionelm = $dochtml->getElementById('para'.$qno);
$ques = $questionelm->nodeValue;

/*
this line of code is going to fetch answer of $qno number question from table stored in database in text format.
*/




$query = "SELECT quesanswer FROM answertable WHERE quesno='$qno'"; 
$answer = mysqli_query($con,$query);
$row=mysqli_fetch_array($answer);
$answerforquestion =$row['quesanswer'];




/*
this code will take image of answer uploaded in host server and extract character from it.
*/

$url = 'https://westcentralus.api.cognitive.microsoft.com/vision/v1.0/ocr';
$api_key = '8dc3cc2376234af696b9de978095f76a'; 
$headers = array('Content-Type: application/json', 'Ocp-Apim-Subscription-Key:'.$api_key);
$wer = "https://1661995ashish.000webhostapp.com/sbmtimages/".basename( $_FILES["fileToUpload"]["name"]);
$body = '{"url": "'.$wer.'"}';
$curl = curl_init($url); 
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);


$result = curl_exec($curl);
$sat = json_decode($result);

$out= " ";
	for($i=0; $i<sizeof($sat->regions) ;++$i)
	{
		for($j=0; $j<sizeof($sat->regions[$i]->lines) ;++$j)
	    {
			for($k=0; $k<sizeof($sat->regions[$i]->lines[$j]->words) ;++$k)
	        {
			  $out = $out.$sat->regions[$i]->lines[$j]->words[$k]->text." ";
			
		    }
			
		}
		
	}
	//echo $out;

	/*
	
	this line of code is going to write answer in $username.txt file which will be used to check plag.
	*/
	
	
if($myfile = fopen($_SERVER['DOCUMENT_ROOT'].'/ansfiles/'.$username.'.txt', "r"))  // file name should be according username like $username.txt.
{
$txt = fread($myfile,filesize($_SERVER['DOCUMENT_ROOT'].'/ansfiles/'.$username.'.txt'));
fclose($myfile);
 
$myfile1 = fopen($_SERVER['DOCUMENT_ROOT'].'/ansfiles/'.$username.'.txt', "w");
$est = $txt." ".$out;
fwrite($myfile1, $est);

fclose($myfile1);
}
else
{
$myfile1 = fopen($_SERVER['DOCUMENT_ROOT'].'/ansfiles/'.$username.'.txt', "w");
$est = $out;
fwrite($myfile1, $est);

fclose($myfile1);
}
	
	/*
	this part of code will send extracted data from image and stored in variable $out to azure ml studio where R script will evaluate
	answer and will send numerical value back between 0 to 100.
	*/
	
	
 $data = array(
  'Inputs'=> array(
      'input1'=> array(
          'ColumnNames' => array("TEXT", "TEXT1"),
          'Values' => array( array($answerforquestion ,$out))
      ),
  ),
  'GlobalParameters'=> null
);

$body = json_encode($data);

$url = 'https://ussouthcentral.services.azureml.net/workspaces/f8f1a965407844eca136fe841540357c/services/1d210306b7ad4851b736519711b14bf6/execute?api-version=2.0&details=true';
$api_key = 'bOYJ0U1dhr7QWiWUMTgwZSrKClVI+pZ8i0eB4eGctN5qMrTBtC8H+ExErHoN4JPi6HhyrIOYkWGFz6qniD9YZg=='; 
$headers = array('Content-Type: application/json', 'Authorization:Bearer ' . $api_key, 'Content-Length: ' . strlen($body));

//$this->responseArray['body'] = $body;


$curl = curl_init($url); 
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);


$result = curl_exec($curl);
$data = json_decode($result);
$evaluatedscore = $data->Results->output1->value->Values[0][0];
//echo $evaluatedscore;

/*
this line of code is going to insert question number, question, answer image url, evaluated score of username table.
*/	
$imagelink = "https://1661995ashish.000webhostapp.com/sbmtimages/".basename( $_FILES["fileToUpload"]["name"]);	
//echo $imagelink;
$insert_path="INSERT INTO `$username`(qid,question,anspiclink,answervalue) VALUES('$qno','$ques','$imagelink','$evaluatedscore')";
 $var=mysqli_query($con,$insert_path);
 if($var)
 {
	echo "Answer has successfully uploaded and evaluated go back to upload next answer.";
 }
 else
	 echo "Some error occurred during evaluation and file uploading try again, go back.";

  


?>