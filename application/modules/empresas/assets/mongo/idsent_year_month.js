db.idsent.aggregate(

  // Pipeline
  [
    // Stage 1
    {
      $match: {
      "ident":7
      }
    },

    // Stage 2
    {
      $group: {
      "_id":{"year":{"$year":"$checkdate"},"month":{"$month":"$checkdate"}},
      "qtty":{"$sum":1},
      "checkdate":{"$first":"$checkdate"}
      }
    },

    // Stage 3
    {
      $project: {
      "_id":false,"year":"$_id.year","month":"$_id.month","qtty":"$qtty",
      date:{ $dateToString: { format: "%Y-%m", date: "$checkdate" } }
      }
    },

    // Stage 4
    {
      $match: {
      "year":2015
      }
    },

    // Stage 5
    {
      $sort: {
      "year":1,"month":1
      }
    }

  ]

  // Created with 3T MongoChef, the GUI for MongoDB - http://3t.io/mongochef

);
