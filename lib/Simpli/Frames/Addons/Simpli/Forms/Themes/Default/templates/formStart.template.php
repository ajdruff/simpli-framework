<form id="{form_name}_{form_counter}" class="{CLASS}" action="{action}" method="{method}">
    
   
    <?php
    // wp_nonce_field($this->plugin()->getSlug()); //this causes a misdirect.
    //  wp_nonce_field('save_post_options', $this->plugin()->getSlug() . '_nonce');
    // wp_nonce_field('ajax_save_post_options', $this->plugin()->getSlug() . '_nonce');
    // <input type="hidden" name="action"  value="" /> When update is used, this line causes a misdirect back to edit.php
    // <?php wp_nonce_field('ajax_save_post_options', $this->plugin()->getSlug() . '_nonce');
    ?>








