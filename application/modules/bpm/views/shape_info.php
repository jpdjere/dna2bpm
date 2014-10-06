<!-- MODAL window with comments--> 
<div  class="panel panel-info" id="myModal"  role="dialog">
    <div class="panel-heading">        
        <h4 class="panel-title">{shape name}</h4>
    </div>
    <div class="panel-body">
        <div class="body-text"></div>
        {shape documentation}
        <h4>
            {lang performer}
        </h4>
        {resources}
        {name} {lastname}
        {/resources}
        <h4>
            {lang group}
        </h4>
        {performer}
        <h4>
            {lang comments}
        </h4>
        {comments}
        <!--End Content-->
    </div>
    <div class="panel-footer">

        <!-- Add Comment -->
        <div class="input-group">
            <input type="text" class="form-control">
            <span class="input-group-btn">
                <button class="btn btn-default" type="button">
                    <i class="glyphicon glyphicon-comment"></i>&nbsp;
                </button>
            </span>
        </div>
    </div>
</div>