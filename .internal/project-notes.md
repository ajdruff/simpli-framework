### Simpli Framework Project Notes


###How to Use FAQs
How to Use the Btools Class
The base Btools class is instantiated in Plugin's getTools() method.

$this->getPlugin()->getTools().



#How to disable Modules
do this in the Plugin init() method:
$this->setDisabledModule('My Module');
This will prevent loading of the specified module.
If you have a lot of modules that you dont want loaded, simply remove them from the Module directory into another directory, like 'Disabled_Modules' or something.

#Difference between Plugins and Addons

Addons are sub-plugins, that is, they are plugins that are intended to run within another plugin.

Here are the main differences:
-addons have no ability to load/save/set settings of their own. if you want an addon to use a setting, it must refer back to the plugins main settings.
-addons can be disabled by adding them to the _addons_disabled array.
-addons can disable modules by using the addon's setDisabledModule method
-addons dont have the same module loading routine as plugins - they differ in loading in that they do not check a regex pattern to allow certain modules to load and not allow others when the plugin is disabled.Instead, the addon is either all enabled or all disabled.

#search and replace patterns for netbeans

### remove all trace statements:
search:
^\s*(\$.*->debug\(\)->t.*\(\).*$)
replace:
<nothing> or if want to comment then out:
//$1


###remove all debug statements
search:
^\s*(\$.*->debug\(\)->(t|v|e|sst|st)\(.*\).*)$
replace:
<nothing> or if want to comment then out:
//$1

###Add $this->debug()->t(); to every function
search:
^[^/]*function.*\(.*\).*[\s\S]?.*\{

replace:
$0\n\$this->debug()->t();\n


###remove comments from all commented backtrace statements

search:
^\s*//.*(\$.*->debug\(\)->(t|v|e|sst|st)\(.*\).*$)
replace:
$1


###How to Create a Form that uses Ajax

1. create a meta box template
post_user_options_metabox_ajax_options.php
2.
Edit the template and create your form:
formStart(array(
            'name' => 'simpli_forms_ajaxoptions',
            'theme' => 'Admin',
            'action' => 'option_save',
            'method' => 'post',
            'template' => __FUNCTION__,
            'filter' => 'Settings',)

        );

...

3. Add the metabox that will add the template to your post.
                        add_meta_box(
                    $this->getSlug() . '_' . 'metabox_ajax_options'  //Meta Box DOM ID
                    , __($this->getPlugin()->getName(), $this->getPlugin()->getTextDomain()) //title of the metabox.
                    , array($this, 'renderMetaBoxTemplate')//function that prints the html
                    , $post->post_type// post_type when you embed meta boxes into post edit pages
                    , 'advanced' //normal advanced or side The part of the page where the metabox should show
                    , 'high' // 'high' , 'core','default', 'low' The priority within the context where the box should show
                    , null //$metabox['args'] in callback function
                    //,  array('path' => $this->getPlugin()->getDirectory() . '/admin/templates/metabox/post.php') //$metabox['args'] in callback function
            );

4.

add an ajax hook

        /* save without reloading the page */
        add_action('wp_ajax_' . $this->getPlugin()->getSlug() . '_settings_save', array($this, 'hookAjaxSave'));

5.
Create the method
    /**
     * Hook - Ajax Save
     *
     * Save the post options using ajax
     *
     * @param none
     * @return void
     */
    public function hookAjaxSave() {

//        if (!wp_verify_nonce($_POST['_wpnonce'], $this->getPlugin()->getSlug())) {
//            return false;
//        }

        $message = __("Post Options Saved.", $this->getPlugin()->getTextDomain());
        $errors = array(); // initialize the error array , add any validation errors when you scrub the form_field values



        //return a success message on submission
        require_once($this->getPlugin()->getDirectory() . '/admin/templates/ajax_message.php');

        die(); //required after require to ensure ajax request exits cleanly; otherwise it hangs and browser request is garbled.


    }

6. add the ajax javascript

a.save javascript to a file in the admin/js directory
add a similar line to your postoptions addHooks method:


                // Add scripts
        add_action('admin_enqueue_scripts', array($this, 'hookEnqueueScripts'));


add the hook method:
    /**
     * Hook Enqueue Scripts
     *
     * Enqueue javascript and styles
     *
     * @param none
     * @return void
     */
    public function hookEnqueueScripts() {
                    /*
         * Add javascript for form submission
         *
         */
        $handle = $this->getPlugin()->getSlug() . '_save-menu-options-post.js';
        $path = $this->getPlugin()->getDirectory() . '/admin/js/save-post-options.js';
        $inline_deps = array();
        $external_deps = array('jquery');
        $this->getPlugin()->enqueueInlineScript($handle, $path, $inline_deps, $external_deps);



    }





-



#####log:
1.
1.
1.


