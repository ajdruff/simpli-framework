<!-- 
CLASS will default to radio if not provided
To make the radio button appear as a regular button stacked, use:
class="btn btn-default btn-large" 
style='display:block;'
-->  

<div class="col-{ds}-{size}">
    <div>
    <input class="{CLASS}" style="{STYLE}" type="radio" name="{NAME}"  value="{OPTION_VALUE}"  {CHECKED}>
    {OPTION_TEXT}</div>

</div>    


<?php 
/*
 * works

     <div class="radio">
      
                <label>
                    <input type="radio" name="{NAME}"  value="{OPTION_VALUE}"  {CHECKED}>
                    {OPTION_TEXT}
                </label>


 </div>
 * 
 * works too
 * <div class="{CLASS} " style="{STYLE}">
            <label>
                <input type="radio" name="{NAME}"  value="{OPTION_VALUE}"  {CHECKED}>
                {OPTION_TEXT}
            </label>
        </div>

        */             
                    
?>





