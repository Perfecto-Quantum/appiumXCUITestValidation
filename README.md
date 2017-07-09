# Appium/XCUITest Validation

## Description
This scan utility, written in **PHP** is intended for users who wish to validate the strength of xPaths used in their test code, as well as to check for Appium/XCUITest compatibility coming off of Appium/UIAutomater per [Perfecto's Documentation](http://developers.perfectomobile.com/display/PD/XCUITest+Infrastructure).

The high level functionality of the scan utility:
- Start at the top of a test source tree (taken as input parameter)
- Scan recursively through the test source code
- For any line in any file where an xPath is found
	- Pass the xPath to the online xPath validation tool located [HERE](https://xpathvalidator.herokuapp.com/)
	- Call out any object translations that need to be fixed to be XCUITest compliant (ex: UIButton -> XCUIElementTypeButton)
- Identify non-supported call/capabilities per the [Perfecto Documentation](http://developers.perfectomobile.com/display/PD/XCUITest+Infrastructure)
- Create a summary page which highlights file by file the xpath score and XCUITest compliance.
- OPTIONALLY: Edit the source code to make the necessary changes for UIAutomator to XCUITest object translation

## Script Customization
In order to run this utility, you must first adjust the `$searchRegex` string to identify the 
patterns in use when creating XPaths in your test code. 
For example, if your code uses syntax such as:
```
getDriver().findElement(By.xpath("//*[@label=\"Accounts\"]")).click();
```
 You would set the regex to:
 ```
 $searchRegex = "xpath";
 ```

 If you use the syntax:
 ```
 myobj = DOM://*[text()="Text notification: "]/parent::*[@class='ng-scope ng-binding']`
 otherobj = NATIVE://*[@class='UIButtonLabel' and text()="Back"]
 ```
 You need to have the regex look for both. For example:
 ```
 $searchRegex = "(= DOM:|= NATIVE:)";
 ```
 
 By default, the scanner is set to look for "//". It ignores lines that begin with "//" as they are comments. It will however try to run validaiton on any comments that occur at the end of a line so you can safely ignore those:

 Default setting:
 ```
 $searchRegex = "\/\/";
 ```

 The search is case-insensitive so you do not need to add /i to your regex. 

## Usage
```
php validate.php <source directory>
```
Example:
Scan the test code located at: ~/ws/testCode/src/main and report the results
```
php validate.php ~/ws/testCode/src/main
```
## Results
Results will be located in the `./results` sub-directory

![Alt text](/images/screenshot.jpg?raw=true "Results screenshot")
