<h3>Formulario de Consulta - Resultados:</h3>
<div class="container" style="width:100%;">
    <?php $i=1; foreach($results as $resultado):?>
        <?php echo "Resultado $i: <br>" ?>
        <table>
        <?php foreach($resultado as $clave => $valor):?>
            <?php if($clave!='_id' && $clave!='idwf' && $clave!='token' && $clave!='volver' && $clave!='rbt' && $clave!='parques' && $clave!='mi_galpon'):?>
            <tr>
                <td style="padding-right:20px;"><label for=""><?php echo $clave ?></label></td>
                <td><label for=""><?php echo $valor ?></label></td>
            </tr>
            <?php endif; ?>
        <?php endforeach; $i+=1;?>
        </table>
        <br><br>
    <?php endforeach; ?>
</div>