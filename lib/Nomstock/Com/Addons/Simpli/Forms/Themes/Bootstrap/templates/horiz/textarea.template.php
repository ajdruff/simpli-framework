<div class="form-group">
    <div class="row">
        <div class="col-{ds}-{label_size}">
            <label  for="{ID}" >{LABEL}   </label>
        </div>
        <div class="col-{ds}-{size}">
            <textarea name="{name}" style="{STYLE}" class="form-control {CLASS}" id="{ID}" placeholder="{PLACEHOLDER}" rows="{rows}" cols="{cols}">{VALUE}</textarea>
        </div>
    </div>
            <!-- Validation Errors -->
         <div class="row">
             <div class="col-{ds}-{size} col-{ds}-offset-{label_size}" >
        <div data-sf-valid="{name}"></div> 
        </div>
        </div>
        <!-- End Validation Errors -->
</div>

<!-- alternative 
<div class="form-group">

    <div class="col-{ds}-{label_size} control-label">
        <label for="{ID}" >{LABEL}   </label>
    </div>


    <div class="col-{ds}-{size}">
        
                    <textarea name="{name}" style="{STYLE}" class="form-control {CLASS}" id="{ID}" placeholder="{PLACEHOLDER}" rows="{rows}" cols="{cols}">{VALUE}</textarea> <div class="control-label-static">{HINT}</div>
    </div>
 </div>

-->





