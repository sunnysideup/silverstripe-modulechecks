git-patch-quick.sh
find vendor/sunnysideup/ -maxdepth 1 -mindepth 1 -type d -execdir realpath "{}"  ';' | while read dir; do
        cd "$dir/";
        echo "---\n$dir/\n";
        git add . -A;
        git commit . -m "PATCH: automated commit";
        git push;
done;

