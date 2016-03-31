<div class="table-responsive">
<table class="table table-bordered table-responsive">
   <thead>
      <tr>
         <th rowspan="2" scope="col" class="info-table-center pad-thead">Área responsable</th>
        <th colspan="6" scope="col" class="info-table-center">Productos (Bien, servicio, proyecto o norma)</th>
  </tr>
  <tr>
         <th class="info-table-center">Scomp</th>
         <th class="info-table-center">Comp</th>
         <th class="info-table-center">Codigo</th>
         <th class="info-table-center">Descripción</th>
         <th class="info-table-center">Contratado</th>
      </tr>
   </thead>
   <tbody class="size-table-font" id="MyTable" contenteditable="true">
      {section}
      <tr>
         <td rowspan="{length}" scope="col" rowspan="{length}">{AREA}</td>
         <td>{SCOMP}</td>
         <td>{COMP}</td>
         <td>{CODIGO}</td>
         <td>{DESCRIP}</td>
         <td>{CONTRATADO}</td>
      </tr>
         {item}
      <tr>
         <td>{aSCOMP}</td>
         <td>{aCOMP}</td>
          <td>{aCODIGO}</td>
         <td>{aDESCRIP}</td>
         <td>{aCONTRATADO}</td>
      </tr>
        {/item}
      {/section}
   </tbody>
</table>
</div>
<table id="tabla-gonza" data-height="400" data-show-columns="true"></table>