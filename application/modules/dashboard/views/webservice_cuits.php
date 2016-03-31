<div class = "small-box bg-teal">
    <div class = "inner">
        <h4>{name}</h4>
        <form class="form-ws" accept-charset="utf-8" method="post" action="../webservice/responder">

            <div class="col-lg-9 input-group input-group-sm">
                <span class="input-group-addon">#CUIT</span>
                <input type="text"  class="form-control" name="cuit" placeholder="ej: 30-70366211-7" />
                <span class="input-group-btn">
                    <button type="submit" class="btn btn-info btn-flat btn-search">Buscar</button>
                </span>
            </div>
        </form>
        <div>

        </div>
    </div>
    <div class = "icon">
        <i class = "ion ion-search">
        </i>
    </div>
</div>
<div>
    <ul class="list-unstyled">
        <li>
            <div id="webservice"></div>
        </li>

    </ul>

</div>