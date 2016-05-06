<div class = "row">
    
<!-- Tramites -->

<div class = "col-md-3 load_tiles_after" data-id='tramites' href='{base_url}/dashboard/lite/tramites' >
    <div class='row center-block dashboard_shortcut'>
       <div class='title'><i class="fa fa-paperclip fa-2x" aria-hidden="true"></i><span>Tramites</span></div>
       <div class="label label-{tramites_count_label_class} pull-right">{tramites_count_qtty}</div>
    </div>
</div>

<!-- Mis tramites -->

<div class = "col-md-3 load_tiles_after" data-id='tramites' href='{base_url}/bpm/bpmui/widget_cases' >
    <div class='row center-block dashboard_shortcut'>
       <div class='title'><i class="fa fa-paperclip fa-2x" aria-hidden="true"></i><span>Mis Tramites</span></div>
       <div class="label label-{mistramites_count_label_class} pull-right">{mistramites_count_qtty}</div>
    </div>
</div>


<!-- Tareas -->

<div class = "col-md-3 load_tiles_after" data-id='tramites' href='{base_url}/bpm/bpmui/widget_2do'>
    <div class='row center-block dashboard_shortcut'>
       <div class='title'><i class="fa fa-list-alt fa-2x" aria-hidden="true"></i><span>Mis Tareas</span></div>
       <div class="label label-{tareas_count_label_class} pull-right">{tareas_count_qtty}</div>
    </div>
</div>



<!-- Notificaciones -->

<div class = "col-md-3 " data-id='inbox' >
    <div class='row center-block dashboard_shortcut'>
       <div class='title'><i class="fa fa-envelope fa-2x" aria-hidden="true"></i><span>Notificaciones</span></div>
       <div class="label label-{inbox_count_label_class} pull-right">{inbox_count_qtty}</div>
    </div>
</div>




</div>
