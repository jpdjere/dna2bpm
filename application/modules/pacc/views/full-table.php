<div class="box">
    <div class="box-header">
        <span class="hidden json_url">{json_url}</span>
        <h3 class="box-title">{title}</h3>
        <div class="box-tools">
            <div class="input-group">
                <input type="text" placeholder="Búsqueda.." style="width: 150px;" class="form-control input-sm pull-right" name="table_search">
                <div class="input-group-btn">
                    <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                </div>
            </div>
        </div>
    </div><!-- /.box-header -->
    <div class="box-body table-responsive no-padding {class-evaluador} {class-coordinador}">
        <table class="table table-hover">
            <tbody><tr>
                <th>IP</th>
                <th>Usuario</th>
                <th>Días</th>
                <th>Estado del proyecto</th>
                <th>Cuit</th>
                <th>Empresa</th>
                <th>Comentarios</th>
            </tr>
            {content}
            <tr>
                <td>{id}</td>
                <td>{name}</td>
                <td>{delay}</td>
                <td><span class="label label-{class}">{status}</span></td>
                <td>{cuit}</td>
                <td>{empresa}</td>
                <td>{comments}</td>
            </tr>
            {/content}
        </tbody></table>
    </div><!-- /.box-body -->
</div>
                            
                            
