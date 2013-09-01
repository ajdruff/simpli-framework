<form id="{name}_{form_counter}" action="{action}" method="{method}">
<?php wp_nonce_field($this->getPlugin()->getSlug()); ?>
    <input type="hidden" name="action"  value="" />








