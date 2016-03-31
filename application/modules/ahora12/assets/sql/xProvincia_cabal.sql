CREATE
 ALGORITHM = UNDEFINED
 DEFINER = root@localhost
 SQL SECURITY DEFINER
 VIEW `xProvincia_cabal`
 AS

 SELECT
        'CABAL' AS `TARJETA`,
        COUNT(DISTINCT `cabal`.`CUIT`) AS `CUITS`,
        COUNT(DISTINCT `cabal`.`NOMBREFANTASIA`) AS `LOCALES`,
        SUM(`cabal`.`IMPORTE`) AS `MONTO_VENTAS`,
        COUNT(0) AS `OPERACIONES`
    FROM
        `cabal`
    INNER JOIN `ahora13`.`cod_postales` ON (`ahora13`.`cod_postales`.`CODIGO_POSTAL` =TRIM(`ahora13`.`cabal`.`CODPOSTAL`))
    INNER JOIN `ahora13`.`provincias` ON (`ahora13`.`provincias`.`id_prov` = `ahora13`.`cod_postales`.`ID_PROV_SUR`)
GROUP BY `ahora13`.`cod_postales`.`ID_PROV_SUR`
