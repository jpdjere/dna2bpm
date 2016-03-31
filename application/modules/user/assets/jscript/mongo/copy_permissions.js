//------Copy permission from group 8 to 112
db['perm.groups'].find({idgroup:8}).forEach(function(reg){
    delete reg._id;
    reg.idgroup=112;
    db['perm.groups'].insert(reg);
    })