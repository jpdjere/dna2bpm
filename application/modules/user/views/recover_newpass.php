<div class="container">
<div class="row">
<div class="col-md-4 col-md-offset-4">        
    <form class="form-signin" id="formAuth" action="{module_url}recover/save_new_pass" method="post">
        
        <h1 class="form-signin-heading">{lang mailform}</h1>  
        
        <div class="form-group">
            <label for="exampleInputEmail1">Password</label>
            <input name="password" id="password" value="" class="form-control" placeholder="Ingrese su nueva contraseña" type="password">
        </div>
        
        <div class="form-group">
            <label for="exampleInputEmail1">Repetir Password</label>
            <input name="password2" id="password2" value="" class="form-control" placeholder="Repita la contraseña" type="password">
        </div>
        <input name="token" id="token" type="hidden" value="{token}">
        <button class="btn btn-info btn-block" type="submit">{lang loginButtonR}</button>
        
        <span id="dummy" style="display:none">
               
        </span>
        <br/>
    </form>

</div> 

</div> 
<!-- /container -->