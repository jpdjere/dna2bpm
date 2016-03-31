
<html lang="en" class="no-js">    
    <body>
         <H3>CARGA DE NUEVOS REPORTES</H3>
        
         </TR>
         <tr>
        <div id="fileupload">
            <form action="{base_url}/upload/" method="POST" enctype="rr/form-data">
                <!--<div class="fileupload-buttonbar">
                    <label class="fileinput-button" >


                    <input type="file" name="files[]" class="btn btn-primary btn-xs" multiple >
                    </label>
                    </br>
                    <button type="submit" class="btn btn-primary btn-xs">Subir todos</button>
                    </br> 
                    <button type="reset" class="btn btn-primary btn-xs">Cancelar todos</button>
                    
                     
                </div>-->
                <div class="form-group">
					<div id="filelist">{lang uploader_error}</div>
					<br />
					<div id="container">
					    <a id="pickfiles" class="btn btn-primary btn-xs" href="javascript:;"><i class="fa fa-files-o"></i> {lang SelectFile}</a> 
					    <a id="uploadfiles" class="btn btn-primary btn-xs" href="javascript:;"><i class="fa fa-cloud-upload"></i> {lang UploadFile}</a>
					</div>
			
			</div>
            </form>
            
        </div>            
    
</body>
</html>
    

