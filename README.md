DFDiagram
=========
An extension for MediaWiki that allows the inclusion of Dwarf Fortress-style diagrams.

**Note:** This extension is still fairly experimental - It shouldn't bring down
your wiki, but it might malfunction. Post any issues you find 
[in the issue tracker](https://github.com/lethosor/DFDiagram/issues) 
(you can also make suggestions in the issue tracker).

This extension has only been tested on MediaWiki 1.20/1.21, but should work on most recent versions.

Installation
---
You can download the source at [GitHub](https://github.com/lethosor/DFDiagram) ([direct link](https://github.com/lethosor/DFDiagram/archive/master.zip)), or clone it using git with `git clone git://github.com/lethosor/DFDiagram.git`.

Make sure the DFDiagram folder is located in your (MediaWiki)/extensions folder and add the following line to `LocalSettings.php`:
```php
require_once( "$IP/extensions/DFDiagram/DFDiagram.php" );
```
Navigate to `Special:Version` on your wiki to verify that the extension has been installed.
