SELECT date(MAX(STR_TO_DATE(`Fecha_Movimiento`, '%d/%m/%Y'))) FROM visa INTO @corte;
INSERT INTO xProvincia (CORTE,TARJETA,CUITS,LOCALES,MONTO_VENTAS,OPERACIONES,id_prov,detalle_prov,poblacion)
SELECT @corte as CORTE,xProvincia_amex.* FROM xProvincia_amex
UNION
SELECT @corte,xProvincia_cabal.* FROM xProvincia_cabal
UNION
SELECT @corte,xProvincia_master.* FROM xProvincia_master
UNION
SELECT @corte,xProvincia_mercurioSol.* FROM xProvincia_mercurioSol
UNION
SELECT @corte,xProvincia_montemarShopping.* FROM xProvincia_montemarShopping
UNION
SELECT @corte,xProvincia_mutualcard.* FROM xProvincia_mutualcard
UNION
SELECT @corte,xProvincia_visa.* FROM xProvincia_visa
