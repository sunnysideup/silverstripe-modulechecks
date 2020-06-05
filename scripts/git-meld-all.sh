
#!/bin/bash

# how to install?
# 1. create ~/local/bin/git-meld-all and copy contents below
# 2. edit ~/.bashrc to include this line ... export PATH=$PATH:~/local/bin/
# 3. make sure the git-meld-all file can be executed

# deleting foo.bar.orig files ...
find . -name "*.orig" -exec rm "{}" -i \;

# find folders
folders=`find . -mindepth 0 -maxdepth 1 -type d`
rootFolder=$PWD

# Defining Colors
RED='\033[1;31m'
RED2='\033[0;31m'
GREEN='\033[1;32m'
BLUE='\033[1;34m'
BLUE2='\033[0;34m'
CYAN='\033[0;36m'
ORANGE='\033[0;33m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

# Vendor to look for
vendor="sunnysideup"

#fix PHP code ...
php-cs-fixer fix ./app/code --using-cache=no --rules=@PSR2

gitCustomStatus (){
    git status --porcelain | while read status file; do
    case $status in
        $1) echo -e "$3 $2$1\t$file${NC}";;
    esac
    done
}


git commit composer.lock -m "PATCH: composer.lock"
git commit .gitignore -m "PATCH: .gitignore"

for folder in $folders; do
    if [ -f $folder/.git/config ] ; then # is a git working copy
    # Belongs to sunnysideup
    if ( grep --quiet $vendor $folder/.git/config ) || [[ $folder == "." ]]; then
        cd $folder
        #fix PHP code ...
        if ( grep --quiet $vendor ./.git/config ); then
            php-cs-fixer fix ./ --using-cache=no --rules=@PSR2
        fi

        # check changes
        folderChanges=`git status --porcelain`

        if [[ $folderChanges ]];then
        echo -e "-- ${CYAN}You have modified files in $folder${NC}"

        gitCustomStatus M "${YELLOW}" "+++"    # Modified
        gitCustomStatus A "${GREEN}" "+++"     # Added
        gitCustomStatus R "${BLUE2}" "+++"     # Renamed
        gitCustomStatus C "${BLUE}" "+++"      # Copied
        gitCustomStatus D "${RED}" "---"       # Deleted

        if [[ `git status --porcelain | grep "^?? "` ]];then
            echo -e "\n-- ${CYAN} Untracked files${NC}"
            gitCustomStatus2 ?? "${RED2}" "   " # Untracked
        fi

        echo -e "\n-- ${ORANGE}Would you like to commit changes?${NC} [y/n]"
        read commit

        if [[ $commit == "y" ]];then
            git add --all
            meld .
            git pull origin "$(git branch | grep -E '^\* ' | sed 's/^\* //g')"
            git push origin "$(git branch | grep -E '^\* ' | sed 's/^\* //g')"
        fi
        echo -e "-- ${CYAN}Completed ${BLUE2}$folder${NC} --"
        echo ""

        fi
        cd $rootFolder
    fi
    fi
done

echo -e "-- ${CYAN}END${NC} --------------------";

for folder in $folders; do
    if [ -f $folder/.git/config ] ; then # is a git working copy
        cd $folder
        git status
        git pull
        git push
        cd $rootFolder
    fi
done
