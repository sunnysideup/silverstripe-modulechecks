#!/bin/bash

folders=`find . -maxdepth 0 -mindepth 1 -type d`
rootFolder=$PWD

for folder in $folders; do
    if [ -f $folder/.git/config ] ; then
    if grep --quiet sunnysideup $folder/.git/config; then
        cd $folder
        modifiedFiles=`git diff --name-only  HEAD`
        if [[ $modifiedFiles ]];then
        echo 'You have modified files in '$folder
        for f in $modifiedFiles;do
            echo $f
        done
        echo 'Would you like to commit changes?'
        read commit

        if [[ $commit == "y" ]];then
            git add --all
            meld .
        fi
        echo ""
        echo "---------------------------------------------------"
        fi
        cd ..
    fi
    fi
done
