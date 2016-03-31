        <div id="replace">
        {areas}
            <div class="panel panel-default">
                <div class="panel-heading c-list">
                    <span class="title">{nombre_area}</span>
                    <ul class="pull-right c-controls">
                        <li><a href="#modal-resumen" data-toggle="tooltip" data-placement="top" title="Nuevo componente"><i class="glyphicon glyphicon-plus"></i></a></li>
                        <li><a href="#modal-editar" data-command="toggle-search" data-toggle="tooltip" data-placement="top" title="Editar componente"><i class="fa fa-edit"></i></a></li>
                        <li><a href="#modal-eliminar" data-command="toggle-search" data-toggle="tooltip" data-placement="top" title="Eliminar componente"><i class="fa fa-trash-o"></i></a></li>
                    </ul>
                </div>
                 <div class="panel-body">
                    <div class="col-md-10 col-sm-5 col-xs-12">
                        <h5 class="bold-componente">Códigos</h5>
                        <ul id="{id}">
                            {componentes}
                            <li><a href="#">{comp} / {descripcion_comp}</a>
                                <ul>{subcomponentes}
                                    <li>{scomp} / {descripcion_scomp}</li>
                                    {/subcomponentes}
                                </ul>
                            </li>
                            {/componentes}
                        </ul>
                    </div><!-- Termina codigos -->
                    <div class="col-md-2 col-sm-1 col-xs-12">
                        <h5 style="font-weight: bold; text-align: center;">Activo</h5>
                        <div class="radio col-md-offset-5 col-sm-offset-5">
                            <input type="radio" name="optionsRadios" id="optionsRadios1" value="option1">
                        </div>
                    </div>
                </div>
            </div>
        
        <div id="modal-eliminar" class="modal fade bs-example-modal-sm" tabindex="0" role="dialog" aria-labelledby="mySmallModalLabel2" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="mySmallModalLabel2">Eliminar Componente</h4>
                </div>
                <div class="modal-body">
                  <div class="form-group">
                        <label for="Input3">Seleccione que tipo de elemento desea eliminar</label>
                        <select class="form-control" id="elemento-eliminar">
                         <option value="1">Componente</option>
                         <option value="2">Subcomponente</option>
                         </select>
                      </div>
                      <div class="form-group" id="subcomponente-eliminar" hidden >
                        <label for="Input3">Seleccione el subcomponente a eliminar de la lista</label>
                        <select class="form-control" id="scompx">
                         {componentes subcomponentes}
                         <option value="{scomp}">{scomp} {descripcion}</option>
                         {/componentes subcomponentes}
                         </select>
                      </div>
                      
                      <div class="form-group" id="componente-eliminar">
                        <label for="Input3">Seleccione el componente a eliminar de la lista</label>
                        <select class="form-control" id="compx">
                         {componentes}
                         <option value="{comp}">{comp} {descripcion}</option>
                         {/componentes}
                         </select>
                      </div>
                      
                      <div class="row">
                        <div class="col-xs-12">
                      <div class="checkbox pull-left">
                        <label class="pad-left-modal">
                        
                        </label>
                      </div>
                      <div class="pull-right">
                      <button id="boton-eliminar" class="btn btn-success">Aceptar</button>
                      <button type="reset" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                  </div>
                    </div>
                </div>
                  
                </div>
            </div>
        </div>
    </div>
        
    <div id="modal-resumen" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="mySmallModalLabel">Nuevo Componente</h4>
                </div>
                <div class="modal-body">
                  <div class="form-group">
                        <label for="Input3">Seleccione que tipo de elemento desea agregar</label>
                        <select class="form-control" id="elemento">
                         <option value="1">Componente</option>
                         <option value="2">Subcomponente</option>
                         </select>
                      </div>
                      <div class="form-group" id="area-hidden">
                        <label for="Input3">Seleccione el área en que lo desea agregar</label>
                        <select class="form-control" id="area">
                         <option value="1">Área Planificación</option>
                          <option value="2">Apoyo a Nuevas Empresas</option>
                         </select>
                      </div>
                      <div id="componente" class="form-group" hidden>
                        <label for="Input1">Ingrese el Componente padre</label>
                        <input value="-" id="comp" type="text" class="form-control" hidden="hidden" required>
                      </div>
                   <div class="form-group">
                        <label for="Input1">Código</label>
                        <input id="codigo" type="text" class="form-control" required>
                      </div>
                      <div class="form-group">
                        <label for="Input2">Descripción</label>
                        <input id="descripcion" type="text" class="form-control"required>
                      </div>
                      
                      <div class="row">
                        <div class="col-xs-12">
                      <div class="checkbox pull-left">
                        <label class="pad-left-modal">
                        
                        </label>
                      </div>
                      <div class="pull-right">
                      <button id="boton-guardar" class="btn btn-success">Aceptar</button>
                      <button type="reset" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                  </div>
                    </div>
                </div>
                  
                </div>
            </div>
        </div>
    </div>
    
    <div id="modal-editar" class="modal fade bs-example-modal-sm" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel2" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                    <h4 class="modal-title" id="mySmallModalLabel2">Nuevo Componente</h4>
                </div>
                <div class="modal-body">
                  <div class="form-group">
                        <label for="Input3">Seleccione que tipo de elemento desea editar</label>
                        <select class="form-control" id="elemento-editar">
                         <option value="1">Componente</option>
                         <option value="2">Subcomponente</option>
                         </select>
                      </div>
                      <div class="form-group" id="componente-editar">
                        <label for="Input3">Seleccione componente que desea editar</label>
                        <select class="form-control" id="compy">
                         {componentes_all}    
                         <option value="{comp}">{comp} {descripcion_comp}</option>
                         {/componentes_all}
                         </select>
                      </div>
                      <div class="form-group" id="subcomponente-editar" hidden>
                        <label for="Input3">Seleccione el subcomponente a eliminar de la lista</label>
                        <select class="form-control" id="scompx">
                         {subcomponentes_all}
                         <option value="{scomp}">{scomp} {descripcion_scomp}</option>
                         {/subcomponentes_all}
                         </select>
                      </div>
                     <div class="form-group">
                        <label for="Input1">Código</label>
                        <input id="codigo" type="text" class="form-control" required>
                      </div>
                      <div class="form-group">
                        <label for="Input2">Descripción</label>
                        <input id="descripcion" type="text" class="form-control"required>
                      </div>
                      
                      <div class="row">
                        <div class="col-xs-12">
                      <div class="checkbox pull-left">
                        <label class="pad-left-modal">
                        
                        </label>
                      </div>
                      <div class="pull-right">
                      <button id="boton-editar" class="btn btn-success">Aceptar</button>
                      <button type="reset" class="btn btn-danger" data-dismiss="modal">Cancelar</button>
                  </div>
                    </div>
                </div>
                  
                </div>
            </div>
        </div>
    </div>
    
    
    
    
    
    
    
    
    
    
    
    
    
    
    {/areas}
     </div>


     <!--<table class="table gtreetable" id="gtreetable"><thead><tr><th>Category</th></tr></thead></table>-->


