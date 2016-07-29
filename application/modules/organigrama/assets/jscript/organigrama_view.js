var m_timer = null;
var options = new primitives.orgdiagram.Config();

jQuery(document).ready(function() {
    jQuery.ajaxSetup({
        cache: false
    });

    ResizePlaceholder();
    $(window).resize(function() {
        onWindowResize();
    });

    var templates = [];
    templates.push(getContactTemplate());
    
    options.items = [];
    options.cursorItem = 0;
    options.graphicsType= primitives.common.GraphicsType.SVG;
    options.onItemRender = onTemplateRender;
    options.templates = [getZoom0Template(), getZoom1Template(), getZoom2Template(), getZoom3Template(), getZoom4Template()];
    options.hasSelectorCheckbox = primitives.common.Enabled.True;
    options.pageFitMode = primitives.common.PageFitMode.None;
    options.hasSelectorCheckbox = primitives.common.Enabled.False;
    options.orientationType = primitives.common.OrientationType.Top;
    options.childrenPlacementType = primitives.common.ChildrenPlacementType.Horizontal;
    options.defaultTemplateName = "Zoom3";
    options.minimalVisibility= primitives.common.Visibility.Dot;
    options.normalLevelShift = 20;
    options.dotLevelShift = 10;
    options.lineLevelShift = 10;
    options.normalItemsInterval = 20;
    options.dotItemsInterval = 10;
    options.lineItemsInterval = 10;
    options.arrowsDirection = primitives.common.GroupByType.Children;
    options.pageFitMode = primitives.common.PageFitMode.FitToPage;
    // options.pageFitMode= primitives.common.PageFitMode.PrintPreview;

    orgDiagram=jQuery("#orgdiagram").orgDiagram(options);
    // orgDiagram = jQuery("#orgdiagram").orgDiagram({
    //     graphicsType: primitives.common.GraphicsType.SVG,
    //     pageFitMode: primitives.common.PageFitMode.FitToPage,
    //     verticalAlignment: primitives.common.VerticalAlignmentType.Middle,
    //     connectorType: primitives.common.ConnectorType.Angular,
    //     minimalVisibility: primitives.common.Visibility.Dot,
    //     selectionPathMode: primitives.common.SelectionPathMode.FullStack,
    //     leavesPlacementType: primitives.common.ChildrenPlacementType.Horizontal,
    //     hasButtons: primitives.common.Enabled.False,
    //     hasSelectorCheckbox: primitives.common.Enabled.False,
    //     templates: templates,
    //     onButtonClick: onButtonClick,
    //     onCursorChanging: onCursorChanging,
    //     onCursorChanged: onCursorChanged,
    //     onHighlightChanging: onHighlightChanging,
    //     onHighlightChanged: onHighlightChanged,
    //     onSelectionChanged: onSelectionChanged,
    //     onItemRender: onTemplateRender,
    //     itemTitleFirstFontColor: primitives.common.Colors.White,
    //     itemTitleSecondFontColor: primitives.common.Colors.White
    // });
    $("#slider").slider({
        value: 3,
        min: 0,
        max: 4,
        step: 1,
        slide: function(event, ui) {
            orgDiagram.orgDiagram({
                defaultTemplateName: "Zoom" + ui.value
            });
            orgDiagram.orgDiagram("update", primitives.orgdiagram.UpdateMode.Refresh);
        }
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
            options.items = data.data.items;
            // options.cursorItem = options.items[0] != null ? options.items[0].id : null;
            orgDiagram.orgDiagram({items:data.data.items});
            orgDiagram.orgDiagram("update");
            // orgDiagram._trigger("onSave");
        }

    });
}

function onWindowResize() {
    if (m_timer == null) {
        m_timer = window.setTimeout(function() {
            ResizePlaceholder();
            orgDiagram.orgDiagram("update", primitives.common.UpdateMode.Refresh)
            window.clearTimeout(m_timer);
            m_timer = null;
        }, 300);
    }
}


function getTreeItem(sourceItem) {
    var result = new primitives.orgdiagram.ItemConfig();
    result.title = sourceItem.title;
    result.description = sourceItem.description;
    result.phone = sourceItem.phone;
    result.email = sourceItem.email;
    result.image = "demo/images/photos/" + sourceItem.photo;
    result.groupTitle = sourceItem.title;
    result.id = sourceItem.id;
    result.href = "showdetails.php?recordid=" + result.id;
    if (sourceItem.children != null) {
        for (var index = 0; index < sourceItem.children.length; index += 1) {
            result.items.push(getTreeItem(sourceItem.children[index]));
        }
    }
    return result;
}

function getContactTemplate() {
    var result = new primitives.orgdiagram.TemplateConfig();
    result.name = "contactTemplate";

    result.itemSize = new primitives.common.Size(220, 120);
    result.minimizedItemSize = new primitives.common.Size(3, 3);
    result.highlightPadding = new primitives.common.Thickness(2, 2, 2, 2);


    var itemTemplate = jQuery(
        '<div class="bp-item bp-corner-all bt-item-frame">' + '<div class="bp-item bp-corner-all bp-title-frame" style="top: 2px; left: 2px; width: 216px; height: 20px;">' + '<div name="title" class="bp-item bp-title" style="top: 3px; left: 6px; width: 208px; height: 18px;">' + '</div>' + '</div>' + '<div class="bp-item bp-photo-frame" style="top: 26px; left: 2px; width: 50px; height: 60px;">' + '<img name="photo" style="height:60px; width:50px;" />' + '</div>' + '<div name="phone" class="bp-item" style="top: 26px; left: 56px; width: 162px; height: 18px; font-size: 12px;"></div>' + '<div name="email" class="bp-item" style="top: 44px; left: 56px; width: 162px; height: 18px; font-size: 12px;"></div>' + '<div name="description" class="bp-item" style="top: 62px; left: 56px; width: 162px; height: 36px; font-size: 10px;"></div>' + '<a name="readmore" class="bp-item" style="top: 104px; left: 4px; width: 212px; height: 12px; font-size: 10px; font-family: Arial; text-align: right; font-weight: bold; text-decoration: none; z-index:100;">Read more ...</a>' + '</div>'
    ).css({
        width: result.itemSize.width + "px",
        height: result.itemSize.height + "px"
    }).addClass("bp-item bp-corner-all bt-item-frame");
    result.itemTemplate = itemTemplate.wrap('<div>').parent().html();

    return result;
}

function onTemplateRender(event, data) {
    var hrefElement = data.element.find("[name=readmore]");
    switch (data.renderingMode) {
        case primitives.common.RenderingMode.Create:
            /* Initialize widgets here */
            hrefElement.click(function(e) {
                /* Block mouse click propogation in order to avoid layout updates before server postback*/
                primitives.common.stopPropagation(e);
            });
            break;
        case primitives.common.RenderingMode.Update:
            /* Update widgets here */
            break;
    }

    var itemConfig = data.context;

    if (data.templateName == "contactTemplate") {
        data.element.find("[name=photo]").attr({
            "src": itemConfig.image
        });

        var fields = ["title", "description", "phone", "email"];
        for (var index = 0; index < fields.length; index += 1) {
            var field = fields[index];

            var element = data.element.find("[name=" + field + "]");
            if (element.text() != itemConfig[field]) {
                element.text(itemConfig[field]);
            }
        }
    }
    hrefElement.attr({
        "href": itemConfig.href
    });
}

function onSelectionChanged(e, data) {
    var selectedItems = jQuery("#centerpanel").orgDiagram("option", "selectedItems");
    var message = "";
    for (var index = 0; index < selectedItems.length; index += 1) {
        var itemConfig = selectedItems[index];
        if (message != "") {
            message += ", ";
        }
        message += "<b>'" + itemConfig.title + "'</b>";
    }
    message += (data.parentItem != null ? " Parent item <b>'" + data.parentItem.title + "'" : "");
    jQuery("#southpanel").empty().append("User selected following items: " + message);
}

function onHighlightChanging(e, data) {
    var message = (data.context != null) ? "User is hovering mouse over item <b>'" + data.context.title + "'</b>." : "";
    message += (data.parentItem != null ? " Parent item <b>'" + data.parentItem.title + "'" : "");

    jQuery("#southpanel").empty().append(message);
}

function onHighlightChanged(e, data) {
    var message = (data.context != null) ? "User hovers mouse over item <b>'" + data.context.title + "'</b>." : "";
    message += (data.parentItem != null ? " Parent item <b>'" + data.parentItem.title + "'" : "");

    jQuery("#southpanel").empty().append(message);
}

function onCursorChanging(e, data) {
    var message = "User is clicking on item '" + data.context.title + "'.";
    message += (data.parentItem != null ? " Parent item <b>'" + data.parentItem.title + "'" : "");

    jQuery("#southpanel").empty().append(message);

    data.oldContext.templateName = null;
    data.context.templateName = "contactTemplate";
}

function onCursorChanged(e, data) {
    var message = "User clicked on item '" + data.context.title + "'.";
    message += (data.parentItem != null ? " Parent item <b>'" + data.parentItem.title + "'" : "");
    jQuery("#southpanel").empty().append(message);
}

function onButtonClick(e, data) {
    var message = "User clicked <b>'" + data.name + "'</b> button for item <b>'" + data.context.title + "'</b>.";
    message += (data.parentItem != null ? " Parent item <b>'" + data.parentItem.title + "'" : "");
    jQuery("#southpanel").empty().append(message);
}

function ResizePlaceholder() {
    return;
    var bodyWidth = $(window).width() - 40
    var bodyHeight = $(window).height() - 40
    var titleHeight = 40;
    jQuery("#menu").css({
        "width": bodyWidth + "px",
        "height": titleHeight + "px"
    });

    jQuery("#westpanel").css({
        "left": "0px",
        "width": "200px",
        "height": (bodyHeight - titleHeight) + "px",
        "top": titleHeight + "px"
    });

    jQuery("#orgdiagram").css({
        "left": "200px",
        "width": (bodyWidth - 200) + "px",
        "height": (bodyHeight - titleHeight) + "px",
        "top": titleHeight + "px"
    });
}

/**
 * ZOOM
 */
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

    data.element.find("[name=photo]").attr({
        "src": itemConfig.image,
        "alt": itemConfig.title
    });
    data.element.find("[name=titleBackground]").css({
        "background": itemConfig.itemTitleColor
    });

    data.element.find("[name=label]").text(itemConfig.percent * 100.0 + '%');

    var fields = ["title", "description", "phone", "email"];
    for (var index = 0; index < fields.length; index++) {
        var field = fields[index];

        var element = data.element.find("[name=" + field + "]");
        if (element.text() != itemConfig[field]) {
            element.text(itemConfig[field]);
        }
    }
}

function getZoom0Template() {
    var result = new primitives.orgdiagram.TemplateConfig();
    result.name = "Zoom0";

    result.itemSize = new primitives.common.Size(100, 10);
    result.minimizedItemSize = new primitives.common.Size(3, 3);
    result.highlightPadding = new primitives.common.Thickness(2, 2, 2, 2);


    var itemTemplate = jQuery(
        '<div class="bp-item">' + '<div name="title" class="bp-item" style="top: 0px; left: 0px; width: 100px; height: 10px; font-size: 8px; text-align:center;"></div>' + '</div>'
    ).css({
        width: result.itemSize.width + "px",
        height: result.itemSize.height + "px"
    }).addClass("bp-item");
    result.itemTemplate = itemTemplate.wrap('<div>').parent().html();

    return result;
}

function getZoom1Template() {
    var result = new primitives.orgdiagram.TemplateConfig();
    result.name = "Zoom1";

    result.itemSize = new primitives.common.Size(120, 28);
    result.minimizedItemSize = new primitives.common.Size(3, 3);
    result.highlightPadding = new primitives.common.Thickness(2, 2, 2, 2);


    var itemTemplate = jQuery(
        '<div class="bp-item">' + '<div name="title" class="bp-item" style="top: 0px; left: 0px; width: 120px; height: 12px; font-size: 10px; text-align:center;"></div>' + '<div name="description" class="bp-item" style="top: 14px; left: 0px; width: 120px; height: 12px; font-size: 10px; text-align:center;"></div>' + '</div>'
    ).css({
        width: result.itemSize.width + "px",
        height: result.itemSize.height + "px"
    }).addClass("bp-item");
    result.itemTemplate = itemTemplate.wrap('<div>').parent().html();

    return result;
}

function getZoom2Template() {
    var result = new primitives.orgdiagram.TemplateConfig();
    result.name = "Zoom2";

    result.itemSize = new primitives.common.Size(140, 64);
    result.minimizedItemSize = new primitives.common.Size(3, 3);
    result.highlightPadding = new primitives.common.Thickness(2, 2, 2, 2);


    var itemTemplate = jQuery(
        '<div class="bp-item">' + '<div class="bp-item bp-photo-frame" style="top: 0px; left: 0px; width: 50px; height: 60px; overflow: hidden;">' + '<img name="photo" style="height:60px; width:50px;" />' + '</div>' + '<div name="title" class="bp-item" style="top: 2px; left: 56px; width: 84px; height: 12px; font-size: 10px; overflow: hidden;"></div>' + '<div name="email" class="bp-item" style="top: 14px; left: 56px; width: 84px; height: 12px; font-size: 10px; overflow: hidden;"></div>' + '<div name="description" class="bp-item" style="top: 28px; left: 56px; width: 84px; height: 62px; font-size: 10px; overflow: hidden;"></div>' + '</div>'
    ).css({
        width: result.itemSize.width + "px",
        height: result.itemSize.height + "px"
    }).addClass("bp-item");
    result.itemTemplate = itemTemplate.wrap('<div>').parent().html();

    return result;
}

function getZoom3Template() {
    var result = new primitives.orgdiagram.TemplateConfig();
    result.name = "Zoom3";

    result.itemSize = new primitives.common.Size(160, 86);
    result.minimizedItemSize = new primitives.common.Size(3, 3);
    result.highlightPadding = new primitives.common.Thickness(2, 2, 2, 2);


    var itemTemplate = jQuery(
        '<div class="bp-item bp-corner-all bt-item-frame">' + '<div name="titleBackground" class="bp-item bp-corner-all bp-title-frame" style="top: 2px; left: 2px; width: 156px; height: 18px; overflow: hidden; text-align:center;">' + '<div name="title" class="bp-item bp-title" style="top: 2px; left: 2px; width: 152px; height: 14px; font-size: 11px; overflow: hidden;">' + '</div>' + '</div>' + '<div class="bp-item bp-photo-frame" style="top: 22px; left: 2px; width: 50px; height: 60px; overflow: hidden;">' + '<img name="photo" style="height:60px; width:50px;" />' + '</div>' + '<div name="email" class="bp-item" style="top: 22px; left: 56px; width: 98px; height: 13px; font-size: 11px; overflow: hidden;"></div>' + '<div name="description" class="bp-item" style="top: 37px; left: 56px; width: 98px; height: 39px; font-size: 11px; overflow: hidden;"></div>' + '</div>'
    ).css({
        width: result.itemSize.width + "px",
        height: result.itemSize.height + "px"
    }).addClass("bp-item bp-corner-all bt-item-frame");
    result.itemTemplate = itemTemplate.wrap('<div>').parent().html();

    return result;
}

function getZoom4Template() {
    var result = new primitives.orgdiagram.TemplateConfig();
    result.name = "Zoom4";

    result.itemSize = new primitives.common.Size(220, 120);
    result.minimizedItemSize = new primitives.common.Size(3, 3);
    result.highlightPadding = new primitives.common.Thickness(2, 2, 2, 2);


    var itemTemplate = jQuery(
        '<div class="bp-item bp-corner-all bt-item-frame">' + '<div name="titleBackground" class="bp-item bp-corner-all bp-title-frame" style="top: 2px; left: 2px; width: 216px; height: 20px; overflow: hidden;">' + '<div name="title" class="bp-item bp-title" style="top: 3px; left: 6px; width: 208px; height: 18px; overflow: hidden;">' + '</div>' + '</div>' + '<div class="bp-item bp-photo-frame" style="top: 26px; left: 2px; width: 50px; height: 60px; overflow: hidden;">' + '<img name="photo" style="height:60px; width:50px;" />' + '</div>' + '<div name="phone" class="bp-item" style="top: 26px; left: 56px; width: 162px; height: 18px; font-size: 12px; overflow: hidden;"></div>' + '<div name="email" class="bp-item" style="top: 44px; left: 56px; width: 162px; height: 18px; font-size: 12px; overflow: hidden;"></div>' + '<div name="description" class="bp-item" style="top: 62px; left: 56px; width: 162px; height: 36px; font-size: 10px; overflow: hidden;"></div>' + '</div>'
    ).css({
        width: result.itemSize.width + "px",
        height: result.itemSize.height + "px"
    }).addClass("bp-item bp-corner-all bt-item-frame");
    result.itemTemplate = itemTemplate.wrap('<div>').parent().html();

    return result;
}