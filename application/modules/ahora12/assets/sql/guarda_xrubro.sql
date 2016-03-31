SELECT date(MAX(STR_TO_DATE(`Fecha_Movimiento`, '%d/%m/%Y'))) FROM visa INTO @corte;
INSERT INTO xRubro (CORTE,TARJETA,CUITS,LOCALES,MONTO_VENTAS,OPERACIONES,RUBRO)
SELECT @corte as CORTE,xRubro_amex.* FROM xRubro_amex
UNION
SELECT @corte,xRubro_cabal.* FROM xRubro_cabal
UNION
SELECT @corte,xRubro_master.* FROM xRubro_master
UNION
SELECT @corte,xRubro_mercurioSol.* FROM xRubro_mercurioSol
UNION
SELECT @corte,xRubro_montemarShopping.* FROM xRubro_montemarShopping
UNION
SELECT @corte,xRubro_mutualcard.* FROM xRubro_mutualcard
UNION
SELECT @corte,xRubro_visa.* FROM xRubro_visa