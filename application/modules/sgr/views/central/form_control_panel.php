<h3>DEUDORES</h3>	
<form method="post" class="well" id="form" target="_blank" action="central/action_form">


    <div class="row ">
        <!--  ========================== row 4 . ========================== -->
        <div class="col-md-4" >
            <div class="form-group col-md-8">
                <label>C.U.I.T. Participe </label>
            </div>
        </div>
        <!--  CUIT  -->
        <div class="row ">
            <div class="form-group col-md-6">                    
                <input type="text" class="form-control" name="cuit_sharer" placeholder="Ingrese el C.U.I.T. sin guiones XXXXXXXXXXX" />
            </div>
        </div>

    </div><!-- row4-->




    <!--  ROW 3  -->
    <div class="row">
        <div class="col-md-12">
            <input type="hidden" name="anexo" value="{anexo}" />
            <button name="submit_period"
                    class="btn btn-block btn-primary hide_offline" type="submit"
                    id="bt_save_{sgr_period}">
                <i class="fa fa-search"></i> Buscar
            </button>
        </div>
    </div>
</form>
