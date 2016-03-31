CREATE
 ALGORITHM = UNDEFINED
 DEFINER = root@localhost
 SQL SECURITY DEFINER
 VIEW `xRubro_visa`
 AS

  SELECT
        'VISA' AS `TARJETA`,
        COUNT(DISTINCT `visa`.`CUIT`) AS `CUITS`,
        COUNT(DISTINCT `visa`.`Nombre_Fantasia`) AS `LOCALES`,
        SUM(`visa`.`Monto_Movimiento`) AS `MONTO_VENTAS`,
        COUNT(0) AS `OPERACIONES`,Desc_Rubro as Rubro
        
    FROM
        `visa`    
GROUP BY Rubro
