<table class="table table-bordered" id="tabla-resumen" {hidden}>
  
   <tbody class="size-table-font"  id="resultados">
    <tr>
         <th scope="col" colspan="10" class="info-table-resumen">Título de Proyecto: {titulo proyecto}</th>
    </tr>
    <tr>
         <th scope="col" colspan="2" class="info-table-resumen pad-resumen">Proyecto (PP/PN) Nº: {proyecto numero}</th>
         <th scope="col" colspan="5" class="info-table-resumen pad-resumen">Empresa: {empresa}</th>
         <th scope="col" colspan="2" class="info-table-resumen pad-resumen">CUIT: {cuit_empresa}</th>
         <th scope="col" colspan="1" class="info-table-resumen pad-resumen">Tipo: {tipo_empresa}</th>
    </tr>
    <tr>
         <th scope="col" colspan="4" class="info-table-resumen">Actividad CLAE: {actividad clae empresa}</th>
         <th scope="col" colspan="3" class="info-table-resumen">Facturación Promedio: {facturacion empresa}</th>
         <th scope="col" colspan="3" class="info-table-resumen">Cantidad de Empleados: {empleados empresa}</th>
    </tr>
    <tr>
         <th scope="col" colspan="4" class="info-table-resumen">Domicilio Legal: {domicilio legal empresa}</th>
         <th scope="col" colspan="3" class="info-table-resumen">Provincia: {provincia empresa}</th>
         <th scope="col" colspan="3" class="info-table-resumen">Ciudad: {ciudad empresa}</th>
    </tr>
    <tr>
         <th scope="col" colspan="4" class="info-table-resumen pad-resumen">Representante Legal: {representante empresa}</th>
         <th scope="col" colspan="3" class="info-table-resumen pad-resumen">Tel: {telefono empresa}</th>
         <th scope="col" colspan="3" class="info-table-resumen pad-resumen">Mail: {email empresa}</th>
    </tr>
    <tr>
         <th scope="col" colspan="4" class="info-table-resumen pad-resumen">Contacto Técnico: {contacto tecnico empresa}</th>
         <th scope="col" colspan="3" class="info-table-resumen pad-resumen">Tel: {telefono contacto tecnico empresa}</th>
         <th scope="col" colspan="3" class="info-table-resumen pad-resumen">Mail: {email contacto tecnico empresa}</th>
    </tr>
    <tr>
         <th scope="col" colspan="4" class="info-table-resumen">Fecha Aprobación: {fecha aprobacion}</th>
         <th scope="col" colspan="3" class="info-table-resumen">Estado del Proyecto: {estado del proyecto}</th>
         <th scope="col" colspan="3" class="info-table-resumen">Fecha del Estado: {fecha del estado}</th>
    </tr>
    <tr>
         <th scope="col" colspan="4" class="info-table-resumen">Fecha de Fin  Actividades: {fecha fin actividades}</th>
         <th scope="col" colspan="3" class="info-table-resumen">Evaluador: {evaluador}</th>
         <th scope="col" colspan="3" class="info-table-resumen">Ventanilla/Incubadora: {ventanilla o incubadora}</th>
    </tr>
    <tr>
         <th scope="col" colspan="10" class="info-table-resumen pad-thead pad-resumen">Descripción: {descripcion}</th>
    </tr>
    <tr>
         <th scope="col" colspan="2" class="info-table-resumen pad-resumen">Total Proyecto: {total proyecto}</th>
         <th scope="col" colspan="2" class="info-table-resumen pad-resumen">Total ANR Proyecto: {total ANR proyecto}</th>
         <th scope="col" colspan="2" class="info-table-resumen pad-resumen">Total Aporte Emprendedor: {total aporte emprendedor}</th>
         <th scope="col" colspan="2" class="info-table-resumen pad-resumen">% ANR Proyecto: {porcentual ANR proyecto}</th>
         <th scope="col" colspan="1" class="info-table-resumen pad-resumen">% Aporte Emprendedor: {porcentual aporte emprendedor}</th>
         <th scope="col" colspan="1" class="info-table-resumen pad-resumen">Gastos Certificación: {gastos certificacion}</th>
    </tr>
    <tr>
         <th class="info-table-resumen pad-resumen text-center">Actividad</th>
         <th class="info-table-resumen pad-resumen text-center">Mes Presentación</th>
         <th class="info-table-resumen pad-resumen text-center">Descripción (máx. 20 caracteres)</th>
         <th class="info-table-resumen pad-resumen text-center">Fecha Vto. Original</th>
         <th class="info-table-resumen pad-resumen text-center">Fecha Vto. Vigente</th>
         <th class="info-table-resumen pad-resumen text-center">Total Actividad</th>
         <th class="info-table-resumen pad-resumen text-center">ANR Actividad</th>
         <th class="info-table-resumen pad-resumen text-center">Fecha Aprobación</th>
         <th class="info-table-resumen pad-resumen text-center">Estado Actividad</th>
         <th class="info-table-resumen pad-resumen text-center">Fecha Estado</th>
    </tr>
    {actividad}
    <tr>
         <th class="info-table-resumen">{actividad}</th>
         <th class="info-table-resumen">{mes_presentacion}</th>
         <th class="info-table-resumen">{descripcion}</th>
         <th class="info-table-resumen">{fecha vencimiento original}</th>
         <th class="info-table-resumen">{fecha vencimiento vigente}</th>
         <th class="info-table-resumen">{total actividad}</th>
         <th class="info-table-resumen">{ANR actividad}</th>
         <th class="info-table-resumen">{fecha aprobacion}</th>
         <th class="info-table-resumen">{estado actividad}</th>
         <th class="info-table-resumen">{fecha estado}</th>
    </tr>
   {/actividad} 
    <tr>
         <th scope="col" colspan="2" class="info-table-resumen pad-resumen text-center">Tipo de Seguimiento</th>
         <th class="info-table-resumen pad-resumen text-center">Fecha Mail Seguimiento</th>
         <th class="info-table-resumen pad-resumen text-center">Fecha Vto. Mail Seguimiento</th>
         <th class="info-table-resumen pad-resumen text-center">Fecha Vto. Mail Seguimiento</th>
         <th class="info-table-resumen pad-resumen text-center">Fecha respuesta Mail Seguimiento</th>
         <th class="info-table-resumen pad-resumen text-center">Fecha respuesta Mail Seguimiento</th>
         <th scope="col" colspan="2" class="info-table-resumen pad-resumen text-center">Descripción Respuesta Mail Seguimiento</th>
         <th class="info-table-resumen pad-resumen text-center">Fecha CD Seguimiento</th>
    </tr>
    {actividad}
    <tr>
         <th scope="col" colspan="2" class="info-table-resumen">{tipo seguimiento}</th>
         <th class="info-table-resumen">{fecha seguimiento email}</th>
         <th class="info-table-resumen">{fecha vencimiento seguimiento email}</th>
         <th class="info-table-resumen">{fecha vencimiento seguimiento email}</th>
         <th class="info-table-resumen">{fecha respuesta seguimiento email}</th>
         <th class="info-table-resumen">{fecha respuesta seguimiento email}</th>
         <th scope="col" colspan="2" class="info-table-resumen">{respuesta seguimiento}</th>
         <th class="info-table-resumen">{fecha seguimiento cd}</th>
    </tr>
   {/actividad}
    <tr>
         <th scope="col" colspan="5" class="info-table-resumen">Fecha Último Movimiento: {fecha del estado}</th>
         <th scope="col" colspan="5" class="info-table-resumen">Estado del Último Movimiento: {estado del proyecto}</th>
    </tr>
    <tr>
         <th scope="col" colspan="5" class="info-table-resumen">Motivo del Rechazo:{motivo del rechazo}</th>
         <th scope="col" colspan="5" class="info-table-resumen">Comentarios Internos:{comentarios internos}</th>
    </tr>
</tbody>
 
</table>