<div class="well">
    <div class="row">
        <div class="col-md-12">
            <table class="table table-striped table-condensed" id="cuotas" width="100%">
                <h4>{title}</h4>
                <br/>
                <thead>
                <tr>
                    <th>Fecha de pago</th>
                    <th>Fecha de licitacion</th>
                    <th>Número de días</th>
                    <th>Amortización</th>
                    <th>Remanente</th>
                    <th>Intereses</th>
                    <th>Cuota</th>
                    <th>Acumulado interés</th>
                    <th>Bonificación</th>
                    <th>Período</th>
                    <th>Puntos bonificados</th>
                    <th>Acumulado capital</th>
                </tr>
                </thead>
                <tbody>
                {cuotas}
                <tr>
                    <td>{fecha_pago}</td>
                    <td>{fecha_liq}</td>
                    <td>{num_days}</td>
                    <td>{amortizacion}</td>
                    <td>{remaining}</td>
                    <td>{intereses}</td>
                    <td>{cuota}</td>
                    <td>{accInt}</td>
                    <td>{bonif}</td>
                    <td>{periodo}</td>
                    <td>{puntos_bon}</td>
                    <td>{accCap}</td>
                </tr>
                {/cuotas}
                </tbody>
            </table>
        </div>
    </div>

</div>