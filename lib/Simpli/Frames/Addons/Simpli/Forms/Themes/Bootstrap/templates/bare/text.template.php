    <?php 
    /*
     * Bare:
     * -no label
     * -no column sizing
     * -no validation . if you need validation, then add the following tag to your form:
<span    data-sf-valid="{NAME}"></span>
     * form-control - needed in bootstrap to maintain styling
     * no form-group
     * for span or block control , use bootstrap row and col , dont control using display

     * 
     */

    ?>


<input name="{NAME}" style="{style}" placeholder="{PLACEHOLDER}" type="text" id="{ID}" class="form-control {CLASS}" value="{VALUE}" {DISABLED} />
