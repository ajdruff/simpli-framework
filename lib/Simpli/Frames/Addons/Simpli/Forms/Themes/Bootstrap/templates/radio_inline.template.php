<!-- Radio Inline Template -->
<!-- 
Inline Radio
Radio buttons will span across page, not stack
If you want them stacked, you need to set size to something small enough

-->
<div class="col-{ds}-{size}">


        <label class="control-label" for="{ID}">{LABEL}</label>


        <div  id="{ID}" >
            {OPTIONS_HTML}
        </div>

</div>

<?php
/*
  works
  <div class="form-group col-{ds}-{size}">


  <label class="control-label" for="{ID}">{LABEL}</label>


  <div  id="{ID}" >
  {OPTIONS_HTML}
  </div>

  </div>
 */
?>






