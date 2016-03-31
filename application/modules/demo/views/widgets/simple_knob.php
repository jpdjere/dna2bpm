<div class="box box-warning">
    <div class="box-header">
        <span class="hidden widget_url">{widget_url}</span>
        <i class="fa fa-2x fa-dashboard"></i>
        <h3 class="box-title">{title}</h3>
            <div class="box-tools pull-right">
                <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
                <button class="btn btn-default btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
            </div>
    </div><!-- /.box-header -->
    <div style="padding: 15px 10px 0px !important;" class="box-body">
        <div class="row">
            <div class="col-md-4 col-md-offset-4 col-sm-4 col-sm-offset-4 col-xs-8 col-xs-offset-2 text-center">
                <div class="col-md-8 col-md-offset-2 col-lg-12 col-lg-offset-0 col-sm-8 col-sm-offset-2 col-xs-12 col-xs-offset-0">
                     <p style="margin-bottom: 0.2em !important;">{title}</p>
                <hr style="margin: 0 0  0.2em 0">
                    <div class="pull-left">
                        <div class="knob-label" style="margin-bottom: 0 !important;">{data-min}</div>
                    </div>
                    <div class="pull-right">
                         <div class="knob-label" style="margin-bottom: 0 !important;">{data-max}</div>
                    </div>
                </div><!--offset-->
                    {content}                               		                                                                                  
            </div><!--text center-->
        </div><!-- /.row -->
    </div>
    <div class="box-footer">
        {footer}
    </div>
<!-- /.box-body -->
</div><!--box-->
