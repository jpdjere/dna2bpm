/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

cursor = db.container.sgr_anexo_06.find();
while(cursor.hasNext()){
    printjson(cursor.next());
}


