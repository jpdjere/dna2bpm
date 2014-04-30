<!DOCTYPE html>
<html>
    <head>
        <title>DNA&sup2; | SGR | {sgr_nombre}</title>
        <meta charset="UTF-8" />
        <link rel="stylesheet" href="{base_url}jscript/bootstrap/css/bootstrap.min.css" />
        <link rel="stylesheet" href="{base_url}jscript/bootstrap/css/bootstrap-responsive.min.css" />
        <link rel="stylesheet" href="{module_url}assets/css/font-awesome-4.0.3/css/font-awesome.min.css" />
        <link rel="stylesheet" href="{module_url}assets/css/sgr.css" />
        <link rel="stylesheet" href="{module_url}assets/css/print.css" />
    </head>
    <body>
        <div class="container" > 
            <div class="navbar navbar-inverse navbar-static-top ">
                <div id="header">
                    <div id="header-dna"></div>
                    <div id="header-logos"></div>
                </div>
                <div class="row-fluid" align="center">                    

                    <h2>{sgr_nombre}</h2>
                    <h4>Declaración Jurada sobre la Presentación de los Anexos 12, 13, 14, 15 y 16</h4>
                    <h3>PER&Iacute;ODO: {print_period}</h3>
                </div>

                <p>Por medio de la presente y en carácter de Declaración Jurada, manifiesto que la información contenida en los archivos detallados a continuación reflejan fielmente la actividad desarrollada por la Sociedad durante el periodo de referencia. </p>

                <div class="table_partners" id="T1">
                    <table>
                        <tr class="no_border_table">
                            <td rowspan="2"></td>
                            <td colspan="4">cantidad socios</td>
                            <td rowspan="5"></td>
                            <td colspan="4">cantidad acciones</td>
                            <td rowspan="5"></td>
                            <td colspan="4">monto de acciones</td>
                        </tr>
                        <tr>
                            <th>al inicio del periodo</th>
                            <th>incorporados</th>
                            <th>desvinculados</th>
                            <th>al final del periodo</th>
                            <th>al inicio del periodo </th>
                            <th>compras</th>
                            <th>ventas</th>
                            <th>al final del periodo</th>
                            <th>al inicio del periodo</th>
                            <th>compras</th>
                            <th>ventas </th>
                            <th>al final del periodo</th>
                        </tr>
                        <tr class="custom_border_table">
                            <th>participes</th>
                            <td>{t1_1}</td>
                            <td>{t1_2}</td>
                            <td>{t1_3}</td>
                            <td>{t1_4}</td>
                            <td>{t1_5}</td>
                            <td>{t1_6}</td>
                            <td>{t1_7}</td>
                            <td>{t1_8}</td>
                            <td>{t1_9}</td>
                            <td>{t1_10}</td>
                            <td>{t1_11}</td>
                            <td>{t1_12}</td>
                        </tr>
                        <tr class="custom_border_table">
                            <th>protectores</th>
                            <td>{t1_13}</td>
                            <td>{t1_14}</td>
                            <td>{t1_15}</td>
                            <td>{t1_16}</td>
                            <td>{t1_17}</td>
                            <td>{t1_18}</td>
                            <td>{t1_19}</td>
                            <td>{t1_20}</td>
                            <td>{t1_21}</td>
                            <td>{t1_22}</td>
                            <td>{t1_23}</td>
                            <td>{t1_24}</td>
                        </tr>
                        <tr class="custom_border_table">
                            <th>total</th>
                            <td>{t1_25}</td>
                            <td>{t1_26}</td>
                            <td>{t1_27}</td>
                            <td>{t1_28}</td>
                            <td>{t1_29}</td>
                            <td>{t1_30}</td>
                            <td>{t1_31}</td>
                            <td>{t1_32}</td>
                            <td>{t1_33}</td>
                            <td>{t1_34}</td>
                            <td>{t1_35}</td>
                            <td>{t1_36}</td>
                        </tr>
                    </table>

                </div>
                <ul>
                    <li>Anexo 12 - Garantías Otorgadas<li>
                        Archivo Importado: <u>{f_12}</u>
                    <li>Anexo 12.1 – Garantías con Sistema de Amortización “Otro”</li>
                    Archivo Importado: <u>{f_121}</u>
                    <li>Anexo 12.2 – Cancelaciones Anticipadas de Garantías</li>
                    Archivo Importado: <u>{f_122}</u>
                    <li>Anexo 12.3 – Saldos diarios de Garantías Comerciales, Futuros y Opciones, etc. </li>
                    Archivo Importado: {f_123}</u>
                    <li>Anexo 12.4 - Garantías Reafianzadas</li>
                    Archivo Importado: <u>{f_124}</u>
                    <li>Anexo 12.5 – Saldo de Garantías Vigentes por Pyme y por Acreedor</li>
                    Archivo Importado: <u>{f_125}</u>
                </ul>

                <div id="T2" class="table_partners">
                    <table>
                        <tr>
                            <th rowspan="2">cantidad de pymes asistidas en el periodo</th>
                            <th colspan="2">garantias otorgadas</th>
                            <th rowspan="2">comisiones devengadas por garantias otorgadas </th>
                            <th colspan="3">garantias vigentes al ultimo dia del periodo</th>
                            <th colspan="2">garantias refianzadas</th>
                        </tr>
                        <tr>
                            <th>cantidad</th>
                            <th>monto </th>
                            <th>pymes</th>
                            <th>garantias</th>
                            <th>saldo </th>
                            <th>cantidad</th>
                            <th>monto </th>
                        </tr>
                        <tr>
                            <td>{t2_1}</td>
                            <td>{t2_2}</td>
                            <td>{t2_3}</td>
                            <td>{t2_4}</td>
                            <td>{t2_5}</td>
                            <td>{t2_6}</td>
                            <td>{t2_7}</td>
                            <td>{t2_8}</td>
                            <td>{t2_9}</td>
                        </tr>
                    </table>

                </div>

                <ul>
                    <li>Anexo 13 - Cumplimiento Irregular de Socios Partícipes </li>
                    Archivo Importado: <u>{f_13}</u>

                </ul>

                <div id="T3" class="table_partners">
                    <table>
                        <tr  class="no_border_table">
                            <td></td>
                            <th>menor 90 dias</th>
                            <th>menor 180 dias </th>
                            <th>menor 365 dias</th>
                            <th>mayor 365 dias </th>
                            <th>total</th>
                            <th>valor contragarantias </th>
                        </tr>
                        <tr>
                            <td>total</td>
                            <td>{t3_1}</td>
                            <td>{t3_2}</td>
                            <td>{t3_3}</td>
                            <td>{t3_4}</td>
                            <td>{t3_5}</td>
                            <td>{t3_6}</td>
                        </tr>
                    </table>
                </div>

                <ul>
                    <li>Anexo 14 - Fondo de Riesgo Contingente</li>
                    Archivo Importado: <u>{f_14}</u>
                </ul>
                
                <ul>
                    <li>Anexo 14.1 - SGR Situación consolidada por Socio Partícipe</li>
                    Archivo Importado: <u>{f_141}</u>
                </ul>
                <div id="T4" class="table_partners">
                    <table>
                        <tr>
                            <th colspan="16">evolucion del fondo de riesgo contingente <br></th>
                        </tr>
                        <tr>
                            <th rowspan="3">cantidad de soc. part. deudores al inicio del periodo <br></th>
                            <th rowspan="3">saldo inicial <br></th>
                            <th colspan="6">garantias afrontadas<br></th>
                            <th colspan="6">gastos por gestion de recuperos<br></th>
                            <th rowspan="3">saldo final<br></th>
                            <th rowspan="3">cantidad de soc. part. deudores al final del periodo <br></th>
                        </tr>
                        <tr>
                            <th colspan="2">caidas</th>
                            <th colspan="2">recuperos</th>
                            <th colspan="2">incobrables</th>
                            <th colspan="2">caidas <br></th>
                            <th colspan="2">recuperos</th>
                            <th colspan="2">incobrables</th>
                        </tr>
                        <tr>
                            <th>cantidad</th>
                            <th>monto <br></th>
                            <th>cantidad</th>
                            <th>monto </th>
                            <th>cantidad</th>
                            <th>monto </th>
                            <th>cantidad</th>
                            <th>monto </th>
                            <th>cantidad</th>
                            <th>monto </th>
                            <th>cantidad</th>
                            <th>monto <br></th>
                        </tr>
                        <tr>
                            <td>{t4_1}</td>
                            <td>{t4_2}</td>
                            <td>{t4_3}</td>
                            <td>{t4_4}</td>
                            <td>{t4_5}</td>
                            <td>{t4_6}</td>
                            <td>{t4_7}</td>
                            <td>{t4_8}</td>
                            <td>{t4_9}</td>
                            <td>{t4_10}</td>
                            <td>{t4_11}</td>
                            <td>{t4_12}</td>
                            <td>{t4_13}</td>
                            <td>{t4_14}</td>
                            <td>{t4_15}</td>
                            <td>{t4_16}</td>
                        </tr>
                    </table>
                </div>

                <ul>
                    <li>Anexo 15 - Inversión del Fondo de Riesgo</li>
                    Archivo Importado: <u>{f_15}</u>
                </ul>
                <div id="T5" class="table_partners">
                    <table>
                        <tr>
                            <th colspan="5">inversiones</th>
                        </tr>
                        <tr>
                            <th>inciso del art. 25</th>
                            <th>en pesos</th>
                            <th>en moneda extangera(*)</th>
                            <th>total</th>
                            <th>%</th>
                        </tr>
                        <tr>
                            <td>a</td>
                            <td>{t5_1}</td>
                            <td>{t5_2}</td>
                            <td>{t5_3}</td>
                            <td>{t5_4}</td>
                        </tr>
                        <tr>
                            <td>b</td>
                            <td>{t5_5}</td>
                            <td>{t5_6}</td>
                            <td>{t5_7}</td>
                            <td>{t5_8}</td>
                        </tr>
                        <tr>
                            <td>c</td>
                            <td>{t5_9}</td>
                            <td>{t5_10}</td>
                            <td>{t5_11}</td>
                            <td>{t5_12}</td>
                        </tr>
                        <tr>
                            <td>d</td>
                            <td>{t5_13}</td>
                            <td>{t5_14}</td>
                            <td>{t5_15}</td>
                            <td>{t5_16}</td>
                        </tr>
                        <tr>
                            <td>e</td>
                            <td>{t5_17}</td>
                            <td>{t5_18}</td>
                            <td>{t5_19}</td>
                            <td>{t5_20}</td>
                        </tr>
                        <tr>
                            <td>f</td>
                            <td>{t5_21}</td>
                            <td>{t5_22}</td>
                            <td>{t5_23}</td>
                            <td>{t5_24}</td>
                        </tr>
                        <tr>
                            <td>g</td>
                            <td>{t5_25}</td>
                            <td>{t5_26}</td>
                            <td>{t5_27}</td>
                            <td>{t5_28}</td>
                        </tr>
                        <tr>
                            <td>h</td>
                            <td>{t5_29}</td>
                            <td>{t5_30}</td>
                            <td>{t5_31}</td>
                            <td>{t5_32}</td>
                        </tr>
                        <tr>
                            <td>i</td>
                            <td>{t5_33}</td>
                            <td>{t5_34}</td>
                            <td>{t5_35}</td>
                            <td>{t5_36}</td>
                        </tr>
                         <tr>
                            <td>j</td>
                            <td>{t5_37}</td>
                            <td>{t5_38}</td>
                            <td>{t5_39}</td>
                            <td>{t5_40}</td>
                        </tr>
                         <tr>
                            <td>k</td>
                            <td>{t5_41}</td>
                            <td>{t5_42}</td>
                            <td>{t5_43}</td>
                            <td>{t5_44}</td>
                        </tr>
                        <tr>
                            <td colspan="3"><strong>Totales</strong></td>
                            <td><strong>{t5_45}</strong></td>
                            <td><strong>{t5_46}</strong></td>
                        </tr>
                    </table>
                </div>

                <ul>
                    <li>Anexo 16 - Grado de Utilización del Fondo de Riesgo</li>
                    Archivo Importado: <u>{f_16}</u>
                </ul>

                <div id="T6" class="table_partners">
                    <table>
                        <tr>
                            <th colspan="6">saldos promedios mensuales</th>
                            <th colspan="3">grados de utilizacion</th>
                        </tr>
                        <tr>
                            <th>garantias vigentes</th>
                            <th>garantias vigentes que computan para el 80%</th>
                            <th>garantias vigentes que computan para el 120% </th>
                            <th>fondo riesgo total computable</th>
                            <th>contingente</th>
                            <th>fondo de riesgo disponible</th>
                            <th>solvencia </th>
                            <th>80%</th>
                            <th>120% </th>
                        </tr>
                        <tr>
                            <td>{t6_1}</td>
                            <td>{t6_2}</td>
                            <td>{t6_3}</td>
                            <td>{t6_4}</td>
                            <td>{t6_5}</td>
                            <td>{t6_6}</td>
                            <td>{t6_7}</td>
                            <td>{t6_8}</td>
                            <td>{t6_9}</td>
                        </tr>
                    </table>
                </div>

                <p>OBSERVACIONES: {observations}</p>
                <p><small>(*) El presente Anexo será generado automáticamente por el Sistema una vez finalizada la carga de los Anexos 12, 13, 14, 15 y 16 y a pedido del usuario. Al solicitar su generación, se le abrirán dos pantallas, en la primera deberá indicar el monto de las comisiones por otorgamiento de garantías devengado durante el período informado, en la segunda podrá realizar las observaciones particulares que considere pertinentes respecto de la información presentada correspondiente al período que se está informando.</small></p>
                <div class="sign"><p>Firma y Aclaración del Presidente o Apoderado de la Sociedad de Garantía 		Recíproca</p></div>
                <!--{show_table}-->
            </div>
        </div>
    </body>
</html>

