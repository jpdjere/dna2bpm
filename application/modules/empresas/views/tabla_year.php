<div class="box box-primary {class}">
    
    <div class="box-header" style="cursor: move;">
        <div style="padding-left: 0px" class="col-lg-6 col-md-7 col-sm-8 col-xs-12">
            <h3 class="pull-left box-title"><i class="fa fa-bar-chart-o"></i> {title}</h3>
        </div>
       
        <div class="box-body {class-tour}">
            <table class="table">
                <thead>
                    <tr>
                        <th>Año</th>
                        <th>Cantidad</th>
                    </tr>
                </thead>
                <tbody>
                    {data}
                    <tr>
                        <td>{date}</td>
                        <td>{qtty}</td>
                    </tr>
                    {/data}
                </tbody>
                <tfoot>
                    <tr>
                      <td>Total:</td>
                      <td>{total}</td>
                    </tr>
              </tfoot>
            </table>
            <hr>
            <a href="{base_url}{xls_url}"><icon class="fa fa-download"></icon> xls</a>
        </div>
    </div>
        
    <!-- /.box-body-->
</div>