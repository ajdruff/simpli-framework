<?php 
    /*
    *Button, inline form with sizing 
    a label tag is needed to provide the same layout as the other controls that have labels, ensuring the button aligns correctly with the them
*/
?>

    <div class="col-{ds}-{size}">

            <label   >&nbsp; </label>



        <img alt="<?php _e('Waiting...', $this->plugin()->getTextDomain()); ?>" src="{SPINNER}" class="waiting submit-waiting" />
                <button type="button" style="{style};display:block;" id="{form_name}_{form_counter}_{ACTION}" class="btn {CLASS}">{VALUE}</button>
 
    </div>

