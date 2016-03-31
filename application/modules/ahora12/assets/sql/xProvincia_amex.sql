CREATE
 ALGORITHM = UNDEFINED
 DEFINER = root@localhost
 SQL SECURITY DEFINER
 VIEW `xProvincia_amex`
 AS 

SELECT 
    'American Express' AS `TARJETA`,
    COUNT(DISTINCT `ahora13`.`amex`.`CUIT`) AS `CUITS`,
    COUNT(DISTINCT `ahora13`.`amex`.`Nombre`) AS `LOCALES`,
    SUM(`ahora13`.`amex`.`Monto`) AS `MONTO_VENTAS`,
    COUNT(0) AS `OPERACIONES`,
    `ahora13`.`provincias`.`id_prov` AS `id_prov`,
    `ahora13`.`provincias`.`detalle_prov` AS `detalle_prov`,
    `ahora13`.`provincias`.`poblacion` AS `poblacion`
FROM
    ((`ahora13`.`amex`
    JOIN `ahora13`.`cod_postales` ON ((`ahora13`.`cod_postales`.`CODIGO_POSTAL` = `ahora13`.`amex`.`Código Postal`)))
    JOIN `ahora13`.`provincias` ON ((`ahora13`.`provincias`.`id_prov` = `ahora13`.`cod_postales`.`ID_PROV_SUR`)))
GROUP BY `ahora13`.`cod_postales`.`ID_PROV_SUR`