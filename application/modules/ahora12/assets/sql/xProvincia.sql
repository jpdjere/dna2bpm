SELECT date(MAX(STR_TO_DATE(`Fecha_Movimiento`, '%d/%m/%Y'))) FROM visa INTO @corte;
SELECT 
    CORTE,
    SUM(`CUITS`) AS `CUITS`,
    SUM(`LOCALES`) AS `LOCALES`,
    SUM(`MONTO_VENTAS`) AS `MONTO_VENTAS`,
    SUM(`OPERACIONES`) AS `OPERACIONES`,
    `ahora13`.`xProvincia`.`id_prov` AS `id_prov`,
    `ahora13`.`xProvincia`.`detalle_prov` AS `detalle_prov`
    
    FROM ahora13.xProvincia
    WHERE CORTE=@corte
    GROUP BY id_prov