<?php
set_time_limit(0);
error_reporting(E_ALL);
ob_implicit_flush(TRUE);
ob_end_flush();
if($_SERVER["HTTPS"] != "on" && empty($_SERVER["HTTPS"]) && $_SERVER["PORT"] != 443 && $_SERVER["HTTP_X_FORWARDED_PROTO"] != "https" && $_SERVER["HTTP_X_FORWARDED_PORT"] != 443) {
   header("HTTP/1.1 301 Moved Permanently");
   header("Location: https://" . $_SERVER['SERVER_NAME'] . $_SERVER['REQUEST_URI']);
   exit();
}
require 'helpers.php';
if (isset($_POST['cloudName'])){
   // User submitted to the contact form
   $cloudName = trim($_POST['cloudName']) ;
   $securityToken = trim($_POST['securityToken']) ;	 
   $startDate = trim($_POST['startDate']) ;
   $endDate = trim($_POST['endDate']) ;

   if(empty($cloudName) || empty($securityToken) || empty($startDate) || empty($endDate)){
      header( "Location: https://xcuitest.perfectomobile.com/appiumCloudRun/index.php" );	   
   }
} else {
      header( "Location: https://xcuitest.perfectomobile.com/appiumCloudRun/index.php" );	   

}

$cloudURL = 'https://' . "$cloudName";

$token = $securityToken;

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
$endTime = strtotime($endDate) * 1000;

$listExecutionsURL = $cloudURL . '/export/api/v1/test-executions?startExecutionTime[0]=' . $startTime . '&endExecutionTime[0]=' . $endTime; 

$executions = queryReports($listExecutionsURL, $token);

if (empty($executions['resources'])) {
     print "No executions were found since $startDate\n";
     exit;
}

$reportURL = "https://xcuitest.perfectomobile.com/appiumXCUITestValidation/" . $outPutFN;
print "Scanning Started - When finished, report will be here: <a href=\"$reportURL\">$reportURL</a><br/>";

foreach ($executions['resources'] as $singleExec){
	$xpathPrint = 0;
	$htmlOut = "";
    print "Processing execution ID: ${singleExec['id']}\n<br/>";

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

       fwrite($outPut, $htmlOut);
    }
}
$htmlEND = htmlEnd();
fwrite($outPut, $htmlEND);
fclose($outPut);
print "Scan Finished - Report here: <a href=\"$reportURL\">$reportURL</a><br/>";

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
