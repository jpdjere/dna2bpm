CREATE 
    ALGORITHM = UNDEFINED 
    DEFINER = root@localhost 
    SQL SECURITY DEFINER
VIEW `xRubro_cabal` AS
    SELECT 
        'CABAL' AS `TARJETA`,
        count(distinct `ahora13`.`cabal`.`CUIT`) AS `CUITS`,
        count(distinct `ahora13`.`cabal`.`NOMBREFANTASIA`) AS `LOCALES`,
        sum(`ahora13`.`cabal`.`IMPORTE`) AS `MONTO_VENTAS`,
        count(0) AS `OPERACIONES`,
        RUBRO as `Rubro`
    FROM
        `ahora13`.`cabal`
    GROUP BY RUBRO