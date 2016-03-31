{knobs}
<div class="small-box bg-aqua {update_class}">
<span class="hidden widget_url">{widget_url}</span>
    <div class="inner tileknob">
        <div class="row">    
            <div class="col-md-10 col-md-offset-1 col-sm-4 col-sm-offset-4 text-center">
                <div class="col-md-8 col-md-offset-2 col-lg-12 col-lg-offset-0 col-sm-8 col-sm-offset-2 col-xs-8 col-xs-offset-2">
                <p style="margin-bottom: 0.2em !important;">{label}</p>
                  <hr style="margin: 0 0  0.2em 0">
                <div class="pull-left">
                    <div class="knob-label" style="margin-bottom: 0 !important;">{data-min}</div>
                </div>
                <div class="pull-right">
                    <div class="knob-label" style="margin-bottom: 0 !important;">{data-max}</div>
                </div><!--{knobs}-->
               </div><!--Cierra col-offset--> 
                {input}
            </div><!--Cierra col-text-center--> 
        </div><!--Cierra row-->
    </div><!--Cierra inner-->
</div><!--Cierra small-box-->
{/knobs}