/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
m = function () {
    emit(this.filename, 1);
}

r = function (k, vals) {
    return Array.sum(vals);
}
res = db.container.sgr_anexo_12.mapReduce(m, r, { out : "sgr_anexo_12_dupes" });

