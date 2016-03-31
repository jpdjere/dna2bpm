CREATE
 ALGORITHM = UNDEFINED
 DEFINER = root@localhost
 SQL SECURITY DEFINER
 VIEW `xRubro_master`
 AS 

select distinct
    `ahora13`.`master`.`Marca` AS `TARJETA`,
    count(distinct `ahora13`.`master`.`CUIT del Comercio`) AS `CUITS`,
    count(distinct `ahora13`.`master`.`Nombre de Fantasia`) AS `LOCALES`,
    sum(`ahora13`.`master`.`Monto de la Venta`) AS `MONTO_VENTAS`,
    count(0) AS `OPERACIONES`,Rubro
    
from `ahora13`.`master`
    
    
group by `ahora13`.`master`.`Marca` , `ahora13`.`master`.`Rubro`