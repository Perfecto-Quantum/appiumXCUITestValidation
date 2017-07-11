<?php
$appDep = array(
   '\.scrollTo\(' => 'driver.scrollTo() - use the executeScript(\"mobile: scroll\") script command instead',
   '\.scrollToExact\(' => 'driver.scrollToExact() - use the executeScript(\"mobile: scroll\") script command instead',
   '\.tap\(' => 'driver.tap() - use <a href="http://appium.github.io/java-client/io/appium/java_client/TouchAction.html">TouchAction</a>, <a href="http://appium.github.io/java-client/io/appium/java_client/MultiTouchAction.html">MutliTouchAction</a> or <a href="http://developers.perfectomobile.com/display/PD/User+Actions">Perfecto Extensions</a> instead',
   '\.swipe\(' => 'driver.swipe() - use <a href="http://appium.github.io/java-client/io/appium/java_client/TouchAction.html">TouchAction</a>, <a href="http://appium.github.io/java-client/io/appium/java_client/MultiTouchAction.html">MutliTouchAction</a> or <a href="http://developers.perfectomobile.com/display/PD/User+Actions">Perfecto Extensions</a> instead',
   '\.zoom\(' => 'driver.zoom() - use <a href="http://developers.perfectomobile.com/pages/viewpage.action?pageId=14877241
">Perfecto Gesture Extension</a> instead',
   '\.pinch\(' => 'driver.pinch() - use <a href="http://developers.perfectomobile.com/pages/viewpage.action?pageId=14877241
">Perfecto Gesture Extension</a> instead',
   '\.getMouse\(' => 'driver.getMouse() has been deprecated - Mouse not supported on mobile devices',
   'io\.appium\.java_client\.NetworkConnectionSetting' => 'io.appium.java_client.NetworkConnectionSetting - use <a href="http://developers.perfectomobile.com/display/PD/Network+Functions">Perfecto Network Functions</a> instead',
   '\.findElementByIosUIAutomation\(' => 'driver.findElementByIosUIAutomation() - use <a href="http://static.javadoc.io/io.appium/java-client/5.0.0-BETA9/io/appium/java_client/ios/IOSDriver.html#findElement-org.openqa.selenium.By-
">other find methods</a> instead',
   '\.findElement\(MobileBy\.IosUIAutomation\(' => 'driver.findElement(MobileBy.IosUIAutomation("")) - use <a href="http://static.javadoc.io/io.appium/java-client/5.0.0-BETA9/io/appium/java_client/ios/IOSDriver.html#findElement-org.openqa.selenium.By-
">other find methods</a> instead',
);


function checkAppiumDeprecated($line, $lineNum, $appDep) {
   $htmlOut = "";
   $htmlBegin = "<strong><span class=\"label label-danger\">Appium Deprecation</span>&nbsp;";
   $htmlEnd = "</strong> (line: $lineNum)</br>\n";

   foreach ($appDep as $key => $value) {
      if(preg_match("/$key/", $line)){
          $htmlOut .= $htmlBegin . $value . $htmlEnd ;
       }
    }
	
   return $htmlOut;
}

?>