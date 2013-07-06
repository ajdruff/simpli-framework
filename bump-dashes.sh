#!/usr/bin/bash


#cd /cygdrive/c/wamp/www/wpdev.com/public_html/wp-content/plugins
#./simpli_amazing/bump.sh ./simpli_amazing bug


# works but math doesnt except with perl
#find "/cygdrive/c/wamp/www/wpdev.com/public_html/wp-content/plugins/simpli_amazing/lib/Simpli/Plugin.php" -type f | xargs sed -i -e 's/[Cc]lass\([0-9]\)/class\1+1/g'
#http://stackoverflow.com/questions/10781498/sed-regex-with-variables-to-replace-numbers-in-a-file



#find "/cygdrive/c/wamp/www/wpdev.com/public_html/wp-content/plugins/simpli_amazing/lib/Simpli/Plugin.php" -type f | xargs -n 1 | perl -pe 's/[cC]lass/Class2/ge'

#exit 1;
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
Usage: bump_simpli DIR PART

Bumps the version of the Simpli Framework base classes.

DIR is the path to the Simpli Framework plugin directory
PART major, minor, or bug indicating which part of version MAJOR.MINOR.BUG should be bumped


Example: bump_simpli ./ major


"
fi

if [[  "$1" = "" ]]
then
die "
Usage: bump_simpli DIR PART
Try 'bump_simpli --help'
"
fi

[ "$#" -eq 2 ] || die "
2 arguments required, $# provided
Try 'bump_simpli --help'
"

#############
#parse arguments
#############

plugin_dir=$(realpath "$1")
ver_part="$2"

lib_dir="${plugin_dir}""/lib"
##debug
echo 'plugin dir=' $plugin_dir
echo 'ver_part=' $ver_part






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
old_ver=$(ls "${lib_dir}" | grep Simpli | sed -e 's/Simpli-\([0-9]*-[0-9]*-[0-9]*\)/\1/g')  #returns the full version number 1-0-0
#break the result into its component parts
major=$(echo "${old_ver}" | sed -e 's/\([0-9]*\)-[0-9]*-[0-9]*/\1/g')  # returns 1 from 1-0-0
minor=$(echo "${old_ver}" | sed -e 's/[0-9]*-\([0-9]*\)-[0-9]*/\1/g')  # returns the first 0 from 1-0-0
bug=$(echo "${old_ver}" | sed -e 's/[0-9]*-[0-9]*-\([0-9]*\)/\1/g')    # returns the last 0 from 1-0-0
echo 'major='"${major}"
echo 'minor='"${minor}"
echo 'bug='"${bug}"

old_directory_name="Simpli-${major}"-"${minor}"-"${bug}"



if [[  "$2" = "major" ]]
then
let major="${major}"+1
fi

if [[  "$2" = "minor" ]]
then
let minor="${minor}"+1
fi

if [[  "$2" = "bug" ]]
then
let bug="${bug}"+1
fi


new_version_with_decimals="${major}"."${minor}"."${bug}"
new_directory_name="Simpli-${major}"-"${minor}"-"${bug}"
new_version_with_underscores="${major}"_"${minor}"_"${bug}"

#first, rename the directory name. What happens of a permission denied error? then must rename it manually or possibly bail if you can do it above.
#rename lib/Simpli_X_Y_Z directory to one with new version
mv "${lib_dir}"/"${old_directory_name}" "${lib_dir}"/"${new_directory_name}"
#mv /cygdrive/c/wamp/www/wpdev.com/public_html/wp-content/plugins/simpli_amazing/lib/Simpli-1-0-1 /cygdrive/c/wamp/www/wpdev.com/public_html/wp-content/plugins/simpli_amazing/lib/Simpli-1-0-2
#mv /cygdrive/c/wamp/www/wpdev.com/public_html/wp-content/plugins/simpli_amazing/lib/Simpli-1-0-2 /cygdrive/c/wamp/www/wpdev.com/public_html/wp-content/plugins/simpli_amazing/lib/Simpli-1-0-1

#check error . if 1 then assume permission denied error
if [[  "$?" = "1" ]]
then
die "
Unable to bump version. Permission denied on directory "${lib_dir}"/"${old_directory_name}"
"
fi



#rename all the base classes tha tuse Simpli in their declaration
#echo "find "${lib_dir}"/"${old_directory_name}" -type f | xargs -n 1 sed -i -e "s#$[cC]lass\\s*Simpli_\(_0-9\)*_#${new_class_prefix}#g""
find "${lib_dir}"/"${new_directory_name}" -type f | xargs -n 1 sed -i -e "s#[cC]lass\s*Simpli[0-9_]*#Class Simpli_${new_version_with_underscores}_#g"


#change all the interfaces that use Simpli in their declarations
find "${lib_dir}"/"${new_directory_name}" -type f | xargs -n 1 sed -i -e "s#[iI]nterface\s*Simpli[0-9_]*#interface Simpli_${new_version_with_underscores}_#g"


#change all the class declarations that implement the changed interfaces
find "${plugin_dir}"/ -type f | xargs -n 1 sed -i -e "s#[iI]mplements\s*Simpli[0-9_]*#implements Simpliv1c0_1_17_1_${new_version_with_underscores}_#g"


#change all the class declarations that extend the base classes
find "${plugin_dir}"/ -type f | xargs -n 1 sed -i -e "s#[eE]xtends\s*Simpli[0-9_]*#extends Simpliv1c0_1_17_1_${new_version_with_underscores}_#g"

exit 1;
#rename all the descendant classes
#find "${target_dir}/test.php" -type f | xargs -n 1 sed -i -e "s#${old_suffix_cap}#${new_suffix_cap}#g"




echo 'new version='"${new_version}"



exit 1;

old_slug='simpli_amazing';
dir_name='simpli-framework';

new_slug=$1; #first argument
new_slug=$(echo $new_slug | awk '{print tolower($0)}')  # company_shortname




target_dir=$(realpath "$2")"/${new_slug}"





if [[ "${target_dir}" == *"${dir_name}"* ]]
then
die "
Target Directory must be outside of the simpli_framework directory
Try 'make --help'
"
fi





old_constant=$(echo $old_slug | awk '{print toupper($0)}') # SIMPLI_AMAZING
new_constant=$(echo $new_slug | awk '{print toupper($0)}') # COMPANY_SHORTNAME





old_slug_dashes=$(echo $old_slug | tr '_' '-') # simpli-amazing
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
old_module_name=${old_prefix^}_Module # Simpliv1c0_Module
new_module_name=${new_prefix^}_Module # Company_Module

#logger
old_logger_name=${old_prefix^}_Logger # Simpli_Logger
new_logger_name=${new_prefix^}_Logger # Company_Logger

#class
old_class='class '${old_suffix_cap} # class Amazing
new_class='class '${new_suffix_cap} # class Shortname
old_class_cap=${old_class^} # Class Amazing
new_class_cap=${new_class^} # Class Shortname

#Plugin Name
old_plugin_name="${old_prefix^} ${old_suffix_cap}" # Simpli Amazing
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
echo "Creating new ${new_plugin_name} plugin using the Simpli Framework..."

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



#replace slugs
echo 'converting slugs...'
find "${target_dir}/" -type f | xargs -n 1 sed -i -e "s|${old_slug}|${new_slug}|g"



#replace slug_dashes
find "${target_dir}/"  -type f | xargs -n 1 sed -i -e "s|${old_slug_dashes}|${new_slug_dashes}|g"


#replace constants
echo 'converting constants...'
find "${target_dir}/" -type f | xargs -n 1 sed -i -e "s|${old_constant}|${new_constant}|g"


#replace module names
echo 'converting modules...'
find "${target_dir}/" -type f | xargs -n 1 sed -i -e "s|${old_module_name}|${new_module_name}|g"


#replace class names
#change class names
echo 'converting classes...'
find "${target_dir}/" -type f | xargs -n 1 sed -i -e "s|${old_class}|${new_class}|g"
find "${target_dir}/" -type f | xargs -n 1 sed -i -e "s|${old_class_cap}|${new_class_cap}|g"

#rename files
mv "${target_dir}"/lib/"${old_class_filename}" "${target_dir}""/lib/${new_class_filename}"
mv "${target_dir}"/lib/"${old_class_filename}.php" "${target_dir}""/lib/${new_class_filename}.php"

#update any remaining classes or namespaces that match the old suffix
find "${target_dir}/" -type f | xargs -n 1 sed -i -e "s#${old_suffix_cap}_\(.*\)#${new_suffix_cap}_\1#"


#replace plugin names
echo 'converting plugin names...'
find "${target_dir}/" -type f | xargs -n 1 sed -i -e "s|${old_plugin_name}|${new_plugin_name}|g"

#replace any remaining references of 'Hello' in plugin.php
find "${target_dir}/plugin.php" -type f | xargs -n 1 sed -i -e "s#${old_suffix_cap}#${new_suffix_cap}#g"


#replace javadoc tokens
echo 'converting javadoc tokens...'
find "${target_dir}/" -type f | xargs -n 1 sed -i -e "s#@package\\s*${old_suffix_cap}#@package ${new_prefix_cap}${new_suffix_cap}#"




#completed
echo "Completed! - Your new plugin ${new_plugin_name} is in sub-directory ${target_dir}"