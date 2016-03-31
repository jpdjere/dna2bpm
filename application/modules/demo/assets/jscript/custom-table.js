       $(document).ready(function(){
         $(function(){
         $("#myTable").tablesorter(
         {
         theme : 'blue',
         sortList : [[1,0],[2,0],[3,0]],
         // header layout template; {icon} needed for some themes
         headerTemplate : '{content}{icon}',
         // initialize column styling of the table
         widgets : ["columns"],
         widgetOptions : {
         // change the default column class names
         // primary is the first column sorted, secondary is the second, etc
         columns : [ "primary", "secondary", "tertiary" ]
         }
         });
         });
         });

