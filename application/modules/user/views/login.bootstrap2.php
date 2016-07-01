<meta property="og:image" content="{base_url}user/assets/images/login.png"> 
<link itemprop="thumbnailUrl" href="{base_url}user/assets/images/login.png">
        <div class="form-box" id="login-box">
            <div class="header bg-light-blue">{lang loginMsg}</div>
            <form id="formAuth" action="{authUrl}" method="post">
                <div class="body bg-gray">
                <!--  MSG -->
      				{if {show_warn}}
                    <div class="form-group alert alert-warning">
                       <button type="button" class="close" data-dismiss="alert">&times;</button>
							<strong>{msgcode}</strong>	
                    </div>          
					{/if}
				 <!--  NAME -->
                    <div class="form-group">
                        <input type="text" name="username" class="form-control" placeholder="{lang username}"/>
                    </div>
                 <!--  PASS -->    
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="{lang password}"/>
                    </div>      
                 <!--  REMEMBERME -->      
                    <div class="form-group">
                       <input type="checkbox" value="remember-me" > {lang rememberButton}
                    </div>
                </div>
                <div class="footer">                                                               
                    <button type="submit" class="btn btn-default btn-flat btn-block">&nbsp;{lang loginButton}</button>  
                    <p>
                    <a href="{base_url}../dna2/pub/register.bpm.php" ><i class="fa fa-user"></i>&nbsp;{lang newUser}</a>
                    </p>
                    <p>
                   <!--  <a href="{module_url}recover" ><i class="fa fa-unlock"></i> -->
                    <a href="http://dna2.produccion.gob.ar/dna2/login.php?reset=1" ><i class="fa fa-unlock"></i>

                    
 {lang forgotPassword} </a>
                    </p>
                    
                </div>
            </form>


        </div>
 
 
