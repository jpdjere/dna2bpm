db.queue.aggregate(

  // Pipeline
  [
    // Stage 1
    {
      $match: { 
          "status" : "revision"
      }
    },

    // Stage 2
    {
      $lookup: { 
          "from" : "procesos", 
          "localField" : "transaccion", 
          "foreignField" : "transaccion", 
          "as" : "empresa"
      }
    },

    // Stage 3
    {
      $group: {
      _id:'$empresa.formaJuridica',cant:{$sum:1}}
    },

    // Stage 4
    {
      $unwind: 
      '$_id'
      
    },

    // Stage 5
    {
      $sort: {
      cant:-1
      }
    }

  ]

  // Created with 3T MongoChef, the GUI for MongoDB - http://3t.io/mongochef

);
