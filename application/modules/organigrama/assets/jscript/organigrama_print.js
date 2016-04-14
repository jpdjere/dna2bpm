var m_timer = null;
var options = new primitives.orgdiagram.Config();
jQuery(document).ready(function() {
    $(window).resize(function() {
        onWindowResize();
    });
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
            // options.hasSelectorCheckbox = primitives.common.Enabled.False;
            // options.minimalVisibility = primitives.common.Visibility.Normal;
            // options.pageFitMode = primitives.common.PageFitMode.Width;
            // options.leavesPlacementType = primitives.common.ChildrenPlacementType.Matrix;
            // options.normalLevelShift = 20;
            // options.dotLevelShift = 10;
            // options.lineLevelShift = 10;
            // options.normalItemsInterval = 20;
            // options.dotItemsInterval = 10;
            // options.lineItemsInterval = 10;
             options.templates = [getTemplate1(),getTemplate2()];
             options.onItemRender = onTemplateRender;
            
            
                options.cursorItem= null;
                options.hasSelectorCheckbox= primitives.common.Enabled.False;
                options.hasButtons= primitives.common.Enabled.False;
                options.pageFitMode= primitives.common.PageFitMode.PrintPreview;
                options.elbowType= primitives.common.ElbowType.Round;
                options.normalLevelShift= 30;
                options.dotLevelShift= 30;
                options.lineLevelShift= 24;
                options.normalItemsInterval= 20;
                options.dotItemsInterval= 10;
                options.lineItemsInterval= 4;
                options.linesWidth= 1;
                options.linesColor= "black";
                options.cousinsIntervalMultiplier= 1;
                options.arrowsDirection= primitives.common.GroupByType.Parents;
                
                
            orgDiagram = jQuery("#orgdiagram").orgDiagram(options);
            
            // orgDiagram.orgDiagram(options);
            // orgDiagram.orgDiagram("update");
            // orgDiagram._trigger("onSave");
        }

    });
}

function onWindowResize() {
    if (m_timer == null) {
        m_timer = window.setTimeout(function() {
            // ResizePlaceholder();
            orgDiagram.orgDiagram("update", primitives.common.UpdateMode.Refresh)
            window.clearTimeout(m_timer);
            m_timer = null;
        }, 1300);
    }
}


function getTemplate1() {
                var result = new primitives.orgdiagram.TemplateConfig();
                result.name = "normal";

                var buttons = [];
                buttons.push(new primitives.orgdiagram.ButtonConfig("revert", "ui-icon-transferthick-e-w", "Revert"));
                buttons.push(new primitives.orgdiagram.ButtonConfig("email", "ui-icon-mail-closed", "E-Mail"));
                buttons.push(new primitives.orgdiagram.ButtonConfig("help", "ui-icon-help", "Help"));

                result.buttons = buttons;

                result.itemSize = new primitives.common.Size(220, 130);
                result.minimizedItemSize = new primitives.common.Size(3, 3);
                result.highlightPadding = new primitives.common.Thickness(2, 2, 2, 2);


                var itemTemplate = jQuery(
                  '<div class="bp-item bp-corner-all bt-item-frame" style="border: 1px solid black"  >'
                    + '<div name="titleBackground" class="bp-item bp-corner-all bp-title-frame" style="box-size:border-box;word-wrap:break-word;position:static;margin:0px; width: inherit; height: auto;">'
                        + '<div name="title" class="bp-item bp-title" style="box-size:border-box;word-wrap:break-word;padding:7px;position:static;white-space:normal; width: inherit; ">'
                        + '</div>'
                    + '</div>'
                     + '<div name="description" class="bp-item" style="position:relative;font-weight:bold; font-size: 10px;padding:4px"></div>'
                    + '<div name="phone" class="bp-item" style="position:static;padding:4px;font-size: 10px;"></div>'
                    + '<div name="email" class="bp-item" style="position:static;padding:4px;font-size: 10px;"></div>'
                   
                + '</div>'
                ).css({
                    width: result.itemSize.width + "px",
                    height: result.itemSize.height + "px"
                }).addClass("bp-item bp-corner-all bt-item-frame");
                result.itemTemplate = itemTemplate.wrap('<div>').parent().html();

                return result;
}

function getTemplate2() {
                var result = new primitives.orgdiagram.TemplateConfig();
                result.name = "grande";

                var buttons = [];
                buttons.push(new primitives.orgdiagram.ButtonConfig("revert", "ui-icon-transferthick-e-w", "Revert"));
                buttons.push(new primitives.orgdiagram.ButtonConfig("email", "ui-icon-mail-closed", "E-Mail"));
                buttons.push(new primitives.orgdiagram.ButtonConfig("help", "ui-icon-help", "Help"));

                result.buttons = buttons;

                result.itemSize = new primitives.common.Size(280, 120);
                result.minimizedItemSize = new primitives.common.Size(3, 3);
                result.highlightPadding = new primitives.common.Thickness(2, 2, 2, 2);


                var itemTemplate = jQuery(
                  '<div class="bp-item bp-corner-all bt-item-frame">'
                    + '<div name="titleBackground" class="bp-item bp-corner-all bp-title-frame" style="top: 2px; left: 2px; width: 276px; height: 20px;">'
                        + '<div name="title" class="bp-item bp-title" style="top: 3px; left: 6px; width: 270px; height: 18px;">'
                        + '</div>'
                    + '</div>'
                     + '<div name="description" class="bp-item" style="font-weight:bold;top: 26px; left: 6px; width: 162px; height: 36px; font-size: 10px;"></div>'
                    + '<div name="phone" class="bp-item" style="top: 44px; left: 6px; width: 162px; height: 18px; font-size: 12px;"></div>'
                    + '<div name="email" class="bp-item" style="top: 62px; left: 6px; width: 162px; height: 18px; font-size: 12px;"></div>'
                   
                + '</div>'
                ).css({
                    width: result.itemSize.width + "px",
                    height: result.itemSize.height + "px"
                }).addClass("bp-item bp-corner-all bt-item-frame");
                result.itemTemplate = itemTemplate.wrap('<div>').parent().html();

                return result;
}


function onTemplateRender(event, data) {

         switch (data.renderingMode) {
                    case primitives.common.RenderingMode.Create:
                        /* Initialize widgets here */
                        break;
                    case primitives.common.RenderingMode.Update:
                        /* Update widgets here */
                        break;
                }

                var itemConfig = data.context;

                if (data.templateName == "grande" || data.templateName == "normal") {
                    // dum
                    data.element.find("[name=photo]").attr({ "src": itemConfig.image, "alt": itemConfig.title });
                    data.element.find("[name=titleBackground]").css({ "background": itemConfig.itemTitleColor });

                    var fields = ["title", "description", "phone", "email"];
                    for (var index = 0; index < fields.length; index++) {
                        var field = fields[index];

                        var element = data.element.find("[name=" + field + "]");
                        if (element.text() != itemConfig[field]) {
                            element.text(itemConfig[field]);
                        }
                    }
                }
            }