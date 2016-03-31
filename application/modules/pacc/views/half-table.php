<div class="box {class}">
     <span class="hidden json_url">{json_url}</span>
            <div class="box-header">
                <h3 class="box-title">{title}</h3>
            </div><!-- /.box-header -->
            <div class="box-body no-padding">
                <table class="table table-condensed">
                    <tbody>
                    <tr>
                        <th style="width: 10px"></th>
                        <th>Apellido y Nombre</th>
                        <th>Meta Mensual</th>
                        <th style="width: 40px">%</th>
                    </tr>
                    {users}
                    <tr>
                        <td>
                            <div class="pull-left image">
                                <img alt="User Image" class="img-circle foto-usuario" src="{avatar}">
                            </div>
                        </td>
                        <td class="pad-nombre">{name}</td>
                        <td class="pad-progress">
                            <div class="progress xs">
                                <div style="width: {value}%" class="progress-bar progress-bar-{class}"></div>
                            </div>
                        </td>
                        <td class="pad-nro"><span class="badge bg-{color}">{value}%</span></td>
                    </tr>
                    {/users}
                    <!-- fake tr for async json -->
                    <tr id="tr_ranking" class="hidden">
                        <td>
                            <div class="pull-left image">
                                <img alt="User Image" class="img-circle foto-usuario" src="{avatar}">
                            </div>
                        </td>
                        <td class="pad-nombre">{name}</td>
                        <td class="pad-progress">
                            <div class="progress xs">
                                <div style="width: {value}%" class="progress-bar progress-bar-{update_class}"></div>
                            </div>
                        </td>
                        <td class="pad-nro"><span class="badge bg-{color}">{value}%</span></td>
                    </tr>    
                </tbody>
            </table>
        </div><!-- /.box-body -->
</div>