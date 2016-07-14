<div class="row">
<div class="col-sm-3">
<img src="{avatar}"  class="avatar" style="width:120px" >
</div>
<div class="col-sm-9">

<input type="hidden" name="cuit" value="{cuit}" /> 
<select class="form-control" id="search_empresa">

{empresas}
<option name="" value="{1695}">{1693} | {1695}</option>
{/empresas}
<option name="" value="23-22277112-9" >Dummy</option>
</select>
<h3 ></h3>
<ul class='list-unstyled'>
<li><strong>Sector:</strong> <span id='profile_sector'>{sector}</span></li>
<li><strong>Clasificación:</strong><span id='profile_pyme'> {isPyme}</span></li>
<li><strong>Categoría:</strong> <span id='profile_categoria'>{categoria}</span></li>
</ul>
<a type="button" href="{base_url}dashboard/profile" class="pull-right btn btn-general btn-xs "><i class="fa fa-pencil-square-o" aria-hidden="true"></i>
 Editar</a>
</div>
</div>

<div class="" style="border-top:1px solid #ccc;height:10px;margin-top:9px"></div>

<div class="row">
<div class="col-sm-12">
<button type="button" class="btn btn-primary btn-md btn-block">Mi certificado PYME</button>
</div>

</div>