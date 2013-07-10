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
override_version=1; # default=0
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
old_version=$(ls "${lib_dir}""/Simpli/" | grep Basev | sed -e 's/Base\([vc0-9]*\)/\1/g')  #returns the full version number 1-0-0
#break the result into its component parts
major=$(echo "${old_version}" | sed -e 's/v\([0-9]*\)c[0-9]*/\1/g')  # returns 2 from v2c1
minor=$(echo "${old_version}" | sed -e 's/v[0-9]*c\([0-9]*\)/\1/g')  # returns 1 from v2c1




echo 'major='"${major}"
echo 'minor='"${minor}"



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
#echo 'new major='"${major}" ; echo 'new minor='"${minor}" ; exit 1;


new_version_with_decimals="${major}"."${minor}"

new_version=v"${major}"c"${minor}"
new_directory_name=Base"${new_version}"

#echo 'old version = '"${old_version}"
#echo 'new version = '"${new_version}"



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


#change the autoload class in plugin.php
find "${plugin_dir}"/plugin.php  | grep -v '.git' | xargs -n 1 sed -i -e "s#\(Simpli_Framework[a-zA-Z0-9]*::.*(.*__FILE__.*\)'[vc0-9]*'#\1'${new_version}'#g"


echo 'stopping - check that simpli framework version was updated' ; exit 1;

# the rest of this seems nonsense  although more selective


#not used, but this is a more selective pattern for Class declarations
#rename all the base classes that use Simpli in their declaration
#echo "find "${lib_dir}"/"${old_directory_name}" -type f | xargs -n 1 sed -i -e "s#$[cC]lass\\s*Simpli_\(_0-9\)*_#${new_class_prefix}#g""
#find "${lib_dir}"/"${new_directory_name}" -type f | grep -v '.git' | xargs -n 1 sed -i -e "s#[cC]lass\s*Simpli[vc0-9]*_#Class Simpli${new_version}_#g"

#not used, but this is a more selective pattern for Interface declarations
#change all the interfaces that use Simpli in their declarations
#find "${lib_dir}"/"${new_directory_name}" -type f | grep -v '.git' | xargs -n 1 sed -i -e "s#[iI]nterface\s*Simpli[vc0-9]*_#interface Simpli${new_version}_#g"




######################
#now do a wider search in the full plugin directory to catch references to modules and interfaces
######################

#change all the class declarations that implement the changed interfaces  e.g.: Implements Simpliv1c0_
find "${plugin_dir}"/  -not -regex "${excluded_files}" -type f | grep -v '.git' | xargs -n 1 sed -i -e "s#[iI]mplements\s*Simpli[vc0-9]*_#implements Simpli${new_version}_#g"


#change all the references to a Simpli interface  # e.g. Simpliv1c0_Logger_Interface
#groups the suffix
#uses backslash to escape the group
#\1 is the capture group
find "${plugin_dir}"/  -not -regex "${excluded_files}" -type f | grep -v '.git' | xargs -n 1 sed -i -e "s#Simpli[vc0-9]*\(_[^\s]*[Ii]nterface\)#Simpli${new_version}\1#g"

#change all the references to a Simpli Module e.g. Simpliv1c0_Plugin_Module
#groups the suffix
#uses backslash to escape the group
#\1 is the capture group
find "${plugin_dir}"/  -not -regex "${excluded_files}" -type f | grep -v '.git' | xargs -n 1 sed -i -e "s#Simpli[vc0-9]*\(_[^\s]*[Mm]odule\)#Simpli${new_version}\1#g"




#change all the class declarations that extend the base classes
find "${plugin_dir}"/  -not -regex "${excluded_files}" -type f | grep -v '.git' | xargs -n 1 sed -i -e "s#[eE]xtends\s*Simpli[vc0-9]*_#extends Simpli${new_version}_#g"

#change the autoload class in plugin.php
find "${plugin_dir}"/plugin.php  | grep -v '.git' | xargs -n 1 sed -i -e "s#base_class_version\s*=\s*'[vc0-9]*'#base_class_version='${new_version}'#g"



#completed
echo "Successfully bumped the Simpli base class library to" "${new_version_with_decimals}"


###notes on