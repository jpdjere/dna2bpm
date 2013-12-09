<!-- Submenu / Breadcrumbs -->
<div class="row-fluid " >
    <ul class="breadcrumb" style="margin-bottom:0px;padding-bottom:0px" >
       
        <li ></li> 
        <li class="pull-right perfil">
            <a title="{usermail}">{username}</a> <i class="icon-angle-right"></i> <i class="{rol_icono}"></i> {rol}
        </li>
    </ul>
    <ul class="breadcrumb breadcrumb-genias" style="padding-top:0px">
        <li ></li>      
        {genias}  
        <li class="pull-right "><span class="divider">/</span</li>
        <li class="pull-right">{nombre}</li>
        {/genias}
    </ul> 
</div>

<!-- INBOX WIDGET -->
<div class="container-fluid">
    <!-- 2row block -->
    <div class="row-fluid">
        <!-- Start 2nd col -->
        <div class="span12">
            <ul class="msgs">
                {mymsgs}
                <li id="{msgid}">
                    <span class="label label-default">{date}</span>
                    <a class="icon {icon_star}" href="#"></a>
                    <a class="icon icon-user" href="#" title="{sender}"></a>
                    <a class="subject {read}" href="#">{subject}</a>
                    <div class="detail">
                        <div class="from"><strong>De: </strong><span>{sender}</span></div>
                        <div class="body">{body}</div>   
                    </div>
                </li>
                {/mymsgs}
            </ul>
        </div>
        <!-- End 2nd col -->
    </div>

</div> 
