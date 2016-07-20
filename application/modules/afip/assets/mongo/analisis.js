db.analisis.drop()
db.raw_ventanilla.find({
    1273: {
        $exists: false
    }
}).forEach(function(rec) {
    //printjson(db.queue.findOne);exit;
    if (rec.cuit) {
        var query = {};
        query.cuit = rec.cuit;
        var cur = db.queue.findOne(query);
        var curP = db.procesos.findOne(query);
        //printjson(cur);
        rec.queue = cur;
        rec.proceso = curP;
        rec.query = query;

    }
    db.analisis.save(rec);
})