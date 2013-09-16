<form id="{form_name}_{form_counter}" action="{action}" method="{method}">
<?php wp_nonce_field($this->plugin()->getSlug()); ?>
    <input type="hidden" name="action"  value="" />
  <div class="message-body"></div>







