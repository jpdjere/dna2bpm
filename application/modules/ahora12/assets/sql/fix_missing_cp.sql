-- insert into cod_postales (CODIGO_POSTAL,DETALLE_LOCALIDAD,ID_PROV_SUR) 
select `visa`.`Codigo_Postal4`, visa.Nombre_localidad, provincias.id_prov from visa JOIN provincias on provincias.detalle_prov=visa.Desc_Provincia
where  `visa`.`Codigo_Postal4` not in (select CODIGO_POSTAL from cod_postales)
group by Codigo_Postal4