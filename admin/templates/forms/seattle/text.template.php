<?php

/*
 * Use named argument swapping by defining variables for swapped arguments.
 * Match the variable names and values with the sprintf in the corresponding method in the Form class.
 */

$name = '%1$s';
$value = '%2$s';
$label = '%3$s';
$hint = '%4$s';
$help = '%5$s';
?>
<fieldset>
    <label class="label-radio">
        <span><?php $e($label) ?></span>
        <input type="text" name="<?php $e ($name) ?>" value="<?php $e ($value) ?>">
    </label>


</fieldset>
<p class="description">
    <?php $e ($hint) ?>
</p>