select STR_TO_DATE(
	CONCAT( YEAR(fecha),WEEK(fecha),' TUESDAY')
	,'%X%V %W') as CORTE, sum(monto),count(monto),tarjeta.detalle
from ticket INNER JOIN tarjeta ON tarjeta.id_tarjeta=ticket.id_tarjeta
group by WEEK(fecha),ticket.id_tarjeta LIMIT 10000