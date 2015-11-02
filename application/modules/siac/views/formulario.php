<div class="container">
    
<form class="form-horizontal" method="POST" action="{module_url}guardar_formulario/{idwf}/{idcase}/{token_id}">
    <fieldset>

        <!-- Form Name -->
        <legend>Reclamo</legend>

        <!-- Multiple Radios -->
        <div class="form-group">
            <label class="col-md-4 label-control" for="radios">Servicio</label>
            <div class="col-md-4">
                {Servicios}
                <div class="radio">
                    <label for="radios-{value}">
                        <input  type="radio" name="radios" id="radios-{value}" value="{value}"> {name}
                    </label>
                </div>
                {/Servicios}
                
            </div>
        </div>

        <!-- Textarea -->
        <div class="form-group">
            <label class="col-md-4 " for="textarea">Descripción</label>
            <div class="col-md-4">
                <textarea class="form-control" id="textarea" required="" name="desc"></textarea>
            </div>
        </div>

        <!-- Text input-->
        <div class="form-group">
            <label class="col-md-4 " for="email">Direccion de orreo</label>
            <div class="col-md-4">
                <input id="email" name="email" type="text" placeholder="juanperez@argentina.com.ar" class="form-control input-md" required="">
                <span class="help-block">Ingrese aquí su dirección de correo electrónico</span>
            </div>
        </div>

        <!-- Text input-->
        <div class="form-group">
            <label class="col-md-4 " for="telefono">Teléfono</label>
            <div class="col-md-4">
                <input id="telefono" name="telefono" type="text" placeholder="0244-7356-2236" class="form-control input-md" required="">
                <span class="help-block">ingrese un teléfono de contacto</span>
            </div>
        </div>

        <!-- Button -->
        <div class="form-group">
            <label class="col-md-4 " for="enviar"></label>
            <div class="col-md-4">
                <button type="submit" id="enviar" name="enviar" class="btn btn-success">Enviar</button>
            </div>
        </div>

    </fieldset>
</form>
</div>