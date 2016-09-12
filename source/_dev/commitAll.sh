#!/bin/bash

files=(
  '/'
);

BASEFOLDER=`pwd`
echo $BASEFOLDER
for i in "${files[@]}"
do
	echo $BASEFOLDER$i;
    cd $BASEFOLDER$i;
    git pull origin master;
    git commit
    git add . -A;
    git commit . -m "fixes";
    git push origin master;
done
