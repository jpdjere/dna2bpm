function ammap_data_convert(data) {
 var   map_convert = {
        "BA": "AR-B",
        "CAT": "AR-K",
        "CHA": "AR-H",
        "CH": "AR-U",
        "CABA": "AR-C",
        "CBA": "AR-X",
        "CTES": "AR-W",
        "ER": "AR-E",
        "FOR": "AR-P",
        "JUJ": "AR-Y",
        "LP": "AR-L",
        "LR": "AR-F",
        "MZA": "AR-M",
        "MIS": "AR-N",
        "NEU": "AR-Q",
        "RN": "AR-R",
        "SAL": "AR-A",
        "SC": "AR-Z",
        "SDE": "AR-G",
        "SF": "AR-S",
        "SJ": "AR-J",
        "SL": "AR-D",
        "TDF": "AR-V",
        "TUC": "AR-T"
    };
    var rtn = {"areas":[]};
    for (var x in data) {
        rtn.areas.push({
            "id":map_convert[data[x].prov],
            "value": (data[x].cant) ? data[x].cant:0
        });
    }
    return rtn;
}