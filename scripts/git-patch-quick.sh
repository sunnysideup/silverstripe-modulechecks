#!/bin/bash

# how to install?
# 1. create ~/local/bin/git-patch-all.sh and copy contents of this file
# 2. edit ~/.bashrc to include this line ... export PATH=$PATH:~/local/bin/
# 3. you may need to run: chmod +x ~/local/bin/git-patch-all.sh

# you can now run git-patch-all.sh from the command line.

git add . -A
git commit . -m "PATCH: automated commit"
git push
