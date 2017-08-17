<?php
require 'helpers.php';

$cloudURL = 'https://demo.reporting.perfectomobile.com';
$startDate = '2017/08/17 08:00:00';


($token = file_get_contents('../reportingToken.txt'))|| die ("Could not load token file: ../reportingToken.txt\n");

$urlValidate = 'https://xpathvalidator.herokuapp.com/validateNew';
$urlValidateTarget = 'https://xpathvalidator.herokuapp.com/?bdata=';

$outPutFN = "./results/" . hash('md5', time()) . "_xPathValidation_ReportingScan.html";
if (!is_dir('./results')) {
    mkdir('./results');
}

$outPut = fopen($outPutFN, "w") or die("Unable to open file!");
$htmlBegin = htmlBegin();

fwrite($outPut, $htmlBegin);
$htmlOut = "";

date_default_timezone_set("GMT");
$startTime = strtotime($startDate) * 1000;

$listExecutionsURL = $cloudURL . '/export/api/v1/test-executions?startExecutionTime[0]=' . $startTime; 

$executions = queryReports($listExecutionsURL, $token);

if (empty($executions['resources'])) {
     print "No executions were found since $startDate\n";
     exit;
}

foreach ($executions['resources'] as $singleExec){
	$xpathPrint = 0;
	$htmlOut = "";
    print "Processing execution ID: ${singleExec['id']}\n";

    $singleTestURL = $cloudURL . '/export/api/v1/test-executions/' . $singleExec['id'] . "/commands"; 
    $singleExecution = queryReports($singleTestURL, $token);

    foreach ($singleExecution['resources'] as $commandList){
       foreach($commandList['commands'] as $command){
       	   foreach ($command['parameters'] as $parameter){
       	   	 if (preg_match("/\/\//", $parameter['value'])){
                $xPathSplit = preg_split("/\/\//", $parameter['value']);
                $xPathSplit[1] = "//" . $xPathSplit[1];
       	   	 	if(preg_match("/http:\/\//", $parameter['value']) || preg_match("/https:\/\//", $parameter['value'])) {
                   continue;
                }
                $xpathPrint = 1;
       	   	 	$xpScore = testXPath($urlValidate, $xPathSplit[1]);
                $labelStat = calcLabel($xpScore);
                $xPathEncode = "<a href=\"$urlValidateTarget" . urlencode($xPathSplit[1]) . "\">(View Details)</a>";
                $htmlOut .= "<strong><span class=\"label $labelStat\">$xpScore</span></strong> $xPathSplit[1] $xPathEncode</br>\n";
                $htmlOut .= checkTranslation($xPathSplit[1]);
       	   	 }
          }
       }
    }
    if($xpathPrint === 1){
       $htmlOut = "<strong>Execution Report ID: ${singleExec['id']}</strong> - <a href=\"${singleExec['reportURL']}\" target=\"_blank\"> ${singleExec['reportURL']}</a><br/>" . $htmlOut . "<br/>";
       fwrite($outPut, $htmlOut);
    }
}
$htmlEND = htmlEnd();
fwrite($outPut, $htmlEND);
fclose($outPut);

function queryReports($url, $token) {
   //create cURL connection
   $curl_connection = 
   curl_init($url);
   curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
   curl_setopt($curl_connection, CURLOPT_USERAGENT, 
   "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
   curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
 //  curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);
   curl_setopt($curl_connection, CURLOPT_HTTPHEADER, array(
    "PERFECTO_AUTHORIZATION: $token"
    ));
   $result = curl_exec($curl_connection);
   curl_close($curl_connection);

   //var_dump(json_decode($result, true));
   $res = json_decode($result, true);
   return $res;
}
?>