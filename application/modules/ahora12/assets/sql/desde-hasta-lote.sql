SELECT
DATE_FORMAT(min(STR_TO_DATE(`Fecha_Movimiento`, '%d/%m/%Y')),'%d/%m/%Y') as desde,
DATE_FORMAT(MAX(STR_TO_DATE(`Fecha_Movimiento`, '%d/%m/%Y')),'%d/%m/%Y') as hasta 
FROM visa