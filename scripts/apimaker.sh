#!/bin/bash

# install php dox
#wget http://phpdox.de/releases/phpdox.phar
#chmod +x phpdox.phar
#rm /usr/local/bin/phpdox/phpdox.phar
#mv phpdox.phar /usr/local/bin/phpdox
#sudo apt-get install php5-xsl
#phpdox --version

for f in */; do
  MYDIR="/var/www/_modules/"$f"trunk/"

	# go to root of install
	cd $MYDIR

	# clean up svn / git
	svn up ./docs/
	svn cleanup ./docs
	svn up ./docs/
	svn cleanup ./docs/
	svn delete ./docs/api --force
	svn delete ./docs/en/phpdox/xml --force
	svn ci ./docs/ --message "MINOR: removing old docs"

	#run php dox
	svn mkdir ./docs/
	touch ./docs/_manifest_exclude
	svn mkdir ./docs/en/
	svn mkdir ./docs/en/phpdox
	cp $MYDIR../../phpdox.xml $MYDIR/docs/en/phpdox/
	cd ./docs/en/phpdox/
	phpdox

	#cleanup

	#add to svn / git
	cd $MYDIR
	svn mkdir ./docs/api
	mv ./docs/en/phpdox/xml ./docs/api
	svn add ./docs/api --force
	svn add ./docs/en/phpdox/ --force
	svn ci ./docs/ --message "MINOR: updating documentation"

done
