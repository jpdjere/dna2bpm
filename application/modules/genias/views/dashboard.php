<!-- / Contenido -->
<div class="container" > 


    <div class="row-fluid">

        <!-- xxxxxxxxxxxxxxxx CREAR META  xxxxxxxxxxxxxxxx -->


        <button type="button" class="btn " data-toggle="collapse" data-target="#meta_div">
            <i class="icon-plus"></i> Nueva meta
        </button>
        <br/>
        <br/>
        <div id="meta_div" class="collapse out no-transition">

            <form id="form_goals" method="post" class="well">
                <div  class="row-fluid">
                    <div class="span6">
                        <label>Proyecto</label>
                        <select name="proyecto" class="input-block-level">
                            {projects}
                            <option value="{id}">{name}</option>
                            {/projects}
                        </select>
                        <label>Genia</label>
                        <select name="genia" class="input-block-level">
                            {genias}
                            <option value="{nombre}">{nombre}</option>
                            {/genias}
                        </select>
                        <div class="">
                            <label>Cantidad</label>
                            <input type="number" name="cantidad" placeholder="Cantidad"   class="input-block-level"/>
                        </div>
                    </div>
                    <div class="span6">
                        <div class="">
                            <label>Período</label>

                            <!--<div class="input-append">
                            <input type="text" name="desde" placeholder="Período"   class="input-block-level "/>
                            </div>
                            -->
                            <div data-date-viewMode="months" data-date-minViewMode="months" data-date-format="mm-yyyy" data-date="" id="dp3" class="input-append date">
                                <input type="text" name="desde" readonly="" value=""  class="input-block-level">
                                <span class="add-on"><i class="icon-calendar"></i></span>
                            </div>
                        </div>

                        <label>Observaciones</label>
                        <textarea name="observaciones" placeholder="Observaciones"  class="input-block-level" ></textarea>

                    </div>
                </div>
                <div  class="row-fluid">
                    <div class="span12">

                        <button class="btn btn-block btn-primary " type="submit" id="bt_save"><i class="icon-save"></i>  Agregar</button>  
                    </div> 

                </div> 

            </form>  



        </div> 
    </div> 
    <br/>
    <br/>
    <!-- Resumen --> 
    {if {rol}=='coordinador'}
    <div class="alert {resumen_class}">
        <button type="button" class="close" data-dismiss="alert">&times;</button>
        <strong>Sus genias tienen {goal_cumplidas} de {goal_cantidad} objetivos cumplidos.</strong> 
    </div>
    {/if}
    <!-- xxxxxxxxxxxxxxxx METAS  xxxxxxxxxxxxxxxx -->
    <div class="row">

        {metas}

        <!-- <div class="span6 {status_class}">  -->
        <div class="span6">  
            <div class="well">
                <!-- Nombre Proyecto -->
                <div>  
                    <h3>{proyecto_nombre}<span class="pull-right">{cumplidas_count}/{cantidad}</span></h3>
                </div>

                <div> 
                    <ul class="unstyled">
                        <li>
                            <i class="icon-calendar" ></i> Período: {desde}
                        </li>
                        <li>
                            <i class="icon-eye-open" ></i> Estado: 
                            <button class="btn btn-mini {label_class}">
                                <i class="{status_icon_class}"></i>&nbsp;{status}   
                                <!-- Btn Aprobar -->
                            </button>
                                {if {rol}=='coordinador'}
                                {if {status_class} == 'well status_open'}

                             <button class="aprobar btn btn-mini btn-success" url="{url_case}" type="button">
                                    <i class="icon-thumbs-up-alt"></i> Aprobar
                            </button>
                                {/if}
                                {/if}
                        </li>
                        <li>
                            <i class="icon-user" ></i> Autor: {owner}
                        </li>
                        <li>
                            <i class="icon-flag" ></i> Genia: {genia}
                        </li>
                    </ul>
                </div>

                <div>
                    <textarea rows="3" class="span5">{observaciones}</textarea>
                </div>

            </div>
        </div>
        {/metas}
    </div>
    <!-- ============= metas  ============= -->



