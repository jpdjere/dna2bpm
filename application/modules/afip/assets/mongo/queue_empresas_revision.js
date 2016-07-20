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
    }

  ]

  // Created with 3T MongoChef, the GUI for MongoDB - http://3t.io/mongochef

);
