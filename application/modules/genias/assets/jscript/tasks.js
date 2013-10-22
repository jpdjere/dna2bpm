/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


$( document ).ready(function() {


url=globals.module_url+"print_ul_tasks/";

// Escenario PYME
//$('#collapse'+'2').load(url+'/2');
//// Escenario Politico
//$('#collapse'+'3').load(url+'/3');


$('.dp').datepicker().on('changeDate',function(ev){
    var mes=ev.date.toISOString().slice(0,7);
    var proy=$(this).attr('id').slice(2,4);
    $('#collapse'+proy).load(url+proy+'/'+mes); 
}); 
  

});

