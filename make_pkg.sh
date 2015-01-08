#!/bin/bash

find . -name '*.php' -exec dos2unix {} \;
find . -name '*.xml' -exec dos2unix {} \;

version=$(grep '<version>' bis.xml | sed -e 's/<[a-z\/]*>//g' | sed -e 's/ *//g')
rm plugin_bis*.zip
zip -r plugin_bis-${version}-j3X.zip myr images cs-CZ.* en-GB.* bis.php bis.xml index.html
