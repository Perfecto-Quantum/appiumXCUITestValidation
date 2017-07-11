<?php
$appDep = array(
   'scrollTo' => 'driver.scrollTo() - use the executeScript(\"mobile: scroll\") script command instead',
   'scrollToExact' => 'driver.scrollToExact() - use the executeScript(\"mobile: scroll\") script command instead',
   'networkClass' => 'io.appium.java_client.NetworkConnectionSetting - use <a href=\"http://developers.perfectomobile.com/display/PD/Network+Functions\">Perfecto Network Functions</a> instead',
   'findElementByIosUIAutomation' => 'driver.findElementByIosUIAutomation() has been deprecated',
   'findElement' => 'driver.findElement(MobileBy.IosUIAutomation("")) has been deprecated',
   'findElements' => 'driver.findElements(MobileBy.IosUIAutomation("")) has been deprecated',
);


function checkAppiumDeprecated($line, $lineNum, $appDep) {
   $htmlOut = "";
   $htmlBegin = "<strong><span class=\"label label-danger\">Appium Deprecation</span>&nbsp;";
   $htmlEnd = "</strong> (line: $lineNum)</br>\n";

   if(preg_match("/\.scrollTo\(/", $line)){
       $htmlOut .= $htmlBegin . $appDep['scrollTo'] . $htmlEnd ;
   }
   if(preg_match("/\.scrollToExact\(/", $line)){
       $htmlOut .= $htmlBegin . $appDep['scrollToExact'] . $htmlEnd ;
   }
   if(preg_match('/io.appium.java_client.NetworkConnectionSetting/', $line)){
       $htmlOut .= $htmlBegin . $appDep['networkClass'] . $htmlEnd ;
   }
   if(preg_match('/\.findElementByIosUIAutomation\(/', $line)){
       $htmlOut .= $htmlBegin . $appDep['findElementByIosUIAutomation'] . $htmlEnd ;
   }
   if(preg_match("/\.findElement\(MobileBy.IosUIAutomation\(/", $line)){
       $htmlOut .= $htmlBegin . $appDep['findElement'] . $htmlEnd ;
   }
   if(preg_match("/\.findElements\(MobileBy.IosUIAutomation\(/", $line)){
       $htmlOut .= $htmlBegin . $appDep['findElements'] . $htmlEnd ;
   }

	
   return $htmlOut;
}

?>