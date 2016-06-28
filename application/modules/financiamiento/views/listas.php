<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Documento sin título</title>
</head>

<body>

<?php 
$tipo_sociedad['0']='S.R.L';
$tipo_sociedad['1']='S.A.';
$tipo_sociedad['2']='S. Simple';
$tipo_sociedad['3']='Unipersonal';
$tipo_sociedad['4']='Cooperativa';
$tipo_sociedad['5']='Clúster';
$tipo_sociedad['6']='Unión Transitoria';

$provincia['0']='Buenos Aires';
$provincia['1']='Catamarca';
$provincia['2']='Chaco';
$provincia['3']='Chubut';
$provincia['4']='Cordoba';
$provincia['5']='Corrientes';
$provincia['6']='Entre Rios';
$provincia['7']='Formosa';
$provincia['8']='Jujuy';
$provincia['9']='La Pampa';
$provincia['10']='La Rioja';
$provincia['11']='Mendoza';
$provincia['12']='Misiones';
$provincia['13']='Neuquen';
$provincia['14']='Rio Negro';
$provincia['15']='Salta';
$provincia['16']='San Juan';
$provincia['17']='San Luis';
$provincia['18']='Santa Cruz';
$provincia['19']='Santa Fe';
$provincia['20']='Santiago del Estero';
$provincia['21']='Tierra del Fuego';
$provincia['22']='Tucuman';
$provincia['23']='Ciudad Autónoma de Buenos Aires';


$sector_actividad['0']='Agropecuario';
$sector_actividad['1']='Comercio';
$sector_actividad['2']='Industria y Minería';
$sector_actividad['3']='Servicios';
$sector_actividad['4']='Construcción';


$cat_agropecuario['0']='A. Micro: $2.000.000';
$cat_agropecuario['1']='B. Pequeña: $13.000.000';
$cat_agropecuario['2']='C. Tramo 1: $100.000.000';
$cat_agropecuario['3']='D. Tramo 2: $160.000.000';
$cat_agropecuario['4']='E. Mas de: $160.000.000';

$cat_industria_mineria['0']='A. Micro: $7.500.000';
$cat_industria_mineria['1']='B. Pequeña: $45.500.000';
$cat_industria_mineria['2']='C. Tramo 1: $360.000.000';
$cat_industria_mineria['3']='D. Tramo 2: $540.000.000 ';
$cat_industria_mineria['4']='E. Mas de: $540.000.000';

$cat_comercio['0']='A. Micro: $9.000.000';
$cat_comercio['1']='B. Pequeña: $9.000.000';
$cat_comercio['2']='C. Tramo 1: $55.000.000';
$cat_comercio['3']='D. Tramo 2: $450.000.000';
$cat_comercio['4']='E. Más de: $450.000.000';

$cat_servicios['0']='A. Micro: $2.500.000';
$cat_servicios['1']='B. Pequeña: $15.000.000';
$cat_servicios['2']='C. Tramo 1: $125.000.000';
$cat_servicios['3']='D. Tramo 2: $160.000.000';
$cat_servicios['4']='E.  Mas de: $160.000.000';

$cat_construccion['0']='A. Micro: $3.500.000';
$cat_construccion['1']='B. Pequeña:$22.500.000';
$cat_construccion['2']='C. Tramo 1: $180.000.000';
$cat_construccion['3']='D. Tramo 2: $270.000.000';
$cat_construccion['4']='E. Mas de:$270.000.000';



$si_no[true]='Si';
$si_no[false]='No';

$destino_prestamo['0']='1-Bienes de Capital.';
$destino_prestamo['1']='2-Construcciones e instalaciones.';
$destino_prestamo['2']='3-Capital de Trabajo.';
$destino_prestamo['3']='4- Construcción o adquisición de galpones nuevos o usados para uso industrial.';
$destino_prestamo['4']='5- Gastos de mudanza a parques industriales.';
#$destino_prestamo['5']='6- Pre financiación de exportaciones.';
#$destino_prestamo['6']='7- Post financiación de exportaciones.';
$destino_prestamo['5']='8-Otros.';


$destino_prestamo_fona['0']='1-Bienes de Capital, Construcciones e instalaciones.';
$destino_prestamo_fona['1']='2-Capital de Trabajo.';
$destino_prestamo_fona['2']='3-Otros.';


$sectores_proyecto['0']='1.Automotriz y Autopartista;';
$sectores_proyecto['1']='2.Maquinaria Agrícola; ';
$sectores_proyecto['2']='3.Biotecnología; ';
$sectores_proyecto['3']='4.Industria farmacéutica; ';
$sectores_proyecto['4']='5.Manufacturas especializadas, orientadas a fortalecer diseño y uso de base a mano de obra calificada; ';
$sectores_proyecto['5']='6.Agroindustria; ';
$sectores_proyecto['6']='7.Productos médicos (s/ ANMAT); ';
$sectores_proyecto['7']='8.Software, TICS, Servicios Audiovisual, Serv. Profesionales y Serv. de invest. clínica y serv. KIBS; ';
$sectores_proyecto['8']='9.Industrias Creativas (audiovisual, videojuegos, editorial, artes escénicas); ';
$sectores_proyecto['9']='10.Proveedores (servicios especializados y bienes de capital) para el sector minero, petróleo, gas, e industrias extractivas y energías renovables; ';
$sectores_proyecto['10']='11.Proveedores del sector aeronáutico, aerospacial, naval y ferroviario; ';
$sectores_proyecto['11']='12.Foresto-Industrial, incluyendo muebles, biomasa y dendroenergía.';
$sectores_proyecto['12']='13.Turismo';
$sectores_proyecto['13']='14.Ninguno de los anteriores';


$monto_prestamo_gran['0']='1.Hasta $3 millones de IP';
$monto_prestamo_gran['1']='2.Hasta $1.5 millones de CT';
$monto_prestamo_gran['2']='3.Más de $3 millones de IP o más de $1.5 millones de CT';

$destino_prestamo_gran['0']='CT';
$destino_prestamo_gran['1']='IP';


$banco_rbt['0']='BNA';
$banco_rbt['1']='BICE';

$banco_parques['0']='BNA';

$banco_mi_galpon['0']='BNA';


$todos_bancos['0']='BANCO DE GALICIA Y BUENOS AIRES S.A.';
$todos_bancos['1']=' BANCO DE LA NACIÓN ARGENTINA';
$todos_bancos['0']='BANCO DE LA PROVINCIA DE BUENOS AIRES';
$todos_bancos['1']='INDUSTRIAL AND COMMERCIAL BANK OF CHINA (ARGENTINA) S.A.';
$todos_bancos['2']='CITIBANK N.A.';
$todos_bancos['3']=' BBVA BANCO FRANCÉS S.A.';
$todos_bancos['4']='BANCO DE LA PROVINCIA DE CÓRDOBA S.A.';
$todos_bancos['5']='BANCO SUPERVIELLE S.A.';
$todos_bancos['6']=' BANCO DE LA CIUDAD DE BUENOS AIRES';
$todos_bancos['7']='BANCO PATAGONIA S.A.';
$todos_bancos['8']='BANCO HIPOTECARIO S.A.';
$todos_bancos['9']=' BANCO DE SAN JUAN S.A.';
$todos_bancos['10']='BANCO DEL TUCUMÁN S.A.';
$todos_bancos['11']='BANCO MUNICIPAL DE ROSARIO';
$todos_bancos['12']=' BANCO SANTANDER RÍO S.A.';
$todos_bancos['13']='BANCO DEL CHUBUT S.A.';
$todos_bancos['14']='BANCO DE SANTA CRUZ S.A.';
$todos_bancos['15']='BANCO DE LA PAMPA SOCIEDAD DE ECONOMÍA MIXTA';
$todos_bancos['16']='BANCO DE CORRIENTES S.A.';
$todos_bancos['17']=' BANCO PROVINCIA DEL NEUQUÉN S.A.';
$todos_bancos['18']='HSBC BANK ARGENTINA S.A.';
$todos_bancos['19']='BANCO CREDICOOP COOPERATIVO LIMITADO';
$todos_bancos['20']=' BANCO ITAÚ ARGENTINA S.A.';
$todos_bancos['21']='BANCO PROVINCIA DE TIERRA DEL FUEGO';
$todos_bancos['22']='BANCO MACRO S.A.';
$todos_bancos['23']='BANCO COMAFI S.A.';
$todos_bancos['24']='BANCO DE INVERSIÓN Y COMERCIO EXTERIOR S.A.';
$todos_bancos['25']='NUEVO BANCO DE LA RIOJA S.A.';
$todos_bancos['26']=' NUEVO BANCO DEL CHACO S.A.';
$todos_bancos['27']='BANCO DE FORMOSA S.A.';
$todos_bancos['28']='BANCO DE SANTIAGO DEL ESTERO S.A.';
$todos_bancos['29']='NUEVO BANCO DE SANTA FE S.A.';
$todos_bancos['30']='NUEVO BANCO DE ENTRE RÍOS S.A.';




?>



</body>
</html>