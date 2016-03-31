CREATE
 ALGORITHM = UNDEFINED
 DEFINER = root@localhost
 SQL SECURITY DEFINER
 VIEW `xProvincia_master`
 AS

 SELECT 
        'MasterCard' AS `TARJETA`,
        COUNT(DISTINCT `master`.`CUIT del Comercio`) AS `CUITS`,
        COUNT(DISTINCT `master`.`Nombre de Fantasia`) AS `LOCALES`,
        SUM(`master`.`Monto de la Venta`) AS `MONTO_VENTAS`,
        COUNT(0) AS `OPERACIONES`
        ,`provincias`.`id_prov` AS `id_prov`,
        `provincias`.`detalle_prov` AS `detalle_prov`,
        `provincias`.`poblacion` AS `poblacion`
    FROM
       ((`ahora13`.`master`
    JOIN `ahora13`.`cod_postales` ON ((`ahora13`.`cod_postales`.`CODIGO_POSTAL` = `ahora13`.`master`.`Código Postal`)))
    JOIN `ahora13`.`provincias` ON ((`ahora13`.`provincias`.`id_prov` = `ahora13`.`cod_postales`.`ID_PROV_SUR`)))
GROUP BY `ahora13`.`cod_postales`.`ID_PROV_SUR`