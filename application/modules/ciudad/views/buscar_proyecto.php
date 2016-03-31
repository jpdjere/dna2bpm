<div class = "small-box bg-teal">
    <div class = "inner">
        <h4>Buscador</h4>
        <form class="form-extra" accept-charset="utf-8" method="post" action="{base_url}ciudad/buscar/pp">

            <div class="col-lg-9 input-group input-group-sm">
                <span class="input-group-addon">Nro</span>
                <input type="text"  class="form-control" name="query" placeholder="ej: 003/1014 ó ciudad" />
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-info btn-flat btn-search">Buscar</button>
                </span>
            </div>
        </form>
        <div>
            <ul class="list-unstyled">
                <li>
                    <a href="{base_url}ciudad/listar_pp" class="load_tiles_after">Preinscripciones</a>
                    (<a href="{base_url}ciudad/listar_pp/xls"><icon class="fa fa-download"></icon> xls</a>)
                </li>
                <li>
                    <a href="{base_url}ciudad/listar_pde" class="load_tiles_after">Proyectos</a>
                    <span class="pull-right>">
                        (<a href="{base_url}ciudad/listar_pde/xls"><icon class="fa fa-download"></icon> xls</a>)
                    </span>
                </li>
            </ul>
        </div>
    </div>
    <div class = "icon">
        <i class = "ion ion-search">
        </i>
    </div>
</div>