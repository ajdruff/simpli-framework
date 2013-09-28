<?php

/**
 * Phantom Class
 *
 * Allows you to silently ignore method calls to modules that arent loaded.
 * Usage is cautioned since it can easily mask major bugs if not used properly.
 * Usage is primarily intended for the debug module where errors within the debug
 * module might cause errors throughout the plugin, forcing the user to disable
 * or remove the Debug module. Once the Debug module was disabled, the code
 * would continue to spew errors where any reference to the module
 * was made. Since trace, t(),  calls are typically made throughout the plugin,
 * this would result in multiple errors.
 * Hence, this hack, which pretends its handling any method by a disabled module
 * but instead ignores the call.
 *
 * You can see an example of its usage in the definition of the debug()
 * method within the base
 * Module class.
 *
 *
 *
 * @author Andrew Druffner
 * @package SimpliFramework
 * @subpackage SimpliBase
 */
class Simpli_Basev1c0_Phantom {

    public function __call($method, $args) {
        /*
          tempting to place a log)() call here, but if there were bugs in
          the log module, you\'d be defeating the purpose of a silent fail'
          echo '<br>#################################';
          echo '<br>You are executing ' . __METHOD__;
          echo '<br> The method requested is ' . $method . ' with args ' . print_r($args);
          echo '<br>#################################';
         *
         */
        return;
    }

}

?>
