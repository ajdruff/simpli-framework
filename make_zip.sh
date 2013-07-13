# this is a small build script that will create a zip file with the contents of the directory.
# edit as necessary for your purposes.
# requires installing the zip package .
# if no git repo, then comment out the above 2 lines and uncomment the next to just copy the contents of the directory
cp -r ./ ./simpli-hello 2>/dev/null

# if this project exists within a git repo with name 'simpli-hello' then comment out the line above and uncomment out the lines starting with 'git' below.
# clones it into a new directory and saves a copy of the changes to a log for reference
# git clone . ./simpli-hello
# git log --pretty=short --abbrev-commit --since=v0.0.1 > ./simpli-hello/changes.log.txt


rm -rf ./simpli-hello/.git
rm -rf ./simpli-hello/.gitignore
rm -rf ./simpli-hello/make.sh
zip -r simpli-hello.zip simpli-hello/
rm -rf ./simpli-hello