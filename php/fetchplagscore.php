<?php

session_start();
$username =$_SESSION['username'];
$username $username.".txt";
$data = array(
  'Inputs'=> array(
      'input1'=> array(
          'ColumnNames' => array("uname"),
          'Values' => array( array($username))
      ),
  ),
  'GlobalParameters'=> null
);

$body = json_encode($data);

$url = 'https://ussouthcentral.services.azureml.net/workspaces/f8f1a965407844eca136fe841540357c/services/8dc08c6b5d0d41f8b27c09ab49f00709/execute?api-version=2.0&details=true';
$api_key = 'NA67FDAg/i4tGBEzb1JNHgyWHLm5xoqmjHExD2HrT0DkuwV6hJ4tMzMTCTHRp2ykvYcfWk5TABcQsXQN2XCNHg=='; 
$headers = array('Content-Type: application/json', 'Authorization:Bearer ' . $api_key, 'Content-Length: ' . strlen($body));

//$this->responseArray['body'] = $body;


$curl = curl_init($url); 
curl_setopt($curl, CURLOPT_FOLLOWLOCATION, TRUE);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($curl, CURLOPT_POSTFIELDS, $body);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$output="<table> <tr> <td>document</td><td>plagiarism</td></tr>";
$result = curl_exec($curl);
$data = json_decode($result);

for($i=1; $i<sizeof($data->Results->output1->value->Values) ;++$i)
	{
		    $output = $output."<tr> <td>".$data->Results->output1->value->Values[$i][0]."</td>";
		
			$v =$data->Results->output1->value->Values[$i][1];
			$c =$data->Results->output1->value->Values[$i][2];
		    if($v>$c)
			{
				$output = $output."<td>".$v."</td></tr>";
			}
			else
			{
				$output = $output."<td>".$c."</td></tr>";
			}
	}
	$output = $output."</table>";
	echo $output;

?>