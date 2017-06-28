<?php
/*

*/

$src = $argv[1];

$urlValidate = 'https://xpathvalidator.herokuapp.com/validateNew';
$urlValidateTarget = 'https://xpathvalidator.herokuapp.com/?bdata=';

/*
$htmlRoot = "/var/www/html";
//$objFilePath = "$htmlRoot/Quantum-Starter-Kit/src/main/resources/common/demo.loc";
$webRoot = "http://54.175.223.15";
//$htmlRoot = "/Applications/MAMP/htdocs";
//$objFilePath = "$htmlRoot/quantum-test/src/main/resources/common/demo.loc";

$xPathStatus = ""; $xPathFail = 0; $xPathWarn = 0;

$outPutFN = $htmlRoot . "/xpathresults/" . $commitSha . "_xPathValidation.html";
$outPutURL = $webRoot . "/xpathresults/" . $commitSha . "_xPathValidation.html";
*/

$outPutFN = "./results/" . time() . "_xPathValidation.html";

$outPut = fopen($outPutFN, "w") or die("Unable to open file!");
$htmlBegin = <<<EOD
<html><head>
    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css"
          integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/sticky-footer.navbar.css">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
<nav class="navbar navbar-default navbar-fixed-top header-style">
    <div class="container">
        <div class="navbar-header">
            <div>
                <img class="navbar-brand logo" src="../images/logo.png">
                <span class="nav navbar-nav appname">XPath Validation Summary</span>
            </div>
        </div>
    </div><!--/.nav-collapse -->

</nav>
<div class="container">
    <div class="page-header">

        <h1>XPath Validation Test Summary</h1>
        <h3>GitHub Commit - $commitSha</h3>
    </div>
EOD;

fwrite($outPut, $htmlBegin);

chdir ($src) or die("Unable to cd to $src!");
$htmlOut = "";

$rii = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($src));
$files = array(); 
foreach ($rii as $file) {
    if ($file->isDir()){ 
        continue;
    }
    $files[] = $file->getPathname(); 
}
foreach ($files as $filename) {
  $stripFN =  str_replace($src, ".", $filename); 
  $htmlOut = "";
  $xpathPrint = 0;
  $objFilePath = $filename;
  $objFile = fopen($objFilePath, "r") or die("Unable to open file!");
    while (($line = fgets($objFile)) !== false) {
       if(preg_match("/xpath/i", $line)) {
          // strip whitespace off of beginning of line
          $line = trim($line);
          // skip if # or //  or look for  //skipXpath to intentionally skip
          if(preg_match("/^#/", $line) || preg_match("/^\/\//", $line) || preg_match("/\/\/skipXpath/", $line)) {
             continue;
          }
          $xpathPrint = 1;
          $xPathSplit = explode("xpath", $line);
          $xPathSplit[1] = strstr($xPathSplit[1], '.click', true) ?: $xPathSplit[1];
          $xPathSplit[1] = strstr($xPathSplit[1], '.sendKeys', true) ?: $xPathSplit[1];
          $xPathSplit[1] = strstr($xPathSplit[1], '//', false) ?: $xPathSplit[1];
          $xPathSplit[1] = strstr($xPathSplit[1], '}', true) ?: $xPathSplit[1];
          $xPathSplit[1] = strstr($xPathSplit[1], ')"))', true) ?: $xPathSplit[1];
          $xPathSplit[1] = strstr($xPathSplit[1], '"))', true) ?: $xPathSplit[1];
          $xpScore = testXPath($urlValidate, $xPathSplit[1]);
          if ((int)$xpScore <= 89 && (int)$xpScore >= 80){
             $xPathWarn = 1;
             $bgcolor = "#ffff00";
             $labelStat = "label-warning";
          } elseif ((int)$xpScore < 80) {
             $xPathFail = 1;
             $bgcolor = "#ff0000";
             $labelStat = "label-danger";
          } else{
             $bgcolor = "#00ff00";   
             $labelStat = "label-success"; 
          }
          $xPathEncode = "<a href=\"$urlValidateTarget" . urlencode($xPathSplit[1]) . "\">(View Details)</a>";
          $htmlOut .= "<strong><span class=\"label $labelStat\">$xpScore</span></strong> $xPathSplit[1] $xPathEncode</br>\n";
       }
    }
  fclose($objFile);
  if($xpathPrint === 1){
    print "$stripFN\n";
    $htmlOut = "<strong>$stripFN</strong><br/>" . $htmlOut . "<br/>";
    fwrite($outPut, $htmlOut);
  }
    
}
$htmlEND = <<<EOD
</div>
</body></html>
EOD;
fwrite($outPut, $htmlEND);
fclose($outPut);

function testXPath($url, $xPath) {
   //create array of data to be posted
   $post_data['data'] = $xPath;
   //traverse array and prepare data for posting (key1=value1)
   foreach ( $post_data as $key => $value) {
      $post_items[] = $key . '=' . $value;
   }
   //create the final string to be posted using implode()
   $post_string = implode ('&', $post_items);
   //create cURL connection
   $curl_connection = 
   curl_init($url);
   curl_setopt($curl_connection, CURLOPT_CONNECTTIMEOUT, 30);
   curl_setopt($curl_connection, CURLOPT_USERAGENT, 
   "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1)");
   curl_setopt($curl_connection, CURLOPT_RETURNTRANSFER, true);
   curl_setopt($curl_connection, CURLOPT_SSL_VERIFYPEER, false);
   curl_setopt($curl_connection, CURLOPT_FOLLOWLOCATION, 1);
   curl_setopt($curl_connection, CURLOPT_POSTFIELDS, $post_string);
   $result = curl_exec($curl_connection);
   curl_close($curl_connection);

   //var_dump(json_decode($result, true));
   $res = json_decode($result);
   $score = $res->{'score'};
   return $score;
}
?>