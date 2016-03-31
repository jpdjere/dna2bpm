CREATE 
    ALGORITHM = UNDEFINED 
    DEFINER = `root`@`localhost` 
    SQL SECURITY DEFINER
VIEW `xRubro_montemarShopping` AS
    select 
        'TARJETA MONTEMAR SHOPPING' AS `TARJETA`,
        count(distinct `montemarShopping`.`CUIT`) AS `CUITS`,
        count(distinct `montemarShopping`.`NOMBRE DE FANTASIA`) AS `LOCALES`,
        sum(`montemarShopping`.`Monto`) AS `MONTO_VENTAS`,
        count(0) AS `OPERACIONES`,RUBRO as Rubro
        
    from `montemarShopping`
    group by Rubro