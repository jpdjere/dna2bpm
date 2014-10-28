<div class="box box-info">
    <div class="box-header">
        <h3 class="box-title">{name}</h3>
        <div class="box-tools pull-right">
            <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-default btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>


    <div class="box-body">
        {mini}        
        <table class="table table-striped">
            <thead>
                <tr><td colspan="6" align="right">{evaluator}</td></tr>
                <tr>
                    <th>Nro Proyecto</th>
                    <th>Fecha</th>
                    <th>Nombre</th>
                    <th>CUIT</th>
                    <th>Estado</th>
                   <!-- <th>Caso</th>-->
                </tr>
            </thead>
            <tbody>
                
                {project}            
            </tbody>
        </table>        
        {/mini}
    </div>
    <!-- /.box-body -->
    <div class="box-footer">
        {footer}
    </div><!-- /.box-footer-->
</div>



