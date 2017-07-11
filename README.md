# Appium/XCUITest Validation

## Description
These scan utilities, written in **PHP** are intended for users who wish to validate the strength of xPaths used in their test code, as well as to check for Appium/XCUITest compatibility coming off of Appium/UIAutomater per [Perfecto's Documentation](http://developers.perfectomobile.com/display/PD/XCUITest+Infrastructure).

There are ***TWO*** scan utilities located in this project.
* `validate.php` - Scans the test source code directly (needs physical access to the sources)
* `reportscan.php` - Queries Perfeco's Reporting database to identify XPath's used

## Using valildate.php
`validate.php` Has the following high level functionality:
- Starts at the top of a test source tree (taken as input parameter)
- Scans recursively through the test source code
- For any line in any file where an xPath is found:
	- Pass the xPath to the online xPath validation tool located [HERE](https://xpathvalidator.herokuapp.com/)
	- Call out any object translations that need to be fixed to be XCUITest compliant (ex: UIButton -> XCUIElementTypeButton)
- Identify non-supported call/capabilities per the [Perfecto Documentation](http://developers.perfectomobile.com/display/PD/XCUITest+Infrastructure)
- Create a summary page which highlights file by file the xpath score and XCUITest compliance. Report name format is: `(md5)_xPathValidation.html`

#### Script Customization
`validate.php` -  By default, the scanner is set to look for "//" in the source code to identify XPaths. It ignores lines that begin with "//" as it recognizes they are comments. It does not know to skip comments that occur at the end of the line. Examples:
```
Will be ignored:
// here is a code comment
```
```
Will be processed as XPath:
getDriver().findElement(By.xpath("//*[@label=\"Accounts\"]")).click();
```
```
Will be processed, so you need to ignore to report for this line:
Object result = getDriver().executeScript("mobile:logs:stop", new HashMap<>());  // isn’t this a cool line
```
If you want to alter the default behavior you may adjust the `$searchRegex` string to identify the 
patterns in use when creating XPaths in your test code. 
For example, if your code uses syntax such as:
 ```
 myobj = DOM://*[text()="Text notification: "]/parent::*[@class='ng-scope ng-binding']`
 otherobj = NATIVE://*[@class='UIButtonLabel' and text()="Back"]
 ```
 You need to have the regex look for both. For example:
 ```
 $searchRegex = "(= DOM:|= NATIVE:)";
 ```

 The default setting (to match "//") is:
 ```
 $searchRegex = "\/\/";
 ```
 The search is case-insensitive so you do not need to add /i to your regex. 

#### Usage
```
php validate.php <source directory>
```
Example:
Scan the test code located at: ~/ws/testCode/src/main and report the results
```
php validate.php ~/ws/testCode/src/main
```
## Using reportscan.php
`reportscan.php` Has the following high level functionality:
- Queries Perfecto's Reporting database for executions since a given timeframe
- Examines each execution found and looks for any XPaths that were used
- For any XPath detected: 
	- Pass the xPath to the online xPath validation tool located [HERE](https://xpathvalidator.herokuapp.com/)
	- Call out any object translations that need to be fixed to be XCUITest compliant (ex: UIButton -> XCUIElementTypeButton)
- Create a summary page which highlights file by file the xpath score and XCUITest compliance. Report name format is: `(md5)_xPathValidation_ReportingScan.html`
#### Script Customization
`reportscan.php` requires some customization before use.
* `$cloudURL` - The URL of your Perfecto Cloud
* `startDate` - The Date/Time to mark as the beginning of the scan
```
Example:
$cloudURL = 'https://demo.reporting.perfectomobile.com';
$startDate = '2017/07/01 17:00:00';
```
#### Token creation
You will need to create the file `../reportingToken.txt` in the directory one level above this script. That file should contain your Perfecto security token. Details on retrieving your security token are [HERE](http://developers.perfectomobile.com/display/PD/Security+Token)
#### Usage
```
php reportscan.php
```

## Results
Results will be located in the `./results` sub-directory

![Alt text](/images/screenshot.jpg?raw=true "Results screenshot")
