<!-- HEADER -->
<div id="header_wrap" class="text-center">
    <header class="inner">

        <h1 id="project_title">   <i class="icon-qrcode"></i> 
            <!--<img  class="img-polaroid" src="{base_url}qr/gen_url/{module_url_encoded}/6/L" width="160" height="160"/>-->  
            {title}
        </h1>
    </header>
</div>
<div class="container-fluid">
    <div class="row-fluid">
        <div class="span3">
            <fieldset><legend>
                    <i class="icon-facetime-video"></i> Con Cámara
                </legend>
                <!--Sidebar content-->
                <ul class="nav nav-pills nav-stacked" id="sidenav">
                    <li>
                        <a href="{module_url}query" id="info">
                            <i class="icon-info-sign"></i>
                            Informaci&oacute;n Expediente
                            <i class="icon-chevron-right"></i>
                        </a>
                    </li>
                    <li>

                        <a href="{module_url}checkin">
                            <i class="icon-signin"></i>
                            Check-In
                            <i class="icon-chevron-right"></i>
                        </a>
                    </li>
                    <!--
                     <li>
                         <a href="{module_url}assign" id="assign">
                             <i class="icon-user"></i>
                             Asignar a Usuario
                             <i class="icon-chevron-right"></i>
                         </a>
                     </li>
                    -->
                </ul>
            </fieldset>
            <fieldset><legend>
                    <i class="icon-user"></i> Manual
                </legend>
                <div class="form-inline">
                    <div class="input-prepend">
                        <span class="add-on">Tipo</span>
                        <select name="type" id="type" >
                            <option val="PDE">
                                PDE
                            </option>
                            <option val="PP">
                                PP
                            </option>
                            <option val="PFI">
                                PFI
                            </option>
                        </select>
                    </div>
                    <br/>
                    <br/>
                    <div class="input-prepend input-append">
                        <span class="add-on">Nro</span>
                        <input id="code" type="text" name="code" placeholder="2234/2012">
                        <button type="button" class="btn" id="btn_seach">
                            <i class="icon-search"></i> 
                        </button>
                    </div>
                    <br/>
                    <br/>
                    <button type="button" class="btn" id="btn_claim">
                        <i class="icon-signin"></i> 
                        Check-In
                    </button>
                    <br/>
                    <br/>
                    <button type="buton" class="btn" id="btn_gencode">
                        <i class="icon-qrcode"></i> 
                        Generar C&oacute;digo
                    </button>
                </div>
            </fieldset>
            <fieldset>
                <legend>
                    <i class="icon-search"></i> 
                    Mostrar Expedientes
                </legend>
                <form action="{module_url}show_objects" class="form-inline">
                    <span class="add-on"><i class="icon-group"></i> Grupo</span>
                    <br/>
                    <select name="group" id="group_select">
                        {groups}
                        <option value="{idgroup}">
                            {name}
                        </option>
                        {/groups}
                    </select>
                    <br/>
                    <br/> 
                    <span class="add-on"><i class="icon-user"></i> Usuario</span>
                    <br/>
                    <select name="user" id="user_select" size="1">
                        {users}
                        <option value="{idu}">
                            {name} {lastname}
                        </option>
                        {/users}
                    </select>
                    <br/>
                    <br/>
                    <input id="data" type="hidden" value="{data}"/>
                    <a id="btn_showobjects" class="btn">
                        Mostrar
                    </a>
                </form>
            </fieldset>

        </div>
        <div class="span9">
            <!--Body content-->
            <!-- MAIN CONTENT -->
            <div id="main_content_wrap" class="text-center">
                <section id="main_content">
                    <div id="result"></div>
                </section>
            </div>
        </div>
    </div>
</div>

<!-- FOOTER 
<div id="footer_wrap" class="outer">
    <footer class="inner">
        &copy; SePyME 2013
    </footer>
</div>
-->