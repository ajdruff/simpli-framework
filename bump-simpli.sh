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
# binary files such as images should be included here to avoid corruption
excluded_files=".*\.\(zip\|png\|jpg\|gif\)"



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
old_version=$(ls "${lib_dir}" | grep Simpli | sed -e 's/Simpli\([vc0-9]*\)/\1/g')  #returns the full version number 1-0-0
#break the result into its component parts
major=$(echo "${old_version}" | sed -e 's/v\([0-9]*\)c[0-9]*/\1/g')  # returns 2 from v2c1
minor=$(echo "${old_version}" | sed -e 's/v[0-9]*c\([0-9]*\)/\1/g')  # returns 1 from v2c1

#echo 'major='"${major}"
#echo 'minor='"${minor}"



old_directory_name=Simpliv"${major}"c"${minor}"



if [[  "$2" = "major" ]]
then
let major="${major}"+1
fi

if [[  "$2" = "minor" ]]
then
let minor="${minor}"+1
fi


#echo 'new major='"${major}"
#echo 'new minor='"${minor}"


new_version_with_decimals="${major}"."${minor}"

new_version=v"${major}"c"${minor}"
new_directory_name=Simpli"${new_version}"

#echo 'old version = '"${old_version}"
#echo 'new version = '"${new_version}"



echo 'bumping to new version ' "${new_version_with_decimals}"
echo 'please wait ...'
#first, rename the directory name. What happens of a permission denied error? then kill explorer and try again
#rename lib/Simpli_X_Y_Z directory to one with new version
mv "${lib_dir}"/"${old_directory_name}" "${lib_dir}"/"${new_directory_name}"


#check error . if 1 then assume permission denied error
if [[  "$?" = "1" ]]
then
die "
Unable to bump version. Permission denied on directory "${lib_dir}"/"${old_directory_name}"
"
fi

#change any references to patterns like 'Simpliv1c1_' in the Simpli class library and change them to new version Simpliv1c2_
find "${lib_dir}"/"${new_directory_name}"  -not -regex "${excluded_files}" -type f | xargs -n 1 sed -i -e "s#Simpli[vc0-9]*\(_[^\s]*\)#Simpli${new_version}\1#g"


#not used, but this is a more selective pattern for Class declarations
#rename all the base classes that use Simpli in their declaration
#echo "find "${lib_dir}"/"${old_directory_name}" -type f | xargs -n 1 sed -i -e "s#$[cC]lass\\s*Simpli_\(_0-9\)*_#${new_class_prefix}#g""
#find "${lib_dir}"/"${new_directory_name}" -type f | xargs -n 1 sed -i -e "s#[cC]lass\s*Simpli[vc0-9]*_#Class Simpli${new_version}_#g"

#not used, but this is a more selective pattern for Interface declarations
#change all the interfaces that use Simpli in their declarations
#find "${lib_dir}"/"${new_directory_name}" -type f | xargs -n 1 sed -i -e "s#[iI]nterface\s*Simpli[vc0-9]*_#interface Simpli${new_version}_#g"




######################
#now do a wider search in the full plugin directory to catch references to modules and interfaces
######################

#change all the class declarations that implement the changed interfaces
find "${plugin_dir}"/  -not -regex "${excluded_files}" -type f | xargs -n 1 sed -i -e "s#[iI]mplements\s*Simpli[vc0-9]*_#implements Simpli${new_version}_#g"


#change all the references to a Simpli interface  # e.g. Simpliv1c0_Logger_Interface
#groups the suffix
#uses backslash to escape the group
#\1 is the capture group
find "${plugin_dir}"/  -not -regex "${excluded_files}" -type f | xargs -n 1 sed -i -e "s#Simpli[vc0-9]*\(_[^z]*[Ii]nterface\)#Simpli${new_version}\1#g"

#change all the references to a Simpli Module e.g. Simpliv1c0_Plugin_Module
#groups the suffix
#uses backslash to escape the group
#\1 is the capture group
find "${plugin_dir}"/  -not -regex "${excluded_files}" -type f | xargs -n 1 sed -i -e "s#Simpli[vc0-9]*\(_[^\s]*[Mm]odule\)#Simpli${new_version}\1#g"




#change all the class declarations that extend the base classes
find "${plugin_dir}"/  -not -regex "${excluded_files}" -type f | xargs -n 1 sed -i -e "s#[eE]xtends\s*Simpli[vc0-9]*_#extends Simpli${new_version}_#g"

#change the autoload class in plugin.php
find "${plugin_dir}"/plugin.php  -not -regex "${excluded_files}" -type f | xargs -n 1 sed -i -e "s#base_class_version='[vc0-9]*'#base_class_version='${new_version}'#g"



#completed
echo "Successfully bumped the Simpli base class library to" "${new_version_with_decimals}"