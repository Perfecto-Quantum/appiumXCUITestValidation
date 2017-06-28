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

## Usage
```
php validate.php <source directory> [edit]
```
Example:
Scan the test code located at: ~/ws/testCode/src/main and report the results
```
php validate.php ~/ws/testCode/src/main
```
Scan the test code located at: ~/ws/testCode/src/main, report the results AND make edits for UIAutomator to XCUITest object translation
```
php validate.php ~/ws/testCode/src/main edit
```

## Results
Results will be located in the `./results` sub-directory

![Alt text](/images/screenshot.jpg?raw=true "Results screenshot")
