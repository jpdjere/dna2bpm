/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */


$( document ).ready(function() {

url=globals.module_url+"print_ul_tasks/";

$('.dp').datepicker().on('changeDate',function(ev){
    var mes=ev.date.toISOString().slice(0,7);
	$('[id^="collapse"]').each(function(){
		var proy=$(this).attr('id').slice(8);
		 $('#collapse'+proy).load(url+proy+'/'+mes); 
	});
}); 
  


});

