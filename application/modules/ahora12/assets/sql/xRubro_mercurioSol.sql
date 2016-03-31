CREATE
 ALGORITHM = UNDEFINED
 DEFINER = root@localhost
 SQL SECURITY DEFINER
 VIEW `xRubro_mercurioSol`
 AS

 SELECT 
        'TARJETA SOL' AS `TARJETA`,
        COUNT(DISTINCT `mercurioSol`.`Cuit`) AS `CUITS`,
        COUNT(DISTINCT `mercurioSol`.`NombFantasia`) AS `LOCALES`,
        SUM(`mercurioSol`.`Importe_vta`) AS `MONTO_VENTAS`,
        COUNT(0) AS `OPERACIONES`, Rubro
    FROM
        `mercurioSol`
    
GROUP BY `ahora13`.`mercurioSol`.`Rubro`
