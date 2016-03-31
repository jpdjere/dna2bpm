<div class="box">
    <div class="box-header">
        <h3 class="box-title">Maqueta de tabla de carga</h3>
    </div><!-- /.box-header -->
    <div class="box-body table-responsive">
        <div role="grid" class="dataTables_wrapper form-inline" id="example1_wrapper">
            <div class="row">
                <div class="col-xs-6">
                  
                   <button id="modalbutton" class="pull-left btn btn-default" data-toggle="modal" data-target="#myModal">Nuevo grupo </button>
                </div>
                <!-- Modal -->
                <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
                  <div class="modal-dialog">
                    <div class="modal-content">
                      <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">×</span><span class="sr-only">Close</span></button>
                        <h4 class="modal-title" id="myModalLabel"><i class="fa fa-plus-circle"></i> Nuevo grupo</h4>
                      </div>
                      <div class="modal-body">
                        <h3><i class="fa fa-caret-right"></i> Nombre</h3>
                            <p>Nombre el nuevo grupo que desea crear.</p>
                   
                    <div class="input-group">
                          <span class="input-group-addon"><i class="fa fa-pencil-square-o"></i></span>
                          <input id="grupo" type="text" class="form-control" placeholder="Nombre...">
                    </div>
                        <br />
                        <button id="button" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-check"></i> Crear</button> 
                        <button id="button_h2" class="btn btn-primary" data-dismiss="modal"><i class="fa fa-check"></i> Crear H2</button> 
                      </div>
                      
                    </div><!-- Cierra Modal content -->
                  </div><!-- Cierra Modal dialog -->
                </div><!-- Cierra Modal -->
                <div class="col-xs-6">
                    <div class="input-group">
                        <input type="text" placeholder="Buscar" style="width: 150px;" class="form-control input-sm pull-right" name="table_search">
                            <div class="input-group-btn">
                                <button class="btn btn-sm btn-default"><i class="fa fa-search"></i></button>
                            </div>
                    </div>
                </div>
            </div>
                {content}
        </div>
    </div>
    <!--<div class="box-footer">-->
    <!--    <div class="row">-->
    <!--        <div class="col-xs-6">-->
    <!--            <div class="dataTables_info" id="example1_info">Showing 1 to 10 of 57 entries</div>-->
    <!--        </div>-->
    <!--        <div class="col-xs-6">-->
    <!--            <div class="dataTables_paginate paging_bootstrap">-->
    <!--                <ul class="pagination"><li class="prev disabled"><a href="#">← Previous</a></li><li class="active"><a href="#">1</a></li><li><a href="#">2</a></li><li><a href="#">3</a></li><li><a href="#">4</a></li><li><a href="#">5</a></li><li class="next"><a href="#">Next → </a></li></ul>-->
    <!--            </div>-->
    <!--        </div>-->
    <!--    </div>-->
    <!--</div>    -->
    <!-- /.box-body -->
</div>