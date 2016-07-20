db.procesos.aggregate(

  // Pipeline
  [
    // Stage 1
    {
      $group: {
      _id: "$result.sector_texto",
      cant:{"$sum":1}
      
      }
    },

    // Stage 2
    {
      $project: {
      "Sector":"$_id","Cantidad":"$cant","_id":0
      }
    },

    // Stage 3
    {
      $sort: {
      'Cantidad':-1
      }
    }

  ]

  // Created with 3T MongoChef, the GUI for MongoDB - http://3t.io/mongochef

);
