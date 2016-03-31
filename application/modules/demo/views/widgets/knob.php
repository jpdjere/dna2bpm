    <!-- Title + min/ max -->
    <div class="col-md-8 col-md-offset-2 col-lg-12 col-lg-offset-0 col-sm-8 col-sm-offset-2 col-xs-8 col-xs-offset-2">
        <span class="hidden widget_url">{widget_url}</span>
        <p style="margin-bottom: 0.2em !important;">{title}</p>
        <hr style="margin: 0 0  0.2em 0">
        <div class="pull-left">
            <div class="knob-label" style="margin-bottom: 0 !important;">{min}</div>
        </div>
        <div class="pull-right">
            <div class="knob-label" style="margin-bottom: 0 !important;">{max}</div>
        </div>
    </div>
    <!--offset-->
    <input 
    class="{class}" 
    title="{title}" 
    data-min="{min}" 
    data-max="{max}" 
    data-label="{label}"
    value="{value}"
    data-url="{json_url}" 
    data-fgColor="{fgColor}" 
    readonly="readonly" 
    type="text" 
    data-width="120" 
    data-height="120" 
    data-angleoffset="270" 
    data-anglearc="180" 
    data-readonly="1" 
    data-thickness="0.3" 
    col-md="4" col-sm="6" col-xs="6"
    />