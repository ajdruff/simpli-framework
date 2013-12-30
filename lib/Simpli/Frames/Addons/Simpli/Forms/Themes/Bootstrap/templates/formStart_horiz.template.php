<form id="{form_name}_{form_counter}" style="{STYLE}" class="form-horizontal {CLASS}" action="{action}" method="{method}" role="form">
    <div class="container">
     <input type="hidden" value="{form_name}" name="form_name"><?php ?>
    
    <?php
    //Notes:
       // Need to add form_name so we can add it to the database . This should likely be added by javascript so the user doesnt need to think about adding it to the template.
    // wp_nonce_field($this->plugin()->getSlug()); //this causes a misdirect.
    //  wp_nonce_field('save_post_options', $this->plugin()->getSlug() . '_nonce');
    // wp_nonce_field('ajax_save_post_options', $this->plugin()->getSlug() . '_nonce');
    // <input type="hidden" name="action"  value="" /> When update is used, this line causes a misdirect back to edit.php
    // <?php wp_nonce_field('ajax_save_post_options', $this->plugin()->getSlug() . '_nonce');
    ?>








