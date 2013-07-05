#!/usr/bin/bash


#add some stdout and help instructions
#create php form script

die () {
echo >&2 "$@"
exit 1
}

#############
#validate arguments
#############

if [[ "$1" = --help ]]
then
die "
Usage: make SLUG DEST

Example: ./make.sh acme_gallery ../


Creates a new plugin project with unique identifier SLUG and places
it in the DEST directory.


SLUG should be in the form company_shortname where
'company' is a single-word identifier for the
plugin company or author and 'shortname' is a single word identifier
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
read -e -p "Enter the plugin slug in the form 'company_shortname': " -i "" input_slug

read -e -p "Enter the path to the target directory: [../] "  input_dir

fi

# if the directory is empty, set it to default
if [[ "${input_dir}" = "" ]]
then
input_dir="../"
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
#define variables
#############

old_slug='simpli_hello';
dir_name='simpli-framework';

#make the input_slug lower case and rename it
new_slug=$(echo ${input_slug} | awk '{print tolower($0)}')  # company_shortname





target_dir_parent=$(realpath "${input_dir}")





# if the destination directory is invalid, exit
if [[ ! -d "${target_dir_parent}" && ! -L "${target_dir_parent}" ]]
then
die "
Target directory ${target_dir_parent} does not exist, exiting.
"
fi



target_dir=$(realpath "$target_dir_parent")"/${new_slug}"
######################################################
#debug
#echo 'new slug'=$new_slug
#echo 'input_dir'=$input_dir


#echo 'target_dir='$target_dir
#echo 'target_dir_parent='$target_dir_parent
#echo 'exiting';exit 1;
###########################################


if [[ "${target_dir}" == *"${dir_name}"* ]]
then
die "
Target Directory must be outside of the simpli_framework directory
Try 'make --help'
"
fi



old_constant=$(echo $old_slug | awk '{print toupper($0)}') # SIMPLI_HELLO
new_constant=$(echo $new_slug | awk '{print toupper($0)}') # COMPANY_SHORTNAME





old_slug_dashes=$(echo $old_slug | tr '_' '-') # simpli-hello
new_slug_dashes=$(echo $new_slug | tr '_' '-') # company-shortname

old_prefix=$(echo ${old_slug} | cut -d _ -f 1) # simpli
new_prefix=$(echo ${new_slug} | cut -d _ -f 1) # hello

old_prefix_cap=${old_prefix^} # Simpli
new_prefix_cap=${new_prefix^} # Company


old_suffix=$(echo ${old_slug} | cut -d _ -f 2) # company
new_suffix=$(echo ${new_slug} | cut -d _ -f 2) # shortname


old_suffix_cap=${old_suffix^} # Hello
new_suffix_cap=${new_suffix^} # Shortname


old_class_filename=${old_suffix_cap} # Hello
new_class_filename=${new_suffix_cap} # Shortname


#module names
old_module_name=${old_prefix^}_Module # Simpli_Module
new_module_name=${new_prefix^}_Module # Company_Module

#logger
old_logger_name=${old_prefix^}_Logger # Simpli_Logger
new_logger_name=${new_prefix^}_Logger # Company_Logger

#class
old_class='class '${old_suffix_cap} # class Hello
new_class='class '${new_suffix_cap} # class Shortname
old_class_cap=${old_class^} # Class Hello
new_class_cap=${new_class^} # Class Shortname

#Plugin Name
old_plugin_name="${old_prefix^} ${old_suffix_cap}" # Simpli Hello
new_plugin_name="${new_prefix^} ${new_suffix_cap}" # Company Shortname

#WordPress Plugin Info Header
#must escape forward slashes with triple slashes
read -d '' new_wp_plugin_header <<EOF
Plugin Name:   ${new_plugin_name}
Plugin URI:    http:\\\/\\\/example.com
Description:   The ${new_plugin_name} plugin does some amazing stuff and was built upon the Simpli framework, a WordPress Plugin development framework that makes building WordPress plugins just a bit easier.
Author:        <AUTHOR_NAME>
Version:       1.0.0
Author URI:    http:\\\/\\\/example.com
Text Domain:   ${new_slug}
Domain Path:   \\\/languages\\\/
EOF


wp_plugin_header_pattern="Plugin(.+?)languages\/"


#############
#Execute
#############




#############
#testing
#############

#cp -r ./* "${target_dir}" 2>/dev/null


#remove framework documentation
#rm  "${target_dir}"/*.html


#echo 'testing'

#replace any remaining references of 'Hello' in plugin.php
#find "${target_dir}/test.php" -type f | xargs -n 1 sed -i -e "s#${old_suffix_cap}#${new_suffix_cap}#g"

#exit 1;
#############
#End Testing
#############
echo "Creating your new plugin ,  '${new_plugin_name}' , using the Simpli Framework..."

#create a subdirectory the same name as the new_slug
mkdir -p "${target_dir}";

if [[ ! (-d "${target_dir}" && ! -L "${target_dir}") ]] ; then
die "Target Directory is invalid, exiting..."
fi


cp -r ./* "${target_dir}" 2>/dev/null


#remove git directory or it will take forever to complete
rm -rf  "${target_dir}"/.git



#remove framework documentation
rm  "${target_dir}"/*.html




#replace WordPress Header
echo 'converting WordPress Plugin Information Header...'
perl -0777 -i -e "s/${wp_plugin_header_pattern}/${new_wp_plugin_header}/sg" "${target_dir}/plugin.php"


#need to exclude binary files or you will corrupt them when searching and replacing
excluded_files=".*\.\(zip\|png\|jpg\|gif\)"
#replace slugs
echo 'converting slugs...'
find "${target_dir}/" -not -regex "${excluded_files}" -type f |  xargs -n 1 sed -i -e "s|${old_slug}|${new_slug}|g"

#replace slug_dashes
find "${target_dir}/" -not -regex "${excluded_files}" -type f | xargs -n 1 sed -i -e "s|${old_slug_dashes}|${new_slug_dashes}|g"


#replace constants
echo 'converting constants...'
find "${target_dir}/" -not -regex "${excluded_files}" -type f | xargs -n 1 sed -i -e "s|${old_constant}|${new_constant}|g"


#replace module names
echo 'converting modules...'
find "${target_dir}/" -not -regex "${excluded_files}" -type f | xargs -n 1 sed -i -e "s|${old_module_name}|${new_module_name}|g"


#replace class names
#change class names
echo 'converting classes...'
find "${target_dir}/" -not -regex "${excluded_files}" -type f | xargs -n 1 sed -i -e "s|${old_class}|${new_class}|g"
find "${target_dir}/" -not -regex "${excluded_files}" -type f | xargs -n 1 sed -i -e "s|${old_class_cap}|${new_class_cap}|g"




#rename files
mv "${target_dir}"/lib/"${old_class_filename}" "${target_dir}""/lib/${new_class_filename}"
mv "${target_dir}"/lib/"${old_class_filename}.php" "${target_dir}""/lib/${new_class_filename}.php"

#update any remaining classes or namespaces that match the old suffix
find "${target_dir}/" -not -regex "${excluded_files}" -type f | xargs -n 1 sed -i -e "s#${old_suffix_cap}_\(.*\)#${new_suffix_cap}_\1#"


#replace plugin names
echo 'converting plugin names...'
find "${target_dir}/" -not -regex "${excluded_files}" -type f | xargs -n 1 sed -i -e "s|${old_plugin_name}|${new_plugin_name}|g"

#replace any remaining references of 'Hello' in plugin.php
find "${target_dir}/plugin.php" -not -regex "${excluded_files}" -type f | xargs -n 1 sed -i -e "s#${old_suffix_cap}#${new_suffix_cap}#g"


#replace javadoc tokens
echo 'converting javadoc tokens...'
find "${target_dir}/" -not -regex "${excluded_files}" -type f | xargs -n 1 sed -i -e "s#@package\\s*${old_suffix_cap}#@package ${new_prefix_cap}${new_suffix_cap}#"




#completed
echo "Completed! - Your new plugin ${new_plugin_name} is in sub-directory ${target_dir}"