




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
            <label for="<?php echo $this->getUserSettingName('enabled') ?>" class="label-radio">
                <label class="label-radio">
                    <input type="radio" name="<?php echo $this->getUserSettingName('enabled') ?>" value="enabled"  <?php echo (( $this->getUserSetting('enabled') === 'enabled') ? ' checked="checked"' : ''); ?> /> <span><?php _e('Enable it', $this->getPlugin()->getTextDomain()); ?></span></label>
                <label class="label-radio">
                    <input type="radio" name="<?php echo $this->getUserSettingName('enabled') ?>" value="disabled"<?php echo (( $this->getUserSetting('enabled') === 'disabled') ? ' checked="checked"' : ''); ?> /> <span><?php _e('Disable it', $this->getPlugin()->getTextDomain()); ?></span>
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


    <div><label for="<?php echo $this->getUserSettingName('text') ?>"> <?php _e('Text to Add', $this->getPlugin()->getTextDomain()); ?>  </label></div>

    <textarea id="<?php echo $this->getUserSettingName('text') ?>" name="<?php echo $this->getUserSettingName('text') ?>" /><?php echo $this->getUserSetting('text') ?></textarea>




<fieldset>
    <?php _e('Placement of Text', $this->getPlugin()->getTextDomain()); ?>
    <p>
        <label for="<?php echo $this->getUserSettingName('placement') ?>" class="label-radio">
            <label class="label-radio">
                <input type="radio" name="<?php echo $this->getUserSettingName('placement') ?>" value="before"  <?php echo (( $this->getUserSetting('placement') === 'before') ? ' checked="checked"' : ''); ?> /> <span><?php _e('Before Content', $this->getPlugin()->getTextDomain()); ?></span></label>
    </p>
    <p>
        <label class="label-radio">
            <input type="radio" name="<?php echo $this->getUserSettingName('placement') ?>" value="after"<?php echo (( $this->getUserSetting('placement') === 'after') ? ' checked="checked"' : ''); ?> /> <span><?php _e('After Content', $this->getPlugin()->getTextDomain()); ?></span>
        </label>
    </p><p>
        <label class="label-radio">
            <input type="radio" name="<?php echo $this->getUserSettingName('placement') ?>" value="default"<?php echo (( $this->getUserSetting('placement') === 'default') ? ' checked="checked"' : ''); ?> /> <span><?php _e('Default as set in Plugin Settings', $this->getPlugin()->getTextDomain()); ?></span>
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
