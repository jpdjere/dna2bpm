<ul class="todo-list ui-sortable">
            {mytasks2}
            <li>
                
                <div class='row'>
                    <div class='col-md-12'>
                           <span class="handle">
                                <i class="fa fa-ellipsis-v"></i>
                                <i class="fa fa-ellipsis-v"></i>
                            </span>
                            {title}
                    </div>
                </div>
                <div class='row'>
                    <div class='col-md-12'>
                        
                        <!-- Extra data -->
                        {if {extra_data ip}}
                        <span class='label label-primary'> {extra_data ip} </span>
                        <span class='label label-primary'> {extra_data empresa} </span>
                        <span class='label label-primary'> CUIT: {extra_data empresa} </span>
                        {/if}
                        
                        <div class="pull-right">
                            
                            <a href='{base_url}bpm/engine/run/model/{idwf}/{case}/{resourceId}' title="Realizar tarea">
                            
                            
                                <i class="fa fa-play fa-lg" aria-hidden="true"></i>
                            </a>
                        </div>
                
                    </div>
                </div>
                

                <small class="label  {label-class} "><i class="fa fa-clock-o"></i>&nbsp;&nbsp;{label} </small>


            </li>
            {/mytasks2}
</ul>