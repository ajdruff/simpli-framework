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

debug=0 ; # default 0 . to debug, set to 1


# add the extensions of any files you dont want included in search and replace
# binary files such as images should be excluded to avoid corruption
# git should be excluded since we are doing the bump from within the working directory
# sh scripts should be excluded so as not to corrupt this script
excluded_files=".*\.\(zip\|png\|jpg\|gif\|git\|gitignore\|gitattributes\|sh\)"



# set override_version = 0, and then set base_major and base_minor to the versions that you want if you want a specific version (good for reversing a bump)
override_version=0; # default=0

override_base_major=1;
override_base_minor=2;
override_base_bug=0;
override_framework_major=1;
override_framework_minor=3;
override_framework_bug=0;


#############
#validate arguments
#############


if [[ "$1" = --help ]]
then
die "
Usage: bump-simpli DIR PART SCOPE

Bumps the version of the Simpli Framework base classes.
Example: bump ./ base_major

DIR is the path to the Simpli Framework plugin directory
PART base_major, base_minor, or bug indicating which part of version MAJOR.MINOR.BUG should be bumped
SCOPE options include 'Base', 'Framework', and 'all'.
'Framework' only changes the Simpli Framework version number in plugin.php .
'Base' bumps the Simpli Framework Base Class version number and does a search
    and replace for all the BasecXcY version strings.
'All' is the default, which will bump both numbers.




"
fi

if [[  "$1" = "" ]]
then
die "
Usage: bump-simpli DIR PART
Try 'bump-simpli --help'
"
fi

if [ "$#" -lt 2 ]  ; then
die "
At least 2 arguments are required, $# provided
Try 'bump-simpli --help'
"
fi

if [ "$#" -gt 3 ]  ; then
die "
No more than 3 arguments, $# provided
Try 'bump-simpli --help'
"
fi
##########
# set SCOPE and convert input to lower case
##########
if [ "$#" -eq 2 ]  ; then
scope=all
fi


if [ "$#" -eq 3 ]  ; then
scope=$( echo "$3" | tr '[:upper:]' '[:lower:]' | xargs )
fi


#############
#parse arguments
#############

plugin_dir=$(realpath "$1")
ver_part="$2"


lib_dir="${plugin_dir}""/lib"

##debug
echo 'plugin dir='$plugin_dir
echo 'ver_part='$ver_part






#############
#validate arguments
#############
if [[ ! (-d "${lib_dir}" && ! -L "${lib_dir}") ]] ; then
die "The path to the Simpli Framework is invalid, exiting..."
fi


if [[ !("${scope}" == "all" || "${scope}" == "framework" || "${scope}" == "base") ]] ; then
die "SCOPE argument ${scope} is invalid. Please try 'bump-simpli --help'"
fi


#############
#define variables
#############

######################
#
# Take the directory name lib/Simpli and parse it for version number
#
#######

# list the lib/Simpli/Frames directory and look for a directory name that starts with Simpli
base_old_version=$(ls "${lib_dir}""/Simpli/Frames/" | grep "Basev" | sed -e 's/Base\([vc0-9]*\)/\1/g')  #returns the full version number 1-0-0




#break the result into its component parts
base_major=$(echo "${base_old_version}" | sed -e 's/v\([0-9]*\)c[0-9]*/\1/g')  # returns 2 from v2c1
base_minor=$(echo "${base_old_version}" | sed -e 's/v[0-9]*c\([0-9]*\)/\1/g')  # returns 1 from v2c1







old_directory_name=Basev"${base_major}"c"${base_minor}"


#################################
### get existing framework version
##################

##############
# Notes on regex expression
#### must escape () and + signs
# xargs is used to trim whitespace
#######

framework_version_regex_pattern='Simpli Framework Version:\s*\([0-9]\+\)\.\([0-9]\+\)\.\([0-9]\+\).*$'
major_sed_expression='s#'"${framework_version_regex_pattern}"'#\1#p'
minor_sed_expression='s#'"${framework_version_regex_pattern}"'#\2#p'
bug_sed_expression='s#'"${framework_version_regex_pattern}"'#\3#p'


#use xargs to trim whitespace
framework_major=$( sed -n -e "${major_sed_expression}" "${plugin_dir}/plugin.php" | xargs)
framework_minor=$( sed -n -e "${minor_sed_expression}" "${plugin_dir}/plugin.php" | xargs)
framework_bug=$( sed -n -e "${bug_sed_expression}" "${plugin_dir}/plugin.php" | xargs)


#######################
# new version numbers
#######################

if [[  "$2" = "major" ]] ; then
let base_major="${base_major}"+1
let framework_major="${framework_major}"+1
fi

if [[  "$2" = "minor" ]] ; then
let base_minor="${base_minor}"+1;
let framework_minor="${framework_minor}"+1;
fi

if [[  "$2" = "bug" ]] ; then
let base_minor="${base_bug}"+1
let framework_bug="${framework_bug}"+1
fi



## ignore the input if the override is set to true
## this provides a way to reset the version back to an older version by hardcoding it by
## defining override_major and override_minor at the start of this script
if [[ "${override_version}" = 1 ]]
then
echo 'Override is on, setting versions to the hard coded values';
base_major="${override_base_major}"
base_minor="${override_base_minor}"
base_bug="${override_base_bug}"

framework_major="${override_framework_major}"
framework_minor="${override_framework_minor}"
framework_bug="${override_framework_bug}"


fi




base_new_version_with_decimals="${base_major}"."${base_minor}"

base_new_version=v"${base_major}"c"${base_minor}"

framework_new_version="${framework_major}"."${framework_minor}"."${framework_bug}"


new_directory_name=Base"${base_new_version}"



###########
# begin debug
###########

if [[ "${debug}" = 1 ]]
then
echo 'SCOPE='"${scope}"



echo '##########  Base Version ########'


echo 'base_major='"${base_major}"
echo 'base_minor='"${base_minor}"

echo '$base_old_version='$base_old_version

echo 'new base_major='"${base_major}" ; echo 'new base_minor='"${base_minor}"

echo 'old version = '"${base_old_version}"
echo 'new version = '"${base_new_version}"


#replace 'Simpli Base Class Version: x.y' with 'Simpli Base Class Version: X.Y' where X.Y is new version
sed_expression='s#\(Simpli\s*Base\s*Class\s*Version:\)\s*[.0-9]*.*$#\1 '"${base_new_version_with_decimals}"'#g'


#test the match
match=$( echo "Simpli Base Class Version: 1.0" |  sed  "${sed_expression}" )

echo '##########  Framework Version ########'

echo 'framework_major='"${framework_major}"
echo 'framework_minor='"${framework_minor}"
echo 'framework_bug='"${framework_bug}"
echo 'framework_new_version='"${framework_new_version}"






echo 'finished debug output, exiting the script without changing anything' ;

exit 1;

fi
###########
# end testing
###########


#############
#Update Framework Version
#############



if [[ "${scope}" == "all" || "${scope}" == "framework"  ]] ; then

# Update 'Simpli Framework Version: X.Y.Z' in plugin.php
simpli_framework_version_sed_expression='s#\(Simpli\s*Framework\s*Version:\s*\)[.0-9]*.*$#\1'"${framework_new_version}"'#g'
find "${plugin_dir}"/plugin.php  | grep -v '.git' | xargs -n 1 sed -i -e "${simpli_framework_version_sed_expression}"

# Update   'Version:       X.Y.Z' in plugin.php
plugin_version_sed_expression='s#\(^\s*Version:\s*\)[.0-9]*.*$#\1'"${framework_new_version}"'#g'
find "${plugin_dir}"/plugin.php  | grep -v '.git' | xargs -n 1 sed -i -e "${plugin_version_sed_expression}"

echo 'Bumped Simpli Framework Version to ' "${framework_new_version}"

fi



#########
#Continue only if scope includes updating base.
#########

if [[ ! ("${scope}" == "all" || "${scope}" == "base") ]] ; then
echo 'Completed scope to bump only Framework, exiting script'
exit 1;
fi






echo 'Bumping Simpli Framework Base Class Library to new version ' "${base_new_version_with_decimals}"
echo 'please wait ...'
#first, rename the directory name. What happens of a permission denied error? then kill explorer and try again
#rename lib/Simpli_X_Y_Z directory to one with new version
mv "${lib_dir}""/Simpli/Frames/""${old_directory_name}" "${lib_dir}""/Simpli/Frames/""${new_directory_name}"


#check error . if 1 then assume permission denied error
if [[  "$?" = "1" ]]
then
die "
Unable to bump version. Permission denied on directory "${lib_dir}"/"${old_directory_name}"
"
fi




#search and replace patterns like 'Basev1c1_' to the newer versions

find "${plugin_dir}"  -not -regex "${excluded_files}" -type f | grep -v '.git' | xargs -n 1 sed -i -e 's#Basev[0-9]\+c[0-9]\+#Base'"${base_new_version}"'#g'


#################
# Update  'Simpli Base Class Version: x.y'  in the WordPress Header in plugin.php
###############
#replace 'Simpli Base Class Version: x.y' with 'Simpli Base Class Version: X.Y' where X.Y is new version
sed_expression='s#\(Simpli\s*Base\s*Class\s*Version:\)\s*[.0-9]*.*$#\1 '"${base_new_version_with_decimals}"'#g'
find "${plugin_dir}"/plugin.php  | grep -v '.git' | xargs -n 1 sed -i -e  "${sed_expression}"



################################################################


#completed
echo "Successfully bumped the Simpli base class library to" "${base_new_version_with_decimals}"


exit 1;
