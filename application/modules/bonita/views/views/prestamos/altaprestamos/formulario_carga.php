<div id="contenedor" style="">
<H3>Carga de datos:</H3>
    <form id="form_principal" method="post" action="{base_url}bonita/prestamos/insertar_prestamo">
        <div id="contenedor-chico">
        <table id="tabla_principal" class="table table-striped table-condensed">
            <thead>
            <tr>
                <td>Entidad Financiera</td>
                <td>Apellido y Nombre/Razón Social</td>
                <td>Cuit</td>
                <td>Localidad</td>
                <td>Municipio</td>
                <td>Partido/Departamento</td>
                <td>Provincia</td>
                <td>Código Postal</td>
                <td>Teléfono</td>
                <td>E-mail</td>
                <td>Fecha de inicio de las actividades</td>
                <td>Sector</td>
                <td>Codigo de actividad principal</td>
                <td>Detalle de Actividad Principal</td>
                <td>Volumen de venta anual del ultimo ejercicio, sin incluir Impuesto al Valor Agregado (IVA) del Titular.</td>
                <td>Periodo inicial al que corresponden</td>
                <td>Periodo final al que corresponden</td>
                <td>Promedio de ventas de los ultimos tres ejercicios (sin incluir IVA)</td>
                <td>Resultado operativo del ultimo ejercicio (segun estados contables auditados)</td>
                <td>Cantidad de empleados</td>
                <td>Endeudamiento total de la empresa con el sistema financiero al momento del otorgamiento</td>
                <td>Endeudamiento de la empresa con el Banco al momento del otorgamiento</td>
                <td>Numero de prestamo</td>
                <td>Capital acreditado</td>
                <td>Fecha de acreditacion del prestamo</td>
                <td>Destino de los fondos</td>
                <td>Plazo total del prestamo (en meses)</td>
                <td>Tasa de interes cobrada en las Micro, P/M Empresas, extpresada como Tasa Nominal Anual Vencida (en %)</td>
                <td>Sistema de amortizacion</td>
                <td>Cantidad de cuotas</td>
                <td>Monto de la primera cuota</td>
                <td>Fecha del primer vencimiento capital</td>
                <td>Fecha del primer vencimiento interes</td>
                <td>Gracia de capital (en meses)</td>
                <td>Gracia de interes (en meses)</td>
                <td>Frecuencia de los servicios de capital</td>
                <td>Frecuencia de los servicios de interes</td>
                <td>Joven Empresario</td>
                <td>Cliente Nuevo</td>
                <td>Garantia SGR</td>
                <td>SGR involucrada</td>
                <td>Garantia</td>
                <td>Observaciones</td>
                <td>Resolucion</td>
            </tr>
            </thead>
            <tr>
                <td><select class="form-control" name="efi" required/>              <!--<td>Entidad Financiera</td>-->
                    <option value="" disabled selected></option>
                    {entidades}
                    <option value="{id}">{razon_social}</option>
                    {/entidades}
                    </select>
                </td>
                <td><input class="form-control" type="text" name="razon_social" required/></td>                        <!--<td>Apellido y Nombre/Razón Social</td>-->
                <td><input class="form-control" type="text" name="cuit" required/></td>                                <!--<td>Cuit</td>-->
                <td><input class="form-control" type="text" name="localidad"/></td>                           <!--<td>Localidad</td>-->
                <td><input class="form-control" type="text" name="municipio"/></td>                           <!--<td>Municipio</td>-->
                <td><select class="form-control chosen" name="partidodpto"/>
                    <option value="" disabled selected></option>
                    {partidos}
                    <option value="{clave}">{valor}</option>
                    {/partidos}
                    </select>
                </td>                         <!--<td>Partido/Departamento</td>-->
                <td><select class="form-control chosen" name="provincia" required/>                           <!--<td>Provincia</td>-->
                    <option value="" disabled selected></option>
                    {provincias}
                    <option value="{clave}">{valor}</option>
                    {/provincias}
                    </select>
                </td>
                <td><input class="form-control" type="number" name="cp" min="0"/></td>                                  <!--<td>Código Postal</td>-->
                <td><input class="form-control" type="text" name="telefono"/></td>                            <!--<td>Teléfono</td>-->
                <td><input class="form-control" type="email" name="email"/></td>                               <!--<td>E-mail</td>-->
                <td><input class="form-control calendar" type="text" name="fecha_ini_actividades" required/></td>               <!--<td>Fecha de inicio de las actividades</td>-->
                <td>
                    <select class="form-control chosen" name="sector" required/>
                        <option value="" disabled selected></option>
                        {sectores}
                        <option value="{clave}">{valor}</option>
                        {/sectores}
                    </select>
                
                </td>                              <!--<td>Sector</td>-->
                <td><input class="form-control" type="number" name="codigo" min="0"/></td>                              <!--<td>Codigo de actividad principal</td>-->
                <td><input class="form-control" type="text" name="actividad"/></td>                           <!--<td>Detalle de Actividad Principal</td>-->
                <td><input class="form-control" type="number" name="ventas_ult_ej" min="0"/></td>                       <!--<td>Volumen de venta anual del ultimo ejercicio, sin incluir Impuesto al Valor Agregado (IVA) del Titular.</td>-->
                <td><input class="form-control calendar" type="text" name=""/></td>                        <!--<td>Periodo Inicial</td>-->
                <td><input class="form-control calendar" type="text" name=""/></td>                        <!--<td>Periodo Final</td>-->
                <td><input class="form-control" type="number" name="ventas_prom_utl3" required min="0"/></td>                    <!--<td>Promedio de ventas de los ultimos tres ejercicios (sin incluir IVA)</td>-->
                <td><input class="form-control" type="text" name=""/></td>                        <!--<td>Resultado operativo del ultimo ejercicio (segun estados contables auditados)</td>-->
                <td><input class="form-control" type="number" name="cant_emp" min="0"/></td>                          <!--<td>Cantidad de empleados</td>-->
                <td><input class="form-control" type="number" name="endeudamiento_sist_finan" min="0"/></td>            <!--<td>Endeudamiento total de la empresa con el sistema financiero al momento del otorgamiento</td>-->
                <td><input class="form-control" type="number" name="edeudamiento_banco" min="0"/></td>                  <!--<td>Endeudamiento de la empresa con el Banco al momento del otorgamiento</td>-->
                <td><input class="form-control" type="number" name="nro" required min="0"/></td>                               <!--<td>Numero de prestamo</td>-->
                <td><input class="form-control" type="number" name="cap" required min="0"/></td>                               <!--<td>Capital acreditado</td>-->
                <td><input class="form-control calendar" type="text" name="fecha_acredita" required/></td>                      <!--<td>Fecha de acreditacion del prestamo</td>-->
                <td><select class="form-control" name="destino"/>
                    <option value="" disabled selected></option>
                    {destinos}
                    <option value="{id}">{destino}</option>
                    {/destinos}
                    </select>
                </td>                             <!--<td>Destino de los fondos</td>-->
                <td><input class="form-control" type="number" name="plazo_meses" required min="0"/></td>                       <!--<td>Plazo total del prestamo (en meses)</td>-->
                <td><input class="form-control" type="number" name="tna" min="0"/></td>                                 <!--<td>Tasa de interes cobrada en las Micro, P/M Empresas, extpresada como Tasa Nominal Anual Vencida (en %)</td>-->
                <td><select class="form-control" name="sistema_amort" required/>
                    <option value="" disabled selected></option>
                    {sis_amortizacion}
                    <option value="{id}">{nombre}</option>
                    {/sis_amortizacion}
                    </select>
                </td>                       <!--<td>Sistema de amortizacion</td>-->
                <td><input class="form-control" type="number" name="cant_cuot" required min="0"/></td>                         <!--<td>Cantidad de cuotas</td>-->
                <td><input class="form-control" type="number" name="" min="0"/></td>                        <!--<td>Monto de la primera cuota</td>-->
                <td><input class="form-control calendar" type="text" name="fecha_1er_vencimiento" required/></td>               <!--<td>Fecha del primer vencimiento capital</td>-->
                <td><input class="form-control calendar" type="text" name="fecha_1er_venc_interes" required/></td>              <!--<td>Fecha del primer vencimiento interes</td>-->
                <td><input class="form-control" type="number" name="gracia_cap" required min="0"/></td>                          <!--<td>Gracia de capital (en meses)</td>-->
                <td><input class="form-control" type="number" name="gracia_int" min="0"/></td>                          <!--<td>Gracia de interes (en meses)</td>-->
                <td><input class="form-control" type="number" name="frec_cap" required min="0"/></td>                            <!--<td>Frecuencia de los servicios de capital</td>-->
                <td><input class="form-control" type="number" name="frec_int" required min="0"/></td>                            <!--<td>Frecuencia de los servicios de interes</td>-->
                <td>
                    <input class="form-control" type="radio" name="joven_empresario" value="1" />Si
                    <input class="form-control" type="radio" name="joven_empresario" value="0" required/>No
                </td>                    <!--<td>Joven Empresario</td> SI/NO-->
                <td>
                    <input class="form-control" type="radio" name="cliente_nuevo" value="1" required/>Si
                    <input class="form-control" type="radio" name="cliente_nuevo" value="0" required/>No
                </td>                       <!--<td>Cliente Nuevo</td> SI/NO-->
                <td>
                    <input class="form-control" type="radio" name="garantia_sgr" value="1" required/>Si
                    <input class="form-control" type="radio" name="garantia_sgr" value="0" required/>No
                </td>                        <!--<td>Garantia SGR</td> SI/NO-->
                <td><input class="form-control" type="text" name="sgr_involucrada"/></td>                     <!--<td>SGR involucrada</td>-->
                <td><input class="form-control" type="text" name="garantia"/></td>                            <!--<td>Garantia</td>-->
                <td><input class="form-control" type="text" name="observaciones"/></td>                       <!--<td>Observaciones</td>-->
                <td>
                    <select class="form-control chosen" name="dispo" required/>
                        <option value="" disabled selected></option>
                        {resoluciones}
                        <option value="{id}">{resolucion}</option>
                        {/resoluciones}
                    </select>
                </td>                               <!--<td>Resolucion</td>-->
            </tr>
        </table>
        <div class="form-group">
            <button id="agregar" class="btn">Agregar</button>
            <button id="quitar" class="btn">Quitar</button>
            <input type="submit" value="Enviar" class="btn btn-default"/>
        </div>
    </form>
</div>