<?php

class Lib_06_data {
    /* VALIDADOR ANEXO 06 */

    public function __construct($parameter) {
        /* Vars 
         * 
         * $parameters =  
         * $parameterArr[0]['fieldValue'] 
         * $parameterArr[0]['row'] 
         * $parameterArr[0]['col']
         * $parameterArr[0]['count']
         * 
         */

        //$this->data = (array)$parameter;
        $parameterArr = (array) $parameter;
        $result = array("error_num"=>"", "error_row"=>"");
        //$this->data = count($parameterArr); //$parameterArr[0]['fieldValue'];//['row']['col'];   
        //$this->count = $parameterArr[0]['count'];
        for ($i = 1; $i <= $parameterArr[0]['count']; $i++) {

            /* Validacion Basica */
            for ($i = 0; $i <= count($parameterArr); $i++) {

                /* TIPO_OPERACION
                 * Nro A.1
                 * Detail:
                 * El campo no puede estar vacío y debe contener uno de los siguientes parámetros:
                  INCORPORACION
                  INCREMENTO TENENCIA ACCIONARIA
                  DISMINUCION DE CAPITAL SOCIAL
                 */
                $code_error = "A.1";
                if ($parameterArr[$i]['col'] == 1) {
                    //Valida Vacio
                    $return = $this->check_empty($parameterArr[$i]['fieldValue']);
                    if($return!=NULL){
                        $result["error_num"] = $code_error;
                        $result["error_row"] = $parameterArr[$i]['row'];
                        
                        //$add_arr[] = "error_num"=>$code_error; //"error_row"=>$parameterArr[$i]['row']);
                        //array_push($result,$add_arr);
                    }
                    //$result[] = $i . "-" . $parameterArr[$i]['fieldValue'] . "[" . $parameterArr[$i]['row'] . "][" . $parameterArr[$i]['col'] . "]";
                }
            }
        }

        $this->data = $result;
    }

    function check_empty($parameter) {
        if ($parameter == NULL) {
            return "error" . $parameter;
        }
    }

    function check_word($parameter) {
        if ($parameter == NULL) {
            return "error" . $parameter;
        }
    }

}
