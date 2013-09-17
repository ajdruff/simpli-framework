<div id="simpli-hello">
    <?php
//echo '<pre>';
//echo '<pre>';
//$this->plugin()->getModule('Form')->text($field_name, $value, $label, $hint, $help,$template_id);
    /*
     * Must add a namespace div
     */

    $f = $this->plugin()->getAddon('Simpli_Forms')->getModule('Form');

    $f->formStart(array(
        'name' => 'simpli_forms_ajaxoptions',
        'theme' => 'Admin',
        'action' => 'options_save',
        'method' => 'post',
        'template' => 'formStart',
        //   <form id="simpli_forms_ajaxoptions_1" action="options_save" method="post">
        'filter' => 'Options')
    );
    //        <input type="hidden" id="_wpnonce" name="_wpnonce" value="0b80148dfc" /><input type="hidden" name="_wp_http_referer" value="/wp-admin/post.php?post=68&amp;action=edit&amp;message=1" />    <input type="hidden" name="action"  value="" />
    ?>






</form>





</div>