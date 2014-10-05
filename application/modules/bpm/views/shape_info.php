<!-- MODAL window with comments--> 
<div  class="" id="myModal"  role="dialog">
    <div class="dialog" >
        <div class="content">
            <!--Content-->
            <div class="header">
                <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <h4 class="title" id="myModalLabel">{shape name}</h4>
            </div>
            <div class="body">
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
            <div class="footer">

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
    </div>
</div>