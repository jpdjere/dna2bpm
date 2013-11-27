<?php

class Model_06 {
    /* VALIDADOR ANEXO 06 */

    public function __construct() {
        
    }

    function save($parameter) {


        /**
         *   Funcion ...
         * 
         * @param 
         * @type PHP
         * @name ...
         * @author Diego
         *
         * @example ....
         * */
        $defdna = array(
            1 => 5779, //Tipo operacion
            2 => 5272, //Tipo socio SGR
            3 => 1695, //Cuit
            4 => 1693, //Nombre
            5 => 5282, //Cuit SGR
            6 => 4651, //Provincia
            7 => 1699, //"PARTIDO_MUNICIPIO_COMUNA",
            8 => 1700, //Localidad/Ciudad
            9 => 1698, //Codigo Postal
            10 => 4653, //Calle
            11 => 4654, //Nro
            12 => 4655, //Piso
            13 => 4656, //Departamento oficina
            14 => 1701, //Telefono1
            15 => 5189, //Telefono2
            16 => 1703, //E_Mail
            17 => 1704, //Web
            18 => 5208, //Codigo Actividad (ClaNAE)
//18 => 4872, //Sector de actividad
//19=>9999,  //Promedio
//29 => 4863, //Tipo Empresa
            19 => 19, //"ANIO",
            20 => 20, //"MONTO",
            21 => 21, //"TIPO_ORIGEN",
            22 => 22, //"ANIO2",
            23 => 23, //"MONTO2",
            24 => 24, // "TIPO_ORIGEN2",
            25 => 25, //"ANIO3",
            26 => 26, //"MONTO3",
            27 => 27, //"TIPO ORIGEN3",
            28 => 5596, //Rgimen ante AFIP
            30 => 5253, //Tipo acta
            31 => 5255, //Fecha acta
            32 => 5254, //Acta nro.
            33 => 33, //Acta nro.
            34 => 5252, //Modalidad
            35 => 5597, //Capital suscripto 
            36 => 5250, //Acciones suscriptas
            37 => 5598, //Capital Integrado
            38 => 5251, //Acciones integradas
            39 => 5248, //CUIT Socio Cedente
            40 => 5249, //Nombre Socio Cedente
            41 => 5292 //Caracterstica Socio Cedente
        );

        $insertarr = array();
        foreach ($defdna as $key => $value) {
            $insertarr[$value] = $parameter[$key];
        }


        $this->debug($insertarr);
    }

    function debug($parameter) {
        return "<pre>" . var_dump($parameter) . "</pre><hr>";
    }

}
