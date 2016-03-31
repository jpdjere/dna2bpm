SELECT date(MAX(STR_TO_DATE(`Fecha_Movimiento`, '%d/%m/%Y'))) FROM visa INTO @corte;
INSERT INTO sumas (CORTE,TARJETA,CUITS,LOCALES,MONTO_VENTAS,OPERACIONES)
SELECT @corte as CORTE,suma_amex.* FROM suma_amex
UNION
SELECT @corte,suma_cabal.* FROM suma_cabal
UNION
SELECT @corte,suma_master.* FROM suma_master
UNION
SELECT @corte,suma_mercurio.* FROM suma_mercurio
UNION
SELECT @corte,suma_montemar.* FROM suma_montemar
UNION
SELECT @corte,suma_mutualcard.* FROM suma_mutualcard
UNION
SELECT @corte,suma_visa.* FROM suma_visa