<?php

$post_options=$this->getPlugin()->getModule('Post')->getPostOptions();

wp_nonce_field($this->getPlugin()->getSlug(), $this->getPlugin()->getSlug());
?>

<div class="misc-pub-section">
        <p><label for="simpli-hello-postenabled"> <?php _e('Enabled',$this->getPlugin()->getTextDomain()); ?>

        <input type="checkbox" id="simpli-hello-postenabled" name="simpli-hello-postenabled" value="true"  <?php echo (($post_options['simpli-hello-postenabled'] == 'true') ?  ' checked="checked"' : ''); ?>>


    </label></p>

    <div><label for="simpli-hello-posttext"> <?php _e('Text to Add',$this->getPlugin()->getTextDomain()); ?>  </label></div>

        <textarea id="simpli-hello-posttext" name="simpli-hello-posttext" /><?php echo $post_options['simpli-hello-posttext'] ?></textarea>



<p><?php _e('Where would you like the text?',$this->getPlugin()->getTextDomain()); ?></p>
                <fieldset>
                    <label for="simpli_placement" class="label-radio">
                        <label class="label-radio">
                        <input type="radio" name="simpli_placement" value="before"  <?php echo (( $post_options['simpli_placement'] == 'before') ?  ' checked="checked"' : ''); ?> /> <span><?php _e('Before Content',$this->getPlugin()->getTextDomain()); ?></span></label>


                         <label class="label-radio">
                        <input type="radio" name="simpli_placement" value="after"<?php echo (( $post_options['simpli_placement'] == 'after') ?  ' checked="checked"' : ''); ?> /> <span><?php _e('After Content',$this->getPlugin()->getTextDomain()); ?></span>
                    </label>

                    <p class="description">
 <?php
                            printf(
                                    __(
                                            '', $this->getPlugin()->getSlug()
                                    ), ''
                            );
                            ?></p>
                              </label>
                </fieldset>

</div>

<script type="text/javascript">
    jQuery(document).ready(function($) {
        var form = $('#post');
        $(form).find('.hidden-temp').remove();
        $(form).find('.hidden-checkbox').removeClass('hidden-checkbox');
        $('#publish,#save-post').click(function() {
            //alert( $('#simpli-hello').find('input:checkbox:not(:checked)').attr('name'));

            $('#simpli-hello').find('input:checkbox:not(:checked)').addClass('hidden-checkbox');
            $('.hidden-checkbox').prepend('<input class="hidden-temp" type="hidden" name="' + $('.hidden-checkbox').attr('name') + '">');
            //$('.hidden-checkbox').get(0).type = 'hidden'; // bug in jquery prevents you from using attr http://stackoverflow.com/a/7634737
        });


    });
</script>