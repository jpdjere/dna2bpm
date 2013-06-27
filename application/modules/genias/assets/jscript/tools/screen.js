/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
function width(){
   return window.innerWidth||document.documentElement.clientWidth||document.body.clientWidth||0;
}
function height(){
   return window.innerHeight||document.documentElement.clientHeight||document.body.clientHeight||0;
}

$('body').html('<div class="form-signin"><h1>Width:'+width()+'<br/><br/>Height:'+height()+'</h1></div>');