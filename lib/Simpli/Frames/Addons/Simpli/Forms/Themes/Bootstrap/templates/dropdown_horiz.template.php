<!-- horizontal form with sizing 
The only difference is that with form-horizontal class applied to the form,
the form-group class acts the part of the 'row' class to enforce the sizes.
there is now no need to use the row divs, which would only screw up the layout.
-->



<div class="form-group">
    <div class="row">
        <div class="col-{ds}-{label_size}">
            <label for="{ID}" >{LABEL}   </label>
        </div>
        <div class="col-{ds}-{size}">



            <select name='{name}' style="{style}" class="form-control {class}" id="{ID}">

                {options_html}

            </select>

        </div>
    </div>


</div>


<!-- alternative,works
<div class="form-group">

    <div class="col-{ds}-{label_size} control-label">
        <label for="{ID}" >{LABEL}   </label>
    </div>


    <div class="col-{ds}-{size}">
               <select name='{name}' style="{style}" class="form-control {class}" id="{ID}">

            {options_html}

        </select>
    </div>


</div>
-->






