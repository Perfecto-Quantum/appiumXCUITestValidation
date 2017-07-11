<?php
$inProgress = array(
  '\.setCapability\((\"|\')fullReset' => 'capabilities.setCapability("fullReset") is not yet supported',
  '\.setCapability\((\"|\')noReset' => 'capabilities.setCapability("noReset") is not yet supported',
  '\.setCapability\((\"|\')useNewWDA' => 'capabilities.setCapability("useNewWDA") is not yet supported',
  '\.setCapability\((\"|\')CUSTOM_SSL_CERT' => 'capabilities.setCapability("CUSTOM_SSL_CERT") is not yet supported',
  '\.setCapability\((\"|\')TAP_WITH_SHORT_PRESS_DURATION' => 'capabilities.setCapability("TAP_WITH_SHORT_PRESS_DURATION") is not yet supported',
  '\.setCapability\((\"|\')SCALE_FACTOR' => 'capabilities.setCapability("SCALE_FACTOR") is not yet supported',
  '\.setCapability\((\"|\')WDA_LOCAL_PORT' => 'capabilities.setCapability("WDA_LOCAL_PORT") is not yet supported',
  '\.setCapability\((\"|\')SHOW_XCODE_LOG' => 'capabilities.setCapability("SHOW_XCODE_LOG") is not yet supported',
  '\.setCapability\((\"|\')REAL_DEVICE_LOGGER' => 'capabilities.setCapability("REAL_DEVICE_LOGGER") is not yet supported',
  '\.setCapability\((\"|\')IOS_INSTALL_PAUSE' => 'capabilities.setCapability("IOS_INSTALL_PAUSE") is not yet supported',
  '\.setCapability\((\"|\')XCODE_CONFIG_FILE' => 'capabilities.setCapability("XCODE_CONFIG_FILE") is not yet supported',
  '\.setCapability\((\"|\')KEYCHAIN_PASSWORD' => 'capabilities.setCapability("KEYCHAIN_PASSWORD") is not yet supported',
  '\.setCapability\((\"|\')KEYCHAIN_PATH' => 'capabilities.setCapability("KEYCHAIN_PATH") is not yet supported',
  '\.setCapability\((\"|\')USE_PREBUILT_WDA' => 'capabilities.setCapability("USE_PREBUILT_WDA") is not yet supported',
  '\.setCapability\((\"|\')PREVENT_WDAATTACHMENTS' => 'capabilities.setCapability("PREVENT_WDAATTACHMENTS") is not yet supported',
  '\.setCapability\((\"|\')WEB_DRIVER_AGENT_URL' => 'capabilities.setCapability("WEB_DRIVER_AGENT_URL") is not yet supported',
  '\.setCapability\((\"|\')CLEAR_SYSTEM_FILES' => 'capabilities.setCapability("CLEAR_SYSTEM_FILES") is not yet supported',
  '\.findElements\(MobileBy\.iOSNsPredicateString\(' => 'driver.findElements(MobileBy.iOSNsPredicateString()) is not yet supported',
  '\.findElementByIosNsPredicate\(' => 'driver.findElementByIosNsPredicate() is not yet supported',
  '\iOSXCUITFindBy\(' => 'io.appium.java_client.pagefactory.iOSXCUITFindBy annotation is not yet supported',
  '\.executeScript\((\"|\')mobile\:doubleTap' => 'driver.executeScript("mobile:doubleTap") is not yet supported - use <a href="https://community.perfectomobile.com/posts/1137436-double-click-a-native-element">Perfeto Double Tap Extension</a>',
);


function checkInProgress($line, $lineNum, $inProgress) {
   $htmlOut = "";
   $htmlBegin = "<strong><span class=\"label label-danger\">In Development</span>&nbsp;";
   $htmlEnd = "</strong> (line: $lineNum)</br>\n";

   foreach ($inProgress as $key => $value) {
      if(preg_match("/$key/", $line)){
          $htmlOut .= $htmlBegin . $value . $htmlEnd ;
       }
    }
	
   return $htmlOut;
}

?>