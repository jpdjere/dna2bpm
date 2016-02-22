<form action="{base_url}bpm/engine/run_post/model/{idwf}/{idcase}/{token_id}" method="post">
{servicios}
<div class="panel panel-default">
  <div class="panel-heading">
       <h3>
           {name}
       </h3>
  </div>
  <div class="panel-body">
      <ul class="list-group">
    {items}
    <li class="list-group-item">
        
        <div class="radio">
          <label>
            <input type="radio" name="servicio" id="servicio-{item_value}" value="{item_value}">
            <h4>
                {item_name}
            </h4>
            {item_desc}
          </label>
        </div>
        </li>
    {/items}
  </ul>
  </div>
</div>
{/servicios}
<input class="btn btn-default" type="submit" value="Enviar">
</form>
