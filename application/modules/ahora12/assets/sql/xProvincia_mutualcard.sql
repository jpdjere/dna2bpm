CREATE
 ALGORITHM = UNDEFINED
 DEFINER = root@localhost
 SQL SECURITY DEFINER
 VIEW `xProvincia_mutualcard`
 AS

 SELECT 
        'MUTUALCARD' AS `TARJETA`,
        COUNT(DISTINCT `mutualcard`.`CUIT`) AS `CUITS`,
        COUNT(DISTINCT `mutualcard`.`Nombre o Razon Social`) AS `LOCALES`,
        SUM(`mutualcard`.`Monto Vta.`) AS `MONTO_VENTAS`,
        COUNT(0) AS `OPERACIONES`
        ,`provincias`.`id_prov` AS `id_prov`,
        `provincias`.`detalle_prov` AS `detalle_prov`,
        `provincias`.`poblacion` AS `poblacion`
    FROM
        `mutualcard`
    INNER JOIN `ahora13`.`cod_postales` ON (`ahora13`.`cod_postales`.`CODIGO_POSTAL` =TRIM(`ahora13`.`mutualcard`.`C.P.`))
    INNER JOIN `ahora13`.`provincias` ON (`ahora13`.`provincias`.`id_prov` = `ahora13`.`cod_postales`.`ID_PROV_SUR`)
GROUP BY `ahora13`.`cod_postales`.`ID_PROV_SUR`
