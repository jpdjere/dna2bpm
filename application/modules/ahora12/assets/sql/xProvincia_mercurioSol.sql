CREATE
 ALGORITHM = UNDEFINED
 DEFINER = root@localhost
 SQL SECURITY DEFINER
 VIEW `xProvincia_mercurioSol`
 AS

 SELECT 
        'TARJETA SOL' AS `TARJETA`,
        COUNT(DISTINCT `mercurioSol`.`Cuit`) AS `CUITS`,
        COUNT(DISTINCT `mercurioSol`.`NombFantasia`) AS `LOCALES`,
        SUM(`mercurioSol`.`Importe_vta`) AS `MONTO_VENTAS`,
        COUNT(0) AS `OPERACIONES`
    FROM
        `mercurioSol`
    INNER JOIN `ahora13`.`cod_postales` ON (`ahora13`.`cod_postales`.`CODIGO_POSTAL` =TRIM(`ahora13`.`mercurioSol`.`CP`))
    INNER JOIN `ahora13`.`provincias` ON (`ahora13`.`provincias`.`id_prov` = `ahora13`.`cod_postales`.`ID_PROV_SUR`)
GROUP BY `ahora13`.`cod_postales`.`ID_PROV_SUR`
