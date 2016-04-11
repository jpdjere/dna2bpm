jQuery(document).ready(function () {
            // var options = famdata;
            // jQuery.extend(options, {
            //     cursorItem: null,
            //     hasSelectorCheckbox: primitives.common.Enabled.False,
            //     hasButtons: primitives.common.Enabled.False,
            //     pageFitMode: primitives.common.PageFitMode.PrintPreview,
            //     elbowType: primitives.common.ElbowType.Round,
            //     normalLevelShift: 30,
            //     dotLevelShift: 30,
            //     lineLevelShift: 24,
            //     normalItemsInterval: 20,
            //     dotItemsInterval: 10,
            //     lineItemsInterval: 4,
            //     linesWidth: 1,
            //     linesColor: "black",
            //     cousinsIntervalMultiplier: 1,
            //     arrowsDirection: primitives.common.GroupByType.Parents
            // });

            // jQuery("#diagram").famDiagram(options);
            LoadData();
        });
        
        function LoadData() {
    /**
     * Load via ajax
     */
    $.ajax({
        'url': globals.module_url + 'get/' + globals.idorg,
        'method': 'post',
        'dataType': 'json',
        'success': function(data, status) {
            options = data.data;
            // options.cursorItem = options.items[0] != null ? options.items[0].id : null;
            jQuery.extend(options, {
                cursorItem: null,
                hasSelectorCheckbox: primitives.common.Enabled.False,
                hasButtons: primitives.common.Enabled.False,
                pageFitMode: primitives.common.PageFitMode.PrintPreview,
                elbowType: primitives.common.ElbowType.Round,
                normalLevelShift: 30,
                dotLevelShift: 30,
                lineLevelShift: 24,
                normalItemsInterval: 20,
                dotItemsInterval: 10,
                lineItemsInterval: 4,
                linesWidth: 1,
                linesColor: "black",
                cousinsIntervalMultiplier: 1,
                arrowsDirection: primitives.common.GroupByType.Parents
            });

            jQuery("#orgdiagram").famDiagram(options);
            // orgDiagram.orgDiagram({items:data.data.items});
            // orgDiagram.orgDiagram("update");
            // orgDiagram._trigger("onSave");
        }

    });
}