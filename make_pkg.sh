#!/bin/bash

find . -name '*.php' -exec dos2unix {} \;
find . -name '*.xml' -exec dos2unix {} \;

if [ ! -d ./dist ]; then mkdir dist; fi;

version=$(grep '<version>' bis.xml | sed -e 's/<[a-z\/]*>//g' | sed -e 's/ *//g')
rm dist/plugin_bis*.zip
zip -r plugin_bis-${version}-j3X.zip myr images cs-CZ.* en-GB.* bis.php bis.xml index.html
mv plugin_bis-${version}*.zip dist/
