<div class="container">
    
<form class="form-horizontal">
    <fieldset>

        <!-- Form Name -->
        <legend>Reclamo</legend>

        <!-- Multiple Radios -->
        <div class="form-group">
            <label class="col-md-4 label-control" for="radios">Multiple Radios</label>
            <div class="col-md-4">
                <div class="radio">
                    <label for="radios-0">
                        <input  type="radio" name="radios" id="radios-0" value="1" checked="checked"> Alumbrado
                    </label>
                </div>
                <div class="radio">
                    <label for="radios-1">
                        <input type="radio" name="radios" id="radios-1" value="2"> Arbolado
                    </label>
                </div>
                <div class="radio">
                    <label for="radios-2">
                        <input  type="radio" name="radios" id="radios-2" value="3"> Vía pública (calles, veredas,ramblas, etc)
                    </label>
                </div>
                <div class="radio">
                    <label for="radios-3">
                        <input type="radio" name="radios" id="radios-3" value="4"> Habilitaciones y Permisos
                    </label>
                </div>
                <div class="radio">
                    <label for="radios-4">
                        <input  type="radio" name="radios" id="radios-4" value="5"> Limpieza y Recolección
                    </label>
                </div>
                <div class="radio">
                    <label for="radios-5">
                        <input  type="radio" name="radios" id="radios-5" value="6"> Residuos Especiales
                    </label>
                </div>
            </div>
        </div>

        <!-- Textarea -->
        <div class="form-group">
            <label class="col-md-4 " for="textarea">Descripción</label>
            <div class="col-md-4">
                <textarea class="form-control" id="textarea" name="textarea"></textarea>
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