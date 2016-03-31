
<div class="box box-solid box-warning">

    <span class="hidden widget_url">{widget_url}</span>
    <div class="box-header" style="cursor: move;">

        <i class="fa fa-download"></i>
        <h3 class="box-title">{title} ({qtty}) </h3>
        <div class="box-tools pull-right">
        </div>
    </div><!-- /.box-header -->
    <div class="box-body">
        {cases}
        <h4>
            Caso: {id}
        </h4>
        <ul class="todo-list ui-sortable">
            {mytasks}
            <li>
                <!-- todo icon -->
                <!--<span class="text"><img src="{base_url}{icon}"/></span>-->
                <!-- todo text -->
                <a href='{base_url}bpm/engine/run_post/model/{idwf}/{id}/{resourceId}' title="Realizar tarea">
                <i class="fa fa-file fa-4x"></i><span class="text">
                <!--<img src="{base_url}{icon}">-->
                {title}

                </span>
                </a>
            </li>
            {/mytasks}
        </ul>
        {/cases}
    </div><!-- /.box-body -->
    <div class="box-footer clearfix no-border">
<!--<button style="margin-right: 5px;" title="" data-toggle="tooltip" data-widget="refresh" class="btn btn-sm pull-right" data-original-title="Refresh"><i class="fa fa-refresh"></i></button>-->
    </div>
</div>