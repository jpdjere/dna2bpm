select 
    'American Express' AS `TARJETA`,
    count(distinct `ahora13`.`amex`.`CUIT`) AS `CUITS`,
    count(distinct `ahora13`.`amex`.`Nombre`) AS `LOCALES`,
    sum(`ahora13`.`amex`.`Monto`) AS `MONTO_VENTAS`,
    count(0) AS `OPERACIONES`,
    `ahora13`.`amex`.`Rubro` AS `Rubro`
from
    `ahora13`.`amex`
group by `ahora13`.`amex`.`Rubro`