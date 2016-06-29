<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * test
 * 
 * Description of the class
 * 
 * @author Sebastian Blazquez <seby_1996@hotmail.com>
 */
class Emails extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->base_url = base_url();
        $this->module_url = base_url() . $this->router->fetch_module() . '/';
        $this->load->model('model_financiamiento');
        $this->load->library('parser');
        $this->load->model('bpm/bpm');
    }
    
    function Index() {
        //$this->session->set_userdata(array('iduser'=>756148209, 'loggedin'=>true));
        //redirect($this->base_url.'bpm/engine/newcase/model/form_entrada');
    }

    function email_gran_empresa_bancario(){

        $email['subject'] = 'Líneas de Crédito. Banco BICE';
        $email['body'] = 'Este mail se envió en forma automática desde la página del Ministerio de Producción.
        De acuerdo a los datos enviados mediante el formulario, hoy en día su solicitud podría canalizarse por medio de las líneas de crédito para Grandes Empresas disponibles en Banco BICE.
        En líneas generales, para proyectos de inversión, el financiamiento es de hasta $200 millones o su equivalente en dólares, con un plazo de hasta 15 años a tasa “Badlar Bancos Privados” más el spread que el BICE determine en cada caso. En dólares con un plazo de hasta 10 años en dólares, a tasa Libor más el spread que el BICE determine en cada caso.
        En caso de estar interesado, la empresa deberá hacer la presentación ante la entidad financiera para su calificación como sujeto de crédito de la línea.  La misma, deberá contener la información que se detalla en el adjunto Pre-análisis. 
        Para mayor información podrá ingresar al siguiente link  http://www.bice.com.ar/es/
        Se deja constancia que la presente no implica el otorgamiento del derecho a esa empresa a la aprobación del crédito en cuestión
        Cualquier duda o dificultad que pudiera surgir comunicarse con financiamiento@producción.gob.ar o  comercial@bice.com.ar. 
        ';
        $email['reply_email'] = 'financiamiento@producción.gob.ar';
        $email['reply_nicename'] = 'Financiamiento Ministerio de Producción';
        $email['attachments'] = array('/var/www/dna2bpm/application/modules/financiamiento/assets/attachments/gran_empresa/bancario/preanalisis.pdf'
            =>'Preanalisis.pdf');
        
        return $email;
    }
    
    function email_gran_empresa_no_bancario(){

        $email['subject'] = 'Solicitud de información complementaria. Ministerio de Producción.';
        $email['body'] = 'Este mail se envió en forma automática desde la página del Ministerio de Producción.
        De acuerdo al análisis de la información presentada consideramos necesario profundizar el estudio del caso para encontrar la mejor alternativa de financiamiento que se corresponda con las características de su empresa y proyecto.
        Una vez completada la planilla, deberá enviarse a: financiamiento@producción.gob.ar con el asunto: ENVIO DE INFORMACIÓN ADICIONAL- NOMBRE DE EMPRESA.
        Cualquier duda o dificultad que pudiera surgir comunicarse con financiamiento@producción.gob.ar
        ';
        $email['reply_email'] = 'financiamiento@producción.gob.ar';
        $email['reply_nicename'] = 'Financiamiento Ministerio de Producción';
        $email['attachments'] = array('/var/www/dna2bpm/application/modules/financiamiento/assets/attachments/gran_empresa/no_bancario/plantilla_empresa.xlsx'
            =>'Plantilla Empresa.xlsx');
        
        return $email;
    }
    
    function email_fona_ct(){

        $email['subject'] = 'Formulario Presentación FONAPYME – FORTALECIMIENTO COMPETITIVO';
        $email['body'] = 'Este mail se envió en forma automática desde la página del Ministerio de Producción.
        Tengo el agrado de dirigirme a usted, a efectos de comunicarle que, de acuerdo al análisis de la información presentada, en principio, su proyecto se ajusta a lo establecido en las Bases y Condiciones del Llamado a Concurso Público de Proyectos para el Fondo Nacional de Desarrollo de la Micro, Pequeña y Mediana Empresa (FONAPyME). Por tal motivo, se adjunta al presente el Anexo II “Formulario de Presentación de Proyectos”, el cual deberá ser cumplimentado por la empresa requirente.
        Para que las presentaciones sean admitidas, los interesados deberán cumplir con todos y cada uno de los siguientes requisitos:
        1. Realizar la presentación de la información y documentación requerida en el FONAPYME.
        2. Cada proyecto, junto a la documentación requerida para cada caso, se entregará encarpetado (no anillado), en hojas tamaño A4, en un juego original. Se deberán firmar todas y cada una de las hojas de los formularios y de la documentación anexa presentada. A su vez, deberá acompañarse en soporte digital, con identificación de la empresa, según consta en el Formulario de Presentación de Proyectos, debidamente completado.
        3. Deberá ser presentado en la Mesa de Entradas de la SECRETARÍA DE EMPRENDEDORES Y DE LA PEQUEÑA Y MEDIANA EMPRESA del MINISTERIO DE PRODUCCIÓN, sita en la Av. Julio A. Roca 651, Planta Baja, Sector 2, de la Ciudad Autónoma de Buenos Aires, y en todos aquellos sitios que el MINISTERIO DE PRODUCCIÓN determine oportunamente. Las presentaciones enviadas por correo serán aceptadas cuando la fecha del matasellos postal indique que la remisión fue realizada dentro de las SETENTA Y DOS HORAS (72 hs) hábiles posteriores a la fecha y hora de cada cierre de la Convocatoria.
        Se deja constancia que la presente no implica el otorgamiento del derecho a esa empresa a la aprobación del crédito en cuestión
        Cualquier duda o dificultad que pudiera surgir comunicarse con fonapyme@producción.gob.ar 
        ';
        $email['reply_email'] = 'financiamiento@producción.gob.ar';
        $email['reply_nicename'] = 'Financiamiento Ministerio de Producción';
        $email['attachments'] = array('/var/www/dna2bpm/application/modules/financiamiento/assets/attachments/pyme/no_bancario/fona_ct.xls'
            =>'ANEXO II - Formulario FONAPYME - FORTALECIMIENTO COMPETITIVO.xls');
        
        return $email;
    }
    
    function email_fona_bc(){

        $email['subject'] = 'Formulario Presentación FONAPYME – PRODUCCIÓN ESTRATÉGICA';
        $email['body'] = 'Este mail se envió en forma automática desde la página del Ministerio de Producción.
        Tengo el agrado de dirigirme a usted, a efectos de comunicarle que, de acuerdo al análisis de la información presentada, en principio, su proyecto se ajusta a lo establecido en las Bases y Condiciones del Llamado a Concurso Público de Proyectos para el Fondo Nacional de Desarrollo de la Micro, Pequeña y Mediana Empresa (FONAPyME). Por tal motivo, se adjunta al presente el Anexo II “Formulario de Presentación de Proyectos”, el cual deberá ser cumplimentado por la empresa requirente.
        Para que las presentaciones sean admitidas, los interesados deberán cumplir con todos y cada uno de los siguientes requisitos:
        1. Realizar la presentación de la información y documentación requerida en el FONAPYME.
        2. Cada proyecto, junto a la documentación requerida para cada caso, se entregará encarpetado (no anillado), en hojas tamaño A4, en un juego original. Se deberán firmar todas y cada una de las hojas de los formularios y de la documentación anexa presentada. A su vez, deberá acompañarse en soporte digital, con identificación de la empresa, según consta en el Formulario de Presentación de Proyectos, debidamente completado.
        3. Deberá ser presentado en la Mesa de Entradas de la SECRETARÍA DE EMPRENDEDORES Y DE LA PEQUEÑA Y MEDIANA EMPRESA del MINISTERIO DE PRODUCCIÓN, sita en la Av. Julio A. Roca 651, Planta Baja, Sector 2, de la Ciudad Autónoma de Buenos Aires, y en todos aquellos sitios que el MINISTERIO DE PRODUCCIÓN determine oportunamente. Las presentaciones enviadas por correo serán aceptadas cuando la fecha del matasellos postal indique que la remisión fue realizada dentro de las SETENTA Y DOS HORAS (72 hs) hábiles posteriores a la fecha y hora de cada cierre de la Convocatoria.
        Se deja constancia que la presente no implica el otorgamiento del derecho a la aprobación del crédito en cuestión.
        Cualquier duda o dificultad que pudiera surgir comunicarse con fonapyme@producción.gob.ar
        ';
        $email['reply_email'] = 'financiamiento@producción.gob.ar';
        $email['reply_nicename'] = 'Financiamiento Ministerio de Producción';
        $email['attachments'] = array('/var/www/dna2bpm/application/modules/financiamiento/assets/attachments/pyme/no_bancario/fona_bc.xls'
            =>'ANEXO II -  FONAPYME PRODUCCION ESTRATEGICA.xls');
        
        return $email;
    }

    function email_fona_otros(){

        $email['subject'] = 'Solicitud de información complementaria.';
        $email['body'] = 'Este mail se envió en forma automática desde la página del Ministerio de Producción.
        Tengo el agrado de dirigirme a usted a efectos de comunicarle que, de acuerdo al análisis de la información presentada, en principio, su proyecto excede el monto otorgado por el Ministerio de Producción para proyectos de estas características. No obstante, solicitamos que tenga a bien completar el archivo que se adjunta, de manera de poder avanzar en el análisis del caso en busca de la mejor alternativa de financiamiento que se adecue a sus las características de su empresa y proyecto.
        Cualquier duda o dificultad que pudiera surgir comunicarse con fonapyme@producción.gob.ar 
        ';
        $email['reply_email'] = 'financiamiento@producción.gob.ar';
        $email['reply_nicename'] = 'Financiamiento Ministerio de Producción';
        $email['attachments'] = array('/var/www/dna2bpm/application/modules/financiamiento/assets/attachments/pyme/no_bancario/plantilla_empresa.xlsx'
            =>'Plantilla Empresa.xlsx');
        
        return $email;
    }

    function email_mi_galpon(){

        $email['subject'] = 'Presentación "Mi Galpón"';
        $email['body'] = 'Este mail se envió en forma automática desde la página del Ministerio de Producción. 
        De acuerdo al análisis de la información presentada, en principio, su proyecto se ajusta a ser financiado por la línea de crédito bancario "Mi Galpón".
        La empresa interesada deberá completar sus datos y Formulario de Proyecto, ingresando en nuestro sistema, en el siguiente link (Registro de usuario). Una vez finalizado el mismo, debe ser guardado desde el sistema para ser recibido por la Subsecretaria de Financiamiento de la Producción, para su pre-evaluación.
        En caso que se declare la elegibilidad, la empresa será notificada mediante correo electrónico. Luego, deberá presentar la documentación respaldatoria que se cita a continuación, en un plazo de hasta 20 días hábiles contados desde la fecha de recepción de la notificación, ante la Subsecretaría de Financiamiento de la Producción, cita en AV. JULIO A. ROCA 651- PLANTA BAJA- SECTOR 2- CODIGO POSTAL 1067- CABA.  
        Si la evaluación resulta factible, se emitirá un certificado de elegibilidad y el mismo será enviado a la empresa y sucursal bancaria por correo. La empresa deberá  acercarse a la sucursal a solicitar la asistencia financiera. 
        Documentación Respaldatoria
        Personas Jurídicas o Físicas:
        Constancia de inscripción en AFIP firmada por el administrador, presidente o representante legal de la empresa o el interesado en caso de ser persona física.
        Testimonio de Escritura Pública para empresas o personas físicas solicitantes conforme modelo correspondiente, redactado por Escribano Público. En caso de que el Escribano no fuere de la Ciudad Autónoma de Buenos Aires deberá estar acompañado de la legalización del Colegio de Escribanos de la jurisdicción correspondiente.
        Modelo de Testimonio de Escritura Pública para Personas Físicas o Sociedades de Hecho
        Modelo de Testimonio de Escritura Pública para Personas Jurídicas
        Informe con carácter de declaración jurada, conforme modelo, bajo apercibimiento de lo dispuesto por el art. 275 del Código Penal de la Nación en caso de resultar falsa o incompleta, firmado por el interesado o administrador, presidente o representante legal de la empresa, el cual debe contener una reseña de la empresa, con el detalle de la actividad que desarrolla, citando el domicilio donde realiza la actividad, presentar una explicación clara y concreta del proyecto de adquisición o construcción del galpón industrial, las causas que lo motivan, los beneficios que se deriven de la concreción del proyecto, el monto de la inversión estimado para concretarlo y el monto del crédito a solicitar, el empleo incremental generado y las variables consideradas para dicha estimación. Asimismo deberán incluir en el mencionado informe un punto especial donde indiquen la garantía a ofrecer al Banco, y además mencionar sí se encuentra actualmente en relación comercial con la entidad, indicando en que sucursal opera y en el caso que no, en que sucursal del Banco prefiere operar.
        Modelo de Informe en carácter de Declaración Jurada.
        En caso, de tratarse de Persona Física, deberá presentar copia del DNI, certificada por Escribano Público acompañado de la legalización del Colegio de Escribanos en caso de corresponder, o también puede ser certificada por Juez de Paz o Poder Judicial
        Se adjuntan al presente los archivos solicitados. 
        Se deja constancia que la presente no implica el otorgamiento del derecho a la aprobación del crédito en cuestión.
        Para mayor información podrá ingresar al siguiente link http://www.produccion.gob.ar/mi-galpon/como-se-accede/.
        ';
        $email['reply_email'] = 'financiamiento@producción.gob.ar';
        $email['reply_nicename'] = 'Financiamiento Ministerio de Producción';
        $email['attachments'] = array(
            '/var/www/dna2bpm/application/modules/financiamiento/assets/attachments/pyme/bancario/mi_galpon_informe.docx'
            =>'Modelo Informe en Caracter de Declaración Jurada 2016.docx',
            '/var/www/dna2bpm/application/modules/financiamiento/assets/attachments/pyme/bancario/mi_galpon_test_escr_pfisica.docx'
            =>'Testimonio de Escritura Pública para Persona física.docx',
            '/var/www/dna2bpm/application/modules/financiamiento/assets/attachments/pyme/bancario/mi_galpon_test_escr_pjuridica.docx'
            =>'Testimonio de Escritura Pública para Personas Jurídicas.docx'
            );
        
        return $email;
    }

    function email_parques(){

        $email['subject'] = 'Presentación "Parques Industriales"';
        $email['body'] = 'Este mail se envió en forma automática desde la página del Ministerio de Producción. 
        De acuerdo al análisis de la información presentada, en principio, su proyecto se ajusta a ser financiado por la línea de crédito bancario "Parques Industriales".
        Se adjunta al presente la alternativa de financiamiento. En caso de estar interesado y cumplir con los requisitos, la empresa deberá presentar la documentación respaldatoria que se cita a continuación:
        Constancia de Clave Única de Identificación Tributaria (C.U.I.T.) firmada por el representante de la empresa. Podrá obtenerla ingresando al siguiente link: https://seti.afip.gob.ar/padron-puc-constancia-internet/ConsultaConstanciaAction.do
        
        Testimonio de Escritura Pública para Personas Físicas o Jurídicas según corresponda, conforme los modelos adjuntados.
        (Links de descarga de los modelos Adjuntos)
        Certificación Contable conforme al modelo propuesto por la Subsecretaría de Financiamiento de la Producción, certificada por el Consejo Profesional en Ciencias Económicas, suscripta por el representante legal y el Contador Público en todas sus hojas.
        (Modelo de Certificación Contable)
        Constancia emitida por la Unidad de Desarrollo Industrial Local (UDIL) que acredite:
        Que el Parque Industrial en el cual la empresa solicitante se encuentra radicada o próxima a radicarse se encuentra inscripto o en proceso de inscripción en el Registro Nacional de Parques Industriales.
        Que la actividad de la empresa peticionante resulta susceptible de ser realizada en dicho parque.
        A los efectos de solicitar tal constancia, la empresa deberá presentar nota ante la Unidad de Desarrollo Industrial Local (UDIL) (Av. Julio A. Roca 651 – P3° - Of.22 - C.A.B.A. (C1067ABB) o por email a: creditoparques@industria.gob.ar.
        Reunida la documentación detallada precedentemente, deberá presentarse ante la Unidad de Desarrollo Industrial Local (UDIL) en Av. Julio A. Roca 651 – P3° - Of.22 - C.A.B.A.(C1067ABB) 
        La Subsecretaria de Financiamiento de la Producción realizará la evaluación de la solicitud y la documentación presentada a efectos de declarar la elegibilidad o rechazar la solicitud presentada.
        La declaración de elegibilidad, a los efectos de calificar para solicitar un crédito con tasa bonificada por el Ministerio de Producción ante el Banco de la Nación Argentina, será notificada al empresario solicitante y al Banco, a los efectos de que inicie la gestión del crédito ante la sucursal del Banco señalada en la presentación.
        Cualquier duda o dificultad que pudiera surgir, comunicarse con rbt@produccion.gob.ar.
        ';
        $email['reply_email'] = 'financiamiento@producción.gob.ar';
        $email['reply_nicename'] = 'Financiamiento Ministerio de Producción';
        $email['attachments'] = array(
            '/var/www/dna2bpm/application/modules/financiamiento/assets/attachments/pyme/bancario/parques_certificacion.docx'
            =>'Modelo de Cerificación Contable Parques Industriales 2016.docx',
            '/var/www/dna2bpm/application/modules/financiamiento/assets/attachments/pyme/bancario/parques_escritura_pfisicas.docx'
            =>'Modelo de Escritura Pública para Personas Físicas.docx',
            '/var/www/dna2bpm/application/modules/financiamiento/assets/attachments/pyme/bancario/parques_escritura_pjuridcas.docx'
            =>'Modelo de Escritura Pública para Personas Jurídicas.docx'
            );
        
        return $email;
    }

    function email_rbt_bice(){

        $email['subject'] = 'Régimen de Bonificación de Tasa.';
        $email['body'] = 'Este mail se envió en forma automática desde la página del Ministerio de Producción. 
        De acuerdo al análisis de la información presentada, en principio, su proyecto se ajusta a ser financiado por la línea de crédito bancario “Régimen de bonificación de tasas”. 
        Se adjunta al presente la alternativa de financiamiento. En caso de estar interesado y cumplir con los requisitos, la empresa deberá hacer la presentación ante la entidad financiera para su calificación como sujeto de crédito de la línea.  La misma, deberá contener la información que se detalla en el adjunto Preanálisis. 
        Cualquier duda o dificultad que pudiera surgir, comunicarse con rbt@produccion.gob.ar; o en caso de que se requiera hacer una derivación y contacto con el Banco.
        Se deja constancia que la presente no implica el otorgamiento del derecho a la aprobación del crédito en cuestión.
        ';
        $email['reply_email'] = 'financiamiento@producción.gob.ar';
        $email['reply_nicename'] = 'Financiamiento Ministerio de Producción';
        $email['attachments'] = array(
            '/var/www/dna2bpm/application/modules/financiamiento/assets/attachments/pyme/bancario/rbt_bice_convenio.pdf'
            =>'Convenio Bice.pdf',
            '/var/www/dna2bpm/application/modules/financiamiento/assets/attachments/pyme/bancario/rbt_bice_preanalisis.pdf'
            =>'Preanalisis Bice.docx'
            );
        
        return $email;
    }

    function email_rbt_bna(){

        $email['subject'] = 'Régimen de Bonificación de Tasa.';
        $email['body'] = 'Este mail se envió en forma automática desde la página del Ministerio de Producción. 
        De acuerdo al análisis de la información presentada, en principio, su proyecto se ajusta a ser financiado por la línea de crédito bancario “Régimen de bonificación de tasas”. 
        Se adjunta al presente la alternativa de financiamiento. En caso de estar interesado y cumplir con los requisitos, deberá hacer la presentación ante la entidad financiera para su calificación como sujeto de crédito de la línea.  
        Cualquier duda o dificultad que pudiera surgir, comunicarse con rbt@produccion.gob.ar; o en caso de que se requiera hacer una derivación y contacto con el Banco.
        Se deja constancia que la presente no implica el otorgamiento del derecho a la aprobación del crédito en cuestión.
        ';
        $email['reply_email'] = 'financiamiento@producción.gob.ar';
        $email['reply_nicename'] = 'Financiamiento Ministerio de Producción';
        $email['attachments'] = array(
            '/var/www/dna2bpm/application/modules/financiamiento/assets/attachments/pyme/bancario/rbt_bna_convenio.pdf'
            =>'Convenio Bna.pdf'
            );
        
        return $email;
    }
    
    function email_pyme_bancario_otros(){

        $email['subject'] = 'Régimen de Bonificación de Tasa.';
        $email['body'] = 'De acuerdo al análisis de la información presentada,  le informamos que, hoy en día,  su solicitud podría canalizarse por medio de la  línea de crédito para la Inversión Productiva (LICIP) disponible en los bancos a una tasa aproximada del 22% nominal anual.
        Se deja constancia que la presente no implica el otorgamiento del derecho a la aprobación del crédito en cuestión.
        Cualquier duda o dificultad que pudiera surgir, comunicarse con financiamiento@producción.gob.ar.
        ';
        $email['reply_email'] = 'financiamiento@producción.gob.ar';
        $email['reply_nicename'] = 'Financiamiento Ministerio de Producción';
        $email['attachments'] = array('/var/www/dna2bpm/application/modules/financiamiento/assets/attachments/gran_empresa/bancario/preanalisis.pdf'
            =>'Preanalisis.pdf');
        
        return $email;
    }
}




