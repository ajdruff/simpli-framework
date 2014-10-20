<!-- horizontal form with sizing 
The only difference is that with form-horizontal class applied to the form,
the form-group class acts the part of the 'row' class to enforce the sizes.
there is now no need to use the row divs, which would only screw up the layout.
-->

<div class="form-group">
    <div class="row">
        <div class="col-{ds}-{label_size}">
            <label class="pull-left" for="{ID}" >{LABEL}   </label>
        </div>
        <div class="col-{ds}-{size}">
            <input type="text" style="{STYLE}" name="{name}" class="form-control {CLASS}" id="{ID}" placeholder="{PLACEHOLDER}" value="{VALUE}" {DISABLED} {READONLY}>
        </div>
    </div>
</div>


<!-- inline form with sizing
No substantial structure change - you can use the horizontal structure
alternative from above:
<div class="form-group">

    <div class="col-{ds}-{label_size} control-label">
        <label for="{ID}" >{LABEL}   </label>
    </div>


    <div class="col-{ds}-{size}">
        <input type="text" style="{STYLE}" name="{name}" class="form-control {CLASS}" id="{ID}" placeholder="{PLACEHOLDER}" value="{VALUE}">
    </div>


</div>
-->






