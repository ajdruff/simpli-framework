#!/usr/bin/bash



#add some stdout and help instructions
#create php form script

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

#the slug of the framework demonstration plugin that will act as the template
simpli_frames='simpli_frames';

default_target_dir=$(dirname "${SELF_PARENT_DIR_PATH}")



#############
# Validate Script Arguments
#############

if [[ "$1" = --help ]]
then
die "
Usage: make SLUG DEST

Example: ./make.sh acme_gallery ../


Creates a new plugin project with unique identifier SLUG and places
it in the DEST directory.


SLUG should be in the form mycompany_myplugin where
'mycompany' is a single-word identifier for the
plugin company or author and 'myplugin' is a single word identifier
for the plugin. No spaces or special characters
(except a single underscore) are permitted.

DEST can be any directory path ending in a slash but cannot
contain the simpli_framework directory.

"
fi


# set inputs equal to the arguments. if no arguments passed, we'll prompt the
user
input_slug="$1" #first argument
input_dir="$2" #second argument





#if there were no arguments, prompt the user for them
if [[ "$#" = 0 ]]
then
read -e -p "Enter the plugin slug in the form 'mycompany_myplugin': " -i "" input_slug

read -e -p "Enter the path to the target directory: [""${default_target_dir}""] "  input_dir

fi

# if the directory is empty, set it to default
if [[ "${input_dir}" = "" ]]
then
input_dir="${default_target_dir}"
fi


# validate that the slug meets the format requirements (single words separated by an underscore)
slug_regex="[^_]*_[^_]"
if [[ ! "${input_slug}" =~ $slug_regex ]]
then
die "
Try again, slug must be two single words separated by an underscore. No special characters.
"
fi


# if the slug is empty, exit
if [[ "${input_slug}" = "" ]]
then
die "

Missing plugin slug, exiting.

Usage: make SLUG DEST
Try 'make --help'
"
fi






#############
# Variables
#############
# Note : variable names are intentionally of mixed format and mimic the expected format of its value
# this makes it a bit easier to spot errors in search and replace logic
# Read this script as if we were making a plugin called 'Mycompany_Myplugin'

#the parent directory of this script
dir_name="${SELF_PARENT_DIR_NAME}";


#make the input_slug lower case and rename it
mycompany_myplugin=$(echo ${input_slug} | awk '{print tolower($0)}')  # mycompany_myplugin



#use for constant replacements
SIMPLI_FRAMES=$(echo $simpli_frames | awk '{print toupper($0)}') # SIMPLI_FRAMES
MYCOMPANY_MYPLUGIN=$(echo $mycompany_myplugin | awk '{print toupper($0)}') # COMPANY_SHORTNAME




#text domains
simpliDASHframes=$(echo $simpli_frames | tr '_' '-') # simpli-frames
mycompanyDASHmyplugin=$(echo $mycompany_myplugin | tr '_' '-') # mycompany-myplugin

#prefixes
simpli=$(echo ${simpli_frames} | cut -d _ -f 1) # simpli
mycompany=$(echo ${mycompany_myplugin} | cut -d _ -f 1) # mycompany


#prefixes - title case
Simpli=${simpli^} # Simpli
Mycompany=${mycompany^} # Mycompany

#suffixes
frames=$(echo ${simpli_frames} | cut -d _ -f 2) # frames
myplugin=$(echo ${mycompany_myplugin} | cut -d _ -f 2) # myplugin

#suffixes - Title Case
Frames=${frames^} # Frames
Myplugin=${myplugin^} # Myplugin

Simpli_Frames="${Simpli}""_""${Frames}"
Mycompany_Myplugin="${Mycompany}""_""${Myplugin}"


#prefix_dot_suffix format
simpli_dot_frames="${simpli}"".""${frames}"
mycompany_dot_myplugin="${mycompany}"".""${myplugin}"
Simpli_dot_Frames="${Simpli}"".""${Frames}"
Mycompany_dot_Myplugin="${Mycompany}"".""${Myplugin}"

#echo "simpli_dot_frames=""${simpli_dot_frames}"
#echo "mycompany_dot_myplugin=""${mycompany_dot_myplugin}"
#echo "Simpli_dot_Frames=""${Simpli_dot_Frames}"
#echo "Mycompany_dot_Myplugin=""${Mycompany_dot_Myplugin}"
#exit 1

#Plugin Name
old_plugin_name="${Simpli}"" ""${Frames}" # Simpli Frames
new_plugin_name="${Mycompany}"" ""${Myplugin}" # Mycompany Myplugin


#echo "new_plugin_name=""${new_plugin_name}";exit 1;



#WordPress Plugin Info Header
#must escape forward slashes with triple slashes . In HEREDOC, must also continue to the next line by adding a backslash
new_line="\\\\n"
read -d '' new_wp_plugin_header <<EOF
Plugin Name:   ${new_plugin_name} ${new_line} \
Plugin URI:    http:\\\/\\\/example.com ${new_line} \
Description:   The ${new_plugin_name} plugin does some amazing stuff and was built upon the Simpli framework, a WordPress Plugin development framework that makes building WordPress plugins just a bit easier. ${new_line} \
Author:        Author ${new_line} \
Version:       1.0.0 ${new_line} \
Author URI:    http:\\\/\\\/example.com ${new_line} \
Text Domain:   ${mycompany_myplugin} ${new_line} \
Domain Path:   \\\/languages\\\/ ${new_line}
EOF


wp_plugin_header_pattern="Plugin(.+?)languages\/"



#############
# Target Directory - Define and Validate
#############


#resolve input directory to real path so we can validate it
target_dir_parent=$(realpath "${input_dir}")






# if the destination directory is invalid, exit
if [[ ! -d "${target_dir_parent}" && ! -L "${target_dir_parent}" ]]
then
die "
Target directory ${target_dir_parent} does not exist, exiting.
"
fi







target_dir=$(realpath "$target_dir_parent")"/${mycompanyDASHmyplugin}"

# if the destination directory already exists, exit
if [[  -d "${target_dir}"  ]]
then
die "
ERROR: Target directory ${target_dir} already exists!
Please delete existing directory, rename slug, or specify
a different target directory.
"
fi



if [[ "${target_dir}" == *"${dir_name}"* ]]
then
die "
Target Directory must be outside of the simpli_framework directory
Try 'make --help'
"
fi




#############
# Main
#############



echo "Creating your new plugin ,  '${new_plugin_name}' , using the Simpli Framework..."

#create a subdirectory the same name as the mycompany_myplugin
mkdir -p "${target_dir}";

if [[ ! (-d "${target_dir}" && ! -L "${target_dir}") ]] ; then
die "Target Directory is invalid, exiting..."
fi


#########################
# Copy Template to New Directory
#rsync is used so we can esily exclude multiple files and directories
#alternatively, use copy, but cant exclude: cp -r ./* "${target_dir}"

#########################


rsync -a --exclude=".*" --exclude="*.sh" --exclude="README.md" --exclude="debug.log.txt" --exclude="nbproject"  ./* "${target_dir}" 2>/dev/null



#########################
# Remove unnecessary files
# DEPRECATED but left for reference
# not needed since we can exclude with rsync (above)
#########################
# remove unneccessary files before replacements start

#remove git directory or it will take forever to complete
#rm -rf  "${target_dir}"/.git 2>/dev/null


#############
# start testing
#############
 #returns the plugins version number
#framework_version=$( sed -n -e 's/Version:\([.0-9]*\)/\1/p' "${target_dir}/plugin.php")
#framework_version=`echo $framework_version` #trims result of any whitespaces

#echo "framework version = |""${framework_version}"

#exit 1;

#############
# end testing
#############


#################
#
# Framework Version
#
#################
#returns the plugins version number taken from the WordPress Header and uses that as the Framework Version
framework_version=$( sed -n -e 's/Version:\([.0-9]*\)/\1/p' "${target_dir}/plugin.php")
framework_version=`echo $framework_version` #trims result of any whitespaces



#########################
# Directory Structure
#########################
#rename files

#rename Simpli/Frames to Mycompany/Myplugin

# make the new directories first
mkdir -p "${target_dir}""/lib/""${Mycompany}";
#move the company directory
mv "${target_dir}""/lib/""${Simpli}""/""${Frames}" "${target_dir}""/lib/""${Mycompany}"
wait
#now rename the frames directory
mv "${target_dir}""/lib/""${Mycompany}""/""${Frames}" "${target_dir}""/lib/""${Mycompany}""/""${Myplugin}"


#########################
#replace WordPress Header
#########################
echo 'converting WordPress Plugin Information Header...'

#use a sed multiline substituion by using /c ref:http://www.linuxtopia.org/online_books/linux_tool_guides/the_sed_faq/sedfaq4_013.html
find "${target_dir}"/plugin.php -type f | xargs -n 1 sed -i -e "/Plugin\s*Name:/, /languages/c$new_wp_plugin_header"


#alternative multiline substitution using perl but requires the p flag on windows which makes a .bak file
#perl -0777 -i -e -p "s/${wp_plugin_header_pattern}/${new_wp_plugin_header}/sg" "${target_dir}""/plugin.php"





#########################
# Slugs
#########################
#replace simpli_frames with mycompany_myplugin
echo 'converting slugs...'
find "${target_dir}/" -not -regex "${excluded_files}" -type f |  xargs -n 1 sed -i -e "s|${simpli_frames}|${mycompany_myplugin}|g"



#########################
# Text Domain Style
#########################
#replace simpli-frames with mycompany-myplugin
find "${target_dir}/" -not -regex "${excluded_files}" -type f | xargs -n 1 sed -i -e "s|${simpliDASHframes}|${mycompanyDASHmyplugin}|g"


#########################
# Constants
#########################
#replace SIMPLI_FRAMES with MYCOMPANY_MYPLUGIN
echo 'converting constants...'
find "${target_dir}/" -not -regex "${excluded_files}" -type f | xargs -n 1 sed -i -e "s|${SIMPLI_FRAMES}|${MYCOMPANY_MYPLUGIN}|g"


#########################
# Javascript variables (simpli.frames)
#########################
#replace simpli.frames with mycompany.myplugin and Simpli.Frames with Mycompany.Myplugin
echo 'converting javascript variables...'
find "${target_dir}/" -not -regex "${excluded_files}" -type f | xargs -n 1 sed -i -e "s|${Simpli}\.${Frames}|${MyCompany_dot_Myplugin}|g"
find "${target_dir}/" -not -regex "${excluded_files}" -type f | xargs -n 1 sed -i -e "s|${simpli}\.${frames}|${mycompany_dot_myplugin}|g"




#########################
# Class Namespaces
#########################
# replace Simpli_Frames_ with Mycompany_Myplugin
echo 'converting class namespaces'
find "${target_dir}/" -not -regex "${excluded_files}" -type f | xargs -n 1 sed -i -e "s|${Simpli_Frames}_|${Mycompany_Myplugin}_|g"




#########################
# Doc Block Tokens
#########################
#replace documentation tokens in phpdoc blocks
#todo: limit this only to the non-framework directories
echo 'converting doc block tokens ...' #deletes subpackage references and renames package
find "${target_dir}""/lib/""${Mycompany}"/"${Myplugin}""/" -not -regex "${excluded_files}" -type f | xargs -n 1 sed -i -e "s#@subpackage\\s*${Simpli}${Frames}##"
find "${target_dir}""/lib/""${Mycompany}"/"${Myplugin}""/" -not -regex "${excluded_files}" -type f | xargs -n 1 sed -i -e "s#@package\\s*'SimpliFramework#@package ${Mycompany}${Myplugin}#"



#########################
# Plugin Name
#########################

#echo 'stopping before plugin replacements so you can look at it' ; exit 1;
#replace 'Simpli Frames' with 'Mycompany Myplugin'
echo 'converting plugin names...'
find "${target_dir}/" -not -regex "${excluded_files}" -type f | xargs -n 1 sed -i -e "s|${old_plugin_name}|${new_plugin_name}|g"



#########################
# Framework Version
#########################




#replace Framework Version placeholder with version number
echo "Adding Framework Version Number"
find "${target_dir}/" -not -regex "${excluded_files}" -type f | xargs -n 1 sed -i -e "s|\"__SIMPLI_FRAMEWORK_VERSION__\"|\"${framework_version}\"|g"


#########################
# Permissions
# Ensure that the proper permissions are set or
# you'll recieve permission denied errors.
#########################
echo "Updating Permissions";
find "${target_dir}/" -type d -exec chmod 755 {} \;
find "${target_dir}/" -type f -exec chmod 644 {} \;



#########################
# Done !
#########################
#completed
echo "Completed! - Your new plugin ${new_plugin_name} is in sub-directory ${target_dir}" ;

exit 1 ;