




       <fieldset>
     <label class="label-radio">
    <span> <?php $this->fieldLabel('new') ?></span>
<input type="text" name="<?php $this->fieldName('new');?>" value="<?php $this->postOption('new');?>">
 </label>

        <span class="description">
            <?php
            printf(
                    __(
                            $this->fieldHelp('new'), $this->getPlugin()->getTextDomain()
                    ), ''
            );
            ?></span>
       </fieldset>
    <fieldset>
        <?php _e('Enable or Disable Hello Text for this post', $this->getPlugin()->getTextDomain()); ?>

        <p>
            <label for="<?php echo $this->getPostOptionName('enabled') ?>" class="label-radio">
                <label class="label-radio">
                    <input type="radio" name="<?php echo $this->getPostOptionName('enabled') ?>" value="enabled"  <?php echo (( $this->getPostOption('enabled') === 'enabled') ? ' checked="checked"' : ''); ?> /> <span><?php _e('Enable it', $this->getPlugin()->getTextDomain()); ?></span></label>
                <label class="label-radio">
                    <input type="radio" name="<?php echo $this->getPostOptionName('enabled') ?>" value="disabled"<?php echo (( $this->getPostOption('enabled') === 'disabled') ? ' checked="checked"' : ''); ?> /> <span><?php _e('Disable it', $this->getPlugin()->getTextDomain()); ?></span>
                </label>
        </p>
        <span class="description">
            <?php
            printf(
                    __(
                            '', $this->getPlugin()->getSlug()
                    ), ''
            );
            ?></span>
        </label>
    </fieldset>


    <div><label for="<?php echo $this->getPostOptionName('text') ?>"> <?php _e('Text to Add', $this->getPlugin()->getTextDomain()); ?>  </label></div>

    <textarea id="<?php echo $this->getPostOptionName('text') ?>" name="<?php echo $this->getPostOptionName('text') ?>" /><?php echo $this->getPostOption('text') ?></textarea>




<fieldset>
    <?php _e('Placement of Text', $this->getPlugin()->getTextDomain()); ?>
    <p>
        <label for="<?php echo $this->getPostOptionName('placement') ?>" class="label-radio">
            <label class="label-radio">
                <input type="radio" name="<?php echo $this->getPostOptionName('placement') ?>" value="before"  <?php echo (( $this->getPostOption('placement') === 'before') ? ' checked="checked"' : ''); ?> /> <span><?php _e('Before Content', $this->getPlugin()->getTextDomain()); ?></span></label>
    </p>
    <p>
        <label class="label-radio">
            <input type="radio" name="<?php echo $this->getPostOptionName('placement') ?>" value="after"<?php echo (( $this->getPostOption('placement') === 'after') ? ' checked="checked"' : ''); ?> /> <span><?php _e('After Content', $this->getPlugin()->getTextDomain()); ?></span>
        </label>
    </p><p>
        <label class="label-radio">
            <input type="radio" name="<?php echo $this->getPostOptionName('placement') ?>" value="default"<?php echo (( $this->getPostOption('placement') === 'default') ? ' checked="checked"' : ''); ?> /> <span><?php _e('Default as set in Plugin Settings', $this->getPlugin()->getTextDomain()); ?></span>
        </label>
    </p>
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
