<?php
$perfectoAppium = array(
   '\.setCapability\((\"|\')autoAcceptAlerts' => 'capabilities.setCapability("autoAcceptAlerts") is not supported by Appium - use driver.switchTo().alert().accept() instead',
   '\.setCapability\((\"|\')autoDismissAlerts' => 'capabilities.setCapability("autoDismissAlerts") is not supported by Appium - use driver.switchTo().alert().dismiss() instead',
   '\.shake\(' => 'driver.shake() is not supported',
   '\.executeScript\((\"|\')mobile\:application\:info' => 'driver.executeScript("mobile:application:info") is not supported - use driver.getCapabilities().getCapability("required capability") instead',
   '\.executeScript\((\"|\')mobile\:handset\:ready' => 'driver.executeScript("mobile:handset:ready") is not supported - use driver.backgroundApp(<timeout>) instead',
   '\.executeScript\((\"|\')mobile\:application\:open' => 'driver.executeScript("mobile:application:open") is not supported - use driver.launchApp() instead',
   '\.executeScript\((\"|\')mobile\:handset\:rotate' => 'driver.executeScript("mobile:handset:rotate") is not supported - use driver.rotate(<orientation>) instead',
   '\.executeScript\((\"|\')mobile\:presskey' => 'driver.executeScript("mobile:presskey") is not supported - use WebElement sendKeys() instead',
   '\.executeScript\((\"|\')mobile\:touch\:drag' => 'driver.executeScript("mobile:touch:drag") is not supported - use <a href="https://appium.github.io/java-client/io/appium/java_client/TouchAction.html">Appium Touch Actions</a>',
   '\.executeScript\((\"|\')mobile\:trackball\:roll' => 'driver.executeScript("mobile:trackball:roll") is not supported - use <a href="https://appium.github.io/java-client/io/appium/java_client/TouchAction.html">Appium Touch Actions</a>',
);

function checkPerfectoAppium($line, $lineNum, $perfectoAppium) {
   $htmlOut = "";
   $htmlBegin = "<strong><span class=\"label label-danger\">Appium Compatibility</span>&nbsp;";
   $htmlEnd = "</strong> (line: $lineNum)</br>\n";

   foreach ($perfectoAppium as $key => $value) {
      if(preg_match("/$key/", $line)){
          $htmlOut .= $htmlBegin . $value . $htmlEnd ;
       }
    }
	
   return $htmlOut;
}

?>