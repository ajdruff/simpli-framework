#!/usr/bin/bash


#add some stdout and help instructions
#create php form script

 old_slug='simpli_great';
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

  
  
 #echo 'first argument is ' $1
 #echo $old_slug
 #echo $new_slug
 #echo $old_constant
 #echo $new_constant
 #echo $old_slug_dashes
 #echo $new_slug_dashes
 # echo $old_module_name
 #echo $old_class
  #echo $old_class_cap
   # echo $new_plugin_name
 
 #replace slugs
 echo 'updating slugs...'
 find ./ -type f | xargs -n 1 sed -i -e "s|${old_slug}|${new_slug}|g" 
 
 
   #replace slug_dashes
 find ./  -type f | xargs -n 1 sed -i -e "s|${old_slug_dashes}|${new_slug_dashes}|g" 
 
 
  #replace constants
   echo 'updating constants...'
 find ./ -type f | xargs -n 1 sed -i -e "s|${old_constant}|${new_constant}|g" 
 
 
 #replace module names
 echo 'updating modules...'
 find ./ -type f | xargs -n 1 sed -i -e "s|${old_module_name}|${new_module_name}|g" 
 
 
 #replace class names
  echo 'updating classes...'
 find ./ -type f | xargs -n 1 sed -i -e "s|${old_class}|${new_class}|g" 
 find ./ -type f | xargs -n 1 sed -i -e "s|${old_class_cap}|${new_class_cap}|g" 
 
 
 #replace plugin names
 echo 'updating plugin names...'
 find ./ -type f | xargs -n 1 sed -i -e "s|${old_plugin_name}|${new_plugin_name}|g"

  
  
  
  
 
 