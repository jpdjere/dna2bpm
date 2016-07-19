db.procesos.aggregate(

  // Pipeline
  [
    // Stage 1
    {
      $group: {
      _id: "$domicilioLegalDescripcionProvincia",
      cant:{"$sum":1}
      
      }
    },

    // Stage 2
    {
      $project: {
      "Provincia":"$_id","Cantidad":"$cant","_id":0
      }
    }

  ]

  // Created with 3T MongoChef, the GUI for MongoDB - http://3t.io/mongochef

);
