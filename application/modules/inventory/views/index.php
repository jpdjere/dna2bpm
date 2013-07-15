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
            <!--Sidebar content-->
            <ul class="nav nav-pills nav-stacked">
                <li>
                    <a href="{module_url}query" id="info">
                        <i class="icon-info-sign"></i>
                        Informaci&oacute;n Expediente
                    </a>
                </li>
                <li>

                    <a href="{module_url}checkin">
                        <i class="icon-facetime-video"></i>
                        Check-In
                    </a>
                </li>
                <li>
                    <a href="{module_url}assign" id="assign">
                        <i class="icon-signin"></i> <i class="icon-user"></i>
                        Asignar a Usuario
                    </a>
                </li>
                <li>

                    <div class="form-inline img-polaroid">
                        <div class="input-prepend">
                            <span class="add-on">Tipo</span>
                            <select name="type" id="type" >
                                <option val="PDE">
                                    PDE
                                </option>
                                <option val="PP">
                                    PP
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

                </li>
            </ul>
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
<!-- FOOTER  -->
<div id="footer_wrap" class="outer">
    <footer class="inner">
        &copy; SePyME 2013
    </footer>
</div>