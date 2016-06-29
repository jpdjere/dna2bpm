<div class="box box-info">
    <div class="box-header">
        <h3 class="box-title">Resultados de: "{querystring}" <span>({count})</span></h3>
        <div class="box-tools pull-right">
            <button class="btn btn-default btn-sm" data-widget="collapse"><i class="fa fa-minus"></i></button>
            <button class="btn btn-default btn-sm" data-widget="remove"><i class="fa fa-times"></i></button>
        </div>
    </div>
    <div class="box-body">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th></th>
                    <th>Nro Proyecto</th>
                    <th>Fecha</th>
                    <th>Nombre Empresa</th>
                    <th>CUIT</th>
                    <th>Estado</th>
                    <th>Caso</th>
                </tr>
            </thead>
            <tbody>
                {empresas}
                <tr>
                    <td>
                        <a href="{link_open}" target="_blank" title="Ver Documentos del Proyecto">
                            <i class="ion ion-folder fa-2x fa-adjust"></i>
                        </a>

                    </td>
                    <td>{if {link_msg_cond}}<a href="{link_msg}" class="load_tiles_after"  title="Ver Notificaciones">
                            <i class="ion ion-email fa-2x fa-adjust"></i>
                        </a>   {/if}
                    </td>
                    <td>
                    {if {url_bpm_cond}}<a href="{url_bpm}" title="Procesar Tareas">
                            <i class="ion-arrow-right-b fa-2x fa-adjust"></i>
                        </a>{/if}
                    </td>
                    <td>
                    {if {url_clone_cond}}<a href="{url_clone}" title="Recibir Documentación">
                            <i class="ion ion-android-inbox fa-2x fa-adjust"></i>
                        </a>{/if}
                    </td>
                    <td>

                    </td>
                     <td>
                    
                        </td>
                    <td>{Nro}</td>
                    <td>{fechaent}</td>
                    <td>{nombre}</td>
                    <td>{cuit}</td>
                    <td>{estado}</td>
                    <td>{case}</td>
                </tr>
                {/empresas}

            </tbody>
        </table>
    </div>
</div>