CREATE 
    ALGORITHM = UNDEFINED 
    DEFINER = `root`@`localhost` 
    SQL SECURITY DEFINER
VIEW `xRubro_mutualcard` AS
    select 
        'MUTUALCARD' AS `TARJETA`,
        count(distinct `mutualcard`.`CUIT`) AS `CUITS`,
        count(distinct `mutualcard`.`Nombre o Razon Social`) AS `LOCALES`,
        sum(`mutualcard`.`Monto Vta.`) AS `MONTO_VENTAS`,
        count(0) AS `OPERACIONES`,Rubro
        
    from
        `mutualcard`
    group by Rubro