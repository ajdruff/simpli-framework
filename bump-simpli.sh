#!/usr/bin/bash


## define die function
die () {
echo >&2 "$@"
exit 1
}



#set the path of this script http://stackoverflow.com/a/76257
SELF_PATH=$(readlink /proc/$$/fd/255)

#full path to the parent directory of this script
SELF_PARENT_DIR_PATH=$(dirname "${SELF_PATH}")

#set the parent directory of this script")
SELF_PARENT_DIR_NAME=$(basename "$(echo $(dirname "${SELF_PATH}"))")





#################
# Configure Script
################

# add the extensions of any files you dont want included in search and replace
# binary files such as images should be excluded to avoid corruption
# git should be excluded since we are doing the bump from within the working directory
# sh scripts should be excluded so as not to corrupt this script
excluded_files=".*\.\(zip\|png\|jpg\|gif\|git\|gitignore\|gitattributes\|sh\)"



# set override_version = 1, and then set major and minor to the versions that you want if you want a specific version (good for reversing a bump)
override_version=0; # default=0
override_major=1;
override_minor=0;



#############
#validate arguments
#############

if [[ "$1" = --help ]]
then
die "
Usage: bump-simpli DIR PART

Bumps the version of the Simpli Framework base classes.
Example: bump ./ major

DIR is the path to the Simpli Framework plugin directory
PART major, minor, or bug indicating which part of version MAJOR.MINOR.BUG should be bumped





"
fi

if [[  "$1" = "" ]]
then
die "
Usage: bump-simpli DIR PART
Try 'bump-simpli --help'
"
fi

[ "$#" -eq 2 ] || die "
2 arguments required, $# provided
Try 'bump-simpli --help'
"

#############
#parse arguments
#############

plugin_dir=$(realpath "$1")
ver_part="$2"

lib_dir="${plugin_dir}""/lib"

##debug
#echo 'plugin dir=' $plugin_dir
#echo 'ver_part=' $ver_part






#############
#validate arguments
#############
if [[ ! (-d "${lib_dir}" && ! -L "${lib_dir}") ]] ; then
die "The path to the Simpli Framework is invalid, exiting..."
fi

#############
#define variables
#############

######################
#
# Take the directory name lib/Simpli and parse it for version number
#
#######

# list the lib directory and look for a directory name that starts with Simpli
old_version=$(ls "${lib_dir}""/Simpli/" | grep "Basev" | sed -e 's/Base\([vc0-9]*\)/\1/g')  #returns the full version number 1-0-0




#break the result into its component parts
major=$(echo "${old_version}" | sed -e 's/v\([0-9]*\)c[0-9]*/\1/g')  # returns 2 from v2c1
minor=$(echo "${old_version}" | sed -e 's/v[0-9]*c\([0-9]*\)/\1/g')  # returns 1 from v2c1







old_directory_name=Basev"${major}"c"${minor}"



if [[  "$2" = "major" ]]
then
let major="${major}"+1
fi

if [[  "$2" = "minor" ]]
then
let minor="${minor}"+1
fi



## ignore the input if the override is set to true
## this provides a way to reset the version back to an older version by hardcoding it by
## defining override_major and override_minor at the start of this script
if [[ "${override_version}" = 1 ]]
then
major="${override_major}"
minor="${override_minor}"
fi




new_version_with_decimals="${major}"."${minor}"

new_version=v"${major}"c"${minor}"
new_directory_name=Base"${new_version}"



###########
# begin debug
###########
debug=0
if [[ "${debug}" = 1 ]]
then

echo '$old_version='$old_version

echo 'major='"${major}"
echo 'minor='"${minor}"

echo 'new major='"${major}" ; echo 'new minor='"${minor}"

echo 'old version = '"${old_version}"
echo 'new version = '"${new_version}"

# old form:
#find "${plugin_dir}"/plugin.php  | grep -v '.git' | xargs -n 1 sed -i -e "s#\(Simpli_Framework[a-zA-Z0-9]*::.*(.*__FILE__.*\)'[vc0-9]*'#\1'${new_version}'#g"
#new form:
#find "${plugin_dir}"/plugin.php  | grep -v '.git' | xargs -n 1 sed -i -e "s#\(Simpli Base Class Version*\)'#\1${new_version}#g"
#framework_version=$( sed -n -e '/Version:\s*/p' "${target_dir}/plugin.php")
#new_version=$( echo "${new_version}" |  sed  "s#v\([0-9]*\)c\([0-9]*\).*#\1.\2#g")
#match=$( sed -n -e '/Simpli\s*Base\s*Class\s*Version:\s*[.0-9]*.*$/p' "${plugin_dir}/plugin.php")
#match=$( echo "${match}" |  sed  "s#\(Simpli Base Class Version:\).*#\1 ${new_version_with_decimals}#g")

#replace 'Simpli Base Class Version: x.y' with 'Simpli Base Class Version: X.Y' where X.Y is new version
sed_expression='s#\(Simpli\s*Base\s*Class\s*Version:\)\s*[.0-9]*.*$#\1 '"${new_version_with_decimals}"'#g'
#find "${plugin_dir}"/plugin.php  | grep -v '.git' | xargs -n 1 sed -i -e  "${sed_expression}"

#test the match
match=$( echo "Simpli Base Class Version: 1.0" |  sed  "${sed_expression}" )

echo 'match = ' $match
#echo 'finished debug output' ;



echo ' exiting ' ; exit 1;
echo 'continuing ....'

fi
###########
# end testing
###########

echo 'bumping to new version ' "${new_version_with_decimals}"
echo 'please wait ...'
#first, rename the directory name. What happens of a permission denied error? then kill explorer and try again
#rename lib/Simpli_X_Y_Z directory to one with new version
mv "${lib_dir}""/Simpli/""${old_directory_name}" "${lib_dir}""/Simpli/""${new_directory_name}"


#check error . if 1 then assume permission denied error
if [[  "$?" = "1" ]]
then
die "
Unable to bump version. Permission denied on directory "${lib_dir}"/"${old_directory_name}"
"
fi




#search and replace patterns like 'Basev1c1_' to the newer versions
#find "${lib_dir}""/Simpli/""${new_directory_name}"  -not -regex "${excluded_files}" -type f | grep -v '.git' | xargs -n 1 sed -i -e "s#Base[vc0-9]*\(_[^\s]*\)#Base${new_version}\1#g"

find "${plugin_dir}"  -not -regex "${excluded_files}" -type f | grep -v '.git' | xargs -n 1 sed -i -e "s#Base[vc0-9]*\(_[^\s]*\)#Base${new_version}\1#g"

#################
# Update  'Simpli Base Class Version: x.y'  in the WordPress Header in plugin.php
###############
#replace 'Simpli Base Class Version: x.y' with 'Simpli Base Class Version: X.Y' where X.Y is new version
sed_expression='s#\(Simpli\s*Base\s*Class\s*Version:\)\s*[.0-9]*.*$#\1 '"${new_version_with_decimals}"'#g'
find "${plugin_dir}"/plugin.php  | grep -v '.git' | xargs -n 1 sed -i -e  "${sed_expression}"



################################################################


#completed
echo "Successfully bumped the Simpli base class library to" "${new_version_with_decimals}"


exit 1;
