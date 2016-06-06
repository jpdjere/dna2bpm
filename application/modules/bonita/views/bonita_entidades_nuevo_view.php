<H3>{titulo}</H3>
<form class="cmxform" id="cargarform" name="cargarform"  method="post" action="">  
    <table>
        <tr>
            <td>Razón Social:&emsp;</td>
            <td><input type="text" id="rsocial" name="rsocial" value="{rsocial}"></td>
        </tr>
        <tr>
            <td>CUIT:&emsp;</td>
            <td><input type="text" id="ent_cuit" name="ent_cuit" value="{ent_cuit}"></td>
        </tr>
        <tr>
            <td>Observaciones:&emsp;</td>
            <td><input type="text" id="obs" name="obs" value="{obs}">
            </td>
        </tr>
        <tr>
            <td><button class="btn btn-default btn-xs" type="submit" ><i class="fa fa-floppy-o">&nbsp;Guardar</i></button></td>
        </tr>
    </table>
</form>