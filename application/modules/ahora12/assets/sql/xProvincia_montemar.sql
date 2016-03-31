CREATE
 ALGORITHM = UNDEFINED
 DEFINER = root@localhost
 SQL SECURITY DEFINER
 VIEW `xProvincia_montemarShopping`
 AS

 SELECT
        'TARJETA MONTEMAR SHOPPING' AS `TARJETA`,
        COUNT(DISTINCT `montemarShopping`.`CUIT`) AS `CUITS`,
        COUNT(DISTINCT `montemarShopping`.`NOMBRE DE FANTASIA`) AS `LOCALES`,
        SUM(`montemarShopping`.`Monto`) AS `MONTO_VENTAS`,
        COUNT(0) AS `OPERACIONES`
        ,`provincias`.`id_prov` AS `id_prov`,
        `provincias`.`detalle_prov` AS `detalle_prov`,
        `provincias`.`poblacion` AS `poblacion`
    FROM
        `montemarShopping`
    INNER JOIN `ahora13`.`cod_postales` ON (`ahora13`.`cod_postales`.`CODIGO_POSTAL` =TRIM(`ahora13`.`montemarShopping`.`CODIGO POSTAL`))
    INNER JOIN `ahora13`.`provincias` ON (`ahora13`.`provincias`.`id_prov` = `ahora13`.`cod_postales`.`ID_PROV_SUR`)
GROUP BY `ahora13`.`cod_postales`.`ID_PROV_SUR`
