<!-- post dropdown - for use where
The only difference is that with form-horizontal class applied to the form,
the form-group class acts the part of the 'row' class to enforce the sizes.
there is now no need to use the row divs, which would only screw up the layout.
-->









<div class="col-md-{size}">
     <div   data-sf-valid="{NAME}">Valid Messages</div>
            <select name='{name}' style="{style};" class=" form-control {class}" id="{ID}">

                {options_html}

            </select>

  </div>










