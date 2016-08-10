<div class="row">

<div class="col-sm-12">

<input type="hidden" name="cuit" value="{cuit}" /> 

{empresas}

<!-- <option name="" value="23-22277112-9" >Dummy</option> -->
</select>
<h3 ></h3>
<ul class='list-unstyled'>
<li><strong>Sector:</strong> <span id='profile_sector'>{sector}</span></li>
<li><strong>Clasificación:</strong> {isPyme}</li>
<li><strong>Categoría:</strong> {categoria}</li>
<li><strong>Actividad:</strong> {actividad_texto}</li>
</ul>

</div>
</div>

<div class="" style="border-top:1px solid #ccc;height:10px;margin-top:9px"></div>

<div class="row">
<div class="col-sm-12">
<a href="{base_url}afip/consultas/certificado/{cuit}" type="button" class="btn btn-primary btn-md btn-block {certificado}">Constancia de Categorización MiPyme</a>
</div>

</div>