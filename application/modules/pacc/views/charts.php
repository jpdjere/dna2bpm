<div class="box box-primary {class}">
    <span class="hidden json_url">{json_url}</span>
<div class="box-header" style="cursor: move;">
  <div style="padding-left: 0px" class="col-lg-6 col-md-7 col-sm-8 col-xs-12">
   <h3 class="pull-left box-title"><i class="fa fa-bar-chart-o"></i> {title}</h3>
    </div>
  <div class="col-lg-6 col-md-5 col-sm-4 col-xs-12">
        <div style="margin-top: 12px;" class="box-tools">
            <p style="font-size: 12px; padding-right: 5px; border-right: 1px solid #f4f4f4 " class="pull-left"><i class="fa fa-circle-o referencia"></i> SDE Ingresada</p>
            <p style="font-size: 12px; padding-left: 5px;" class="pull-left"><i class="fa fa-circle-o referencia2"></i> SDE Evaluada</p>
        </div>
    </div>
    </div>
    <div class="box-body {class-tour}">
        <div style="height: 300px; padding: 0px; position: relative;" id="line-chart">
            <canvas class="flot-base" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 523px; height: 300px;" width="523" height="300"></canvas>
            <div class="flot-text" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; font-size: smaller; color: rgb(84, 84, 84);">
                <div class="flot-x-axis flot-x1-axis xAxis x1Axis" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; display: block;">
                    <div style="position: absolute; max-width: 65px; top: 283px; left: 21px; text-align: center;" class="flot-tick-label tickLabel">0</div>
                    <div style="position: absolute; max-width: 65x; top: 283px; left: 93px; text-align: center;" class="flot-tick-label tickLabel">2</div>
                    <div style="position: absolute; max-width: 65px; top: 283px; left: 166px; text-align: center;" class="flot-tick-label tickLabel">4</div>
                    <div style="position: absolute; max-width: 65px; top: 283px; left: 239px; text-align: center;" class="flot-tick-label tickLabel">6</div>
                    <div style="position: absolute; max-width: 65px; top: 283px; left: 312px; text-align: center;" class="flot-tick-label tickLabel">8</div>
                    <div style="position: absolute; max-width: 65px; top: 283px; left: 382px; text-align: center;" class="flot-tick-label tickLabel">10</div>
                    <div style="position: absolute; max-width: 65px; top: 283px; left: 455px; text-align: center;" class="flot-tick-label tickLabel">12</div>
                </div>
            <div class="flot-y-axis flot-y1-axis yAxis y1Axis" style="position: absolute; top: 0px; left: 0px; bottom: 0px; right: 0px; display: block;">
                <div style="position: absolute; top: 270px; left: 1px; text-align: right;" class="flot-tick-label tickLabel">-1.5</div>
                <div style="position: absolute; top: 225px; left: 1px; text-align: right;" class="flot-tick-label tickLabel">-1.0</div>
                <div style="position: absolute; top: 180px; left: 1px; text-align: right;" class="flot-tick-label tickLabel">-0.5</div>
                <div style="position: absolute; top: 135px; left: 4px; text-align: right;" class="flot-tick-label tickLabel">0.0</div>
                <div style="position: absolute; top: 90px; left: 4px; text-align: right;" class="flot-tick-label tickLabel">0.5</div>
                <div style="position: absolute; top: 45px; left: 4px; text-align: right;" class="flot-tick-label tickLabel">1.0</div>
                <div style="position: absolute; top: 0px; left: 4px; text-align: right;" class="flot-tick-label tickLabel">1.5</div>
            </div>
            </div>
                <canvas class="flot-overlay" style="direction: ltr; position: absolute; left: 0px; top: 0px; width: 523px; height: 300px;" width="523" height="300"></canvas>
                </div>
    </div><!-- /.box-body-->
</div>