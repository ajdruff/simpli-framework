<!-- original
<div class="form-group">
    <div class="col-{ds}-{label_size}">
        <label class="control-label" for="{ID}">{LABEL}</label>
    </div>

    <div class="radio col-{ds}-5" id="{ID}">
        <label>
            <input type="radio" name="{NAME}" id="optionsRadios2" value="{VALUE}">
            {OPTION_TEXT}
        </label>
    </div>
</div>

-->

<!-- vertical form with sizing 
Notes:
No form-control class
No Control or Label Sizing Since it doesnt make sense since label and control occupies a separate row, and the radio button is small enough where on

to make a radio like a button:
class="btn btn-default btn-large" 
style='display:block;'


-->

<div class="form-group">
    <div class="row">
        <div class="col-{ds}-{label_size}">
            <label>{LABEL}   </label>
        </div>
    </div>
    <div class="row">
        <div  class="col-{ds}-{size}">
            {options_html}
        </div>


    </div>
</div>


