
#!/bin/bash

# how to install?
# 1. create ~/local/bin/git-meld-all and copy contents below
# 2. edit ~/.bashrc to include this line ... export PATH=$PATH:~/local/bin/
# 3. make sure the git-meld-all file can be executed

folders=`find . -mindepth 0 -maxdepth 1 -type d`
rootFolder=$PWD

# Defining Colors
RED='\033[1;31m'
GREEN='\033[1;32m'
BLUE='\033[0;34m'
CYAN='\033[0;36m'
ORANGE='\033[0;33m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Vendor to look for
vendor="sunnysideup"

for folder in $folders; do
    if [ -f $folder/.git/config ] ; then # is a git working copy
	# Belongs to sunnysideup
	if ( grep --quiet $vendor $folder/.git/config ) || [[ $folder == "." ]]; then
	    cd $folder
	    folderChanges=`git diff --name-only  HEAD`
	    if [[ $folderChanges ]];then
		echo -e "-- ${CYAN}You have modified files in $folder${NC}"

		untrackedFiles=`git ls-files --others --exclude-standard`
		for file in $untrackedFiles;do 
		    echo -e "--> ${RED}$file${NC}"
		done

		modifiedFiles=`git ls-files -m`
		for file in $modifiedFiles;do
		    echo -e "--> ${YELLOW}$file${NC}"
		done

		addedFiles=`git diff --name-only --cached`
		for file in $addedFiles;do
		    echo -e "--> ${GREEN}$file${NC}"
		done
		
		echo -e "-- ${ORANGE}Would you like to commit changes?${NC} [y/n]"
		read commit

		if [[ $commit == "y" ]];then
		    git add --all
		    meld .
		fi
		echo -e "-- ${CYAN}Completed ${BLUE}$folder${NC} --"
		echo ""

	    fi
	    cd $rootFolder
	fi
    fi
done

echo -e "-- ${CYAN}END${NC} --------------------";

    
