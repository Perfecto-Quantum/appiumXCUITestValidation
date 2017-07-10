<?php

function calcLabel($xpScore) {
   if ((int)$xpScore <= 89 && (int)$xpScore >= 80){
      $labelStat = "label-warning";
   } elseif ((int)$xpScore < 80) {
      $labelStat = "label-danger";
   } else{ 
      $labelStat = "label-success"; 
   }
   return $labelStat;
}

function checkTranslation($line) {
   require 'UIA-XCUI-translation.php';
   foreach ($translation as $key => $value) {
      if(preg_match("/$key/", $line)){
         $htmlOut .= "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<strong><span class=\"label label-danger\">XCUITest Incompatible</span>Replace $key with $value</strong></br>\n";
      }
   }
   return $htmlOut;
}

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

function htmlBegin() {
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
                <span class="nav navbar-nav appname">XPath Validation and XCUITest Compatibility Summary</span>
            </div>
        </div>
    </div><!--/.nav-collapse -->

</nav>
<div class="container">
    <div class="page-header">

        <h1>XPath Validation and XCUITest Compatibility Summary</h1>
    </div>
EOD;
  return $htmlBegin;
}

function htmlEnd(){
	$htmlEND = <<<EOD
</div>
</body></html>
EOD;
   return $htmlEND;
}
?>