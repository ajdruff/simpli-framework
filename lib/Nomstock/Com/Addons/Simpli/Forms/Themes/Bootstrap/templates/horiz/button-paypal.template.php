<?php 
    /* Button, Horizontal
     * A label is not used here to allow for finer control of where the button can be located. The user can add a row around the button and additional columns near the button to push it to align with the other controls, if needed

*/
?>



    <div class="col-{ds}-{size}">
        <img alt="<?php _e('Waiting...', $this->plugin()->getTextDomain()); ?>" src="{SPINNER}"  class="waiting submit-waiting" />
        <!--button type="button" id="{form_name}_{form_counter}_{ACTION}"  style="{STYLE}" class="btn {CLASS}">{VALUE}</button -->
            
        
        <input type="image" id="{form_name}_{form_counter}_{ACTION}" style="{STYLE}" class="btn {CLASS}" src="https://www.paypalobjects.com/en_US/i/btn/btn_buynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!">
<img alt="" border="0" src="https://www.paypalobjects.com/en_US/i/scr/pixel.gif" width="1" height="1">


        
        
    </div>

