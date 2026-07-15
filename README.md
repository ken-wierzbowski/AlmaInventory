# AlmaInventory

- Alma Inventory API application that can be launched using PHP Desktop on Windows.
For background on the application, see [CARLI's adaptation](https://github.com/CARLI/AlmaInventory) of the [original AlmaInventory app written by Terry Brady](https://github.com/terrywbradyC9/AlmaInventory).

- Intent is to deploy this app on a small scale for use on stacks staff laptops and is not intended to be deployed at scale in its current state.

## Prerequisites

  - PHP Desktop Chrome 130.1 for Windows
  - Git
  - An Ex Libris Alma API key with read-only access to the Bibs and Configuration APIs

## Configuration

- Add project files to `www` PHP Desktop folder.
- Download the [cacert.pem](https://curl.se/ca/cacert.pem) file and place in `www/php` directory from CA certificates extracted from Mozilla
- In PHP Desktop, modify settings.json file to adjust app window size.
- Configure your Alma API key in `local.prop`.


## Functionality

This code will facilitate an inventory of items cataloged in the Alma integrated library system.

- Physical items are handled one at a time and scanned with a barcode scanner
- The user can press a button to indicate if there is a mismatch in the title, volume or call number of the physical item
- A request is sent to the Alma Bib API to retrieve known information for that Barcode
  - To avoid cross-origin restrictions, a PHP service is used to call the Alma API and to add the Alma API key
  - This service simply appends the api key to the request and returns the json object provided from the Alma API
- Results are displayed in a table with common errors highlighted
- Optionally, the user can upload results of a scanning session to Google sheets


## Configuration Files

| Purpose | Server Type | Default File Location | Note |
| ------- | ----------- | --------------------- | ---- |
| Store Alma API Key | All | /var/data/local.prop |This file should not be web accessible|
| Set path to local.prop | Jetty | jetty/prop.jsp | JSP code file|
| | Node.js| node/prop.js | Node.js code file |
| | PHP | php/Alma.prop | PHP prop file format |
| Set client side properties | All | */barcode.init.js | Alma API URL is set for all instances |
| | All | */barcode.init.js | Location validation regular expression, barcode validation regular expression |
| | Jetty | jetty/barcode.init.js | Alma requests are pre-processed by inventory/redirect.jsp|
| | Node.js | node/barcode.init.js | Alma requests are pre-processed by redirect.js |
| | PHP | php/barcode.init.js | Alma requests are pre-processed by barcodeReportRedirect.php |
| Set Google Drive Upload Properties | All | gsheet.prop.json | Save gsheet.prop.json.template to gsheet.prop.json note that these values will be visible to the client app.|

