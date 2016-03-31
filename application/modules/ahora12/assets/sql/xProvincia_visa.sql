CREATE
 ALGORITHM = UNDEFINED
 DEFINER = root@localhost
 SQL SECURITY DEFINER
 VIEW `xProvincia_visa`
 AS

  SELECT
        'VISA' AS `TARJETA`,
        COUNT(DISTINCT `visa`.`CUIT`) AS `CUITS`,
        COUNT(DISTINCT `visa`.`Nombre_Fantasia`) AS `LOCALES`,
        SUM(`visa`.`Monto_Movimiento`) AS `MONTO_VENTAS`,
        COUNT(0) AS `OPERACIONES`
        ,`provincias`.`id_prov` AS `id_prov`,
        `provincias`.`detalle_prov` AS `detalle_prov`,
        `provincias`.`poblacion` AS `poblacion`
    FROM
        `visa`
    INNER JOIN `ahora13`.`cod_postales` ON (`ahora13`.`cod_postales`.`CODIGO_POSTAL` =TRIM(`ahora13`.`visa`.`Codigo_Postal4`))
    INNER JOIN `ahora13`.`provincias` ON (`ahora13`.`provincias`.`id_prov` = `ahora13`.`cod_postales`.`ID_PROV_SUR`)
GROUP BY `ahora13`.`cod_postales`.`ID_PROV_SUR`
