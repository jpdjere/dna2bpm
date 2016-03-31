$(document).ready(function(){
    
  var
  container = document.getElementById('ht1'),
  hot5;
  

hot5 = new Handsontable(container, {
  
  data: [
    
      ['row1', 'col2', 'col3', 'col4', 'col5','col6','col6', 'col6', 'col6','col7'],
      ['row1', 'col2', 'col3', 'col4', 'col5','col6','col6', 'col6', 'col6','col7'],
      ['row1', 'col2', 'col3', 'col4', 'col5','col6','col6', 'col6', 'col6','col7'],
      ['row1', 'col2', 'col3', 'col4', 'col5','col6','col6', 'col6', 'col6','col7'],
      ['row1', 'col2', 'col3', 'col4', 'col5','col6','col6', 'col6', 'col6', 'col7'],
    
    ],
    
  startRows: 5,
  startCols: 10,
  colHeaders: ['Indicadores', 'Unidad de medida', 'Val', 'Año', 'Val-1', 'Año-1', 'Val-2', 'Año-2', 'Fuente/ Medio de verificación', 'Observaciones'],
  minSpareRows: 1
  
});
    
  
    
    
    
    
    
});