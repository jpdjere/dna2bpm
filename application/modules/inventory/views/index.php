<!-- HEADER -->
<div id="header_wrap" class="text-center">
    <header class="inner">

        <h1 id="project_title">   <i class="icon-qrcode"></i> 
            <!--<img  class="img-polaroid" src="{base_url}qr/gen_url/{module_url_encoded}/6/L" width="160" height="160"/>-->  
            {title}
        </h1>
    </header>
</div>

<!-- MAIN CONTENT -->
<div id="main_content_wrap" class="text-center">
    <section id="main_content">
        <ul class="nav nav-pills nav-stacked">
            <li>

                <h3>

                    <i class="icon-info-sign"></i>
                    <a href="{module_url}query" id="info">
                        Informaci&oacute;n Expediente
                    </a>
                </h3>

            </li>
            <li>
                <h3>

                    <i class="icon-signin"></i>
                    <a href="{module_url}checkin">
                        Check-In
                    </a>
                </h3>
            </li>
            <li>
                <h3>

                    <i class="icon-signin"></i> <i class="icon-user"></i>
                    <a href="{module_url}assign" id="assign">
                        Asignar a Usuario
                    </a>
                </h3>
            </li>
            <li>
                <h3>
                    <i class="icon-qrcode"></i> 
                    Generar C&oacute;digo
                </h3>

                <form class="form-inline" action="{module_url}gencode" method="POST">
                    <div class="input-prepend">
                        <span class="add-on">Tipo</span>
                        <select name="type" class="span2" id="type" >
                            <option val="PDE">
                                PDE
                            </option>
                            <option val="PP">
                                PP
                            </option>
                        </select>
                    </div>
                    <div class="input-prepend">
                        <span class="add-on">Nro</span>
                        <input class="span2" id="code" type="text" name="code" placeholder="2234/2012">
                    </div>
                    <button type="submit" class="btn">Generar</button>
                </form>

            </li>
        </ul>
        <br/>
        <br/>
    </section>
</div>
<!-- FOOTER  -->
<div id="footer_wrap" class="outer">
    <footer class="inner">
        &copy; SePyME 2013
    </footer>
</div>