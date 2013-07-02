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
Usage: make COMPANY_SLUG

Creates a new subfolder containing the framework's files reconfigured for the new plugin name
in the format COMPANY_SHORTNAME where COMPANY is a unique identifier for the 
plugin author and SHORTNAME is a single word identifier for the plugin. 
No spaces, special characters (except a single underscore) permitted. Example: config acme_gallery
"
fi

if [[  "$1" = "" ]] 
then
die "
Usage: make COMPANY_SLUG
Try 'make --help'
"
fi

[ "$#" -eq 1 ] || die "
1 argument required, $# provided
Try 'make --help'
"

#############
#define variables
#############

 old_slug='simpli_hello';
 dir_name='simpli-framework';
 
 new_slug=$1; #first argument
 
 old_constant=$(echo $old_slug | awk '{print toupper($0)}')
 new_constant=$(echo $new_slug | awk '{print toupper($0)}')

 old_slug_dashes=$(echo $old_slug | tr '_' '-')
 new_slug_dashes=$(echo $new_slug | tr '_' '-')
 
old_prefix=$(echo ${old_slug} | cut -d _ -f 1)
old_suffix=$(echo ${old_slug} | cut -d _ -f 2)
new_prefix=$(echo ${new_slug} | cut -d _ -f 1)
new_suffix=$(echo ${new_slug} | cut -d _ -f 2)
 
 #module names
 old_module_name=${old_prefix^}_Module
 new_module_name=${new_prefix^}_Module
 
  #logger
 old_logger_name=${old_prefix^}_Logger
 new_logger_name=${new_prefix^}_Logger
 
 #class
 old_class='class '${old_suffix^}
 new_class='class '${new_suffix^}
 old_class_cap=${old_class^}
 new_class_cap=${new_class^}
 
 #Plugin Name
 old_plugin_name="${old_prefix^} ${old_suffix^}"
 new_plugin_name="${new_prefix^} ${new_suffix^}"

#############
#Execute
#############
 

 
 #create a subdirectory the same name as the new_slug
  mkdir "${new_slug}";cp ./* ./"${new_slug}" 2>/dev/null
  
 #remove git directory or it will take forever to complete 
 rm -rf  "${new_slug}/.git"

 #replace slugs
 echo 'updating slugs...'
 find "./${new_slug}/" -type f | xargs -n 1 sed -i -e "s|${old_slug}|${new_slug}|g" 
 
 
   #replace slug_dashes
 find "./${new_slug}/"  -type f | xargs -n 1 sed -i -e "s|${old_slug_dashes}|${new_slug_dashes}|g" 
 
 
  #replace constants
   echo 'updating constants...'
 find "./${new_slug}/" -type f | xargs -n 1 sed -i -e "s|${old_constant}|${new_constant}|g" 
 
 
 #replace module names
 echo 'updating modules...'
 find "./${new_slug}/" -type f | xargs -n 1 sed -i -e "s|${old_module_name}|${new_module_name}|g" 
 
 
 #replace class names
  echo 'updating classes...'
 find "./${new_slug}/" -type f | xargs -n 1 sed -i -e "s|${old_class}|${new_class}|g" 
 find "./${new_slug}/" -type f | xargs -n 1 sed -i -e "s|${old_class_cap}|${new_class_cap}|g" 
 
 
 #replace plugin names
 echo 'updating plugin names...'
 find "./${new_slug}/" -type f | xargs -n 1 sed -i -e "s|${old_plugin_name}|${new_plugin_name}|g"