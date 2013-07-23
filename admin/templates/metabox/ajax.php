<?php
/**
 * Gets Html for a metabox using ajax
 *
 * This script is included by the admin module's render method,which is called by an add_meta_box() call
 *
 * @package SimpliFramework
 * @subpackage SimpliHello
 */


/*
 * Use the setLocalVars method to pass the
 * metabox url to the ajax script, which will retrieve the html
 * for the metabox content
 */
$metaboxes=array(

    'remote_metaboxes'=>array(
        $metabox['id']=>$metabox['args']['url']
    )


);

$this->getPlugin()->setLocalVars($metaboxes);




$handle=$this->getPlugin()->getSlug() . '_get-remote-metabox-html.js';
$path=$this->getPlugin()->getDirectory(). '/admin/js/get-remote-metabox-html.js';
$inline_deps=array();
$external_deps=array('jquery');
$this->getPlugin()->enqueueInlineScript($handle,$path,$inline_deps,$external_deps);

?>