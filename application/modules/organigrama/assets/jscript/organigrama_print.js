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
            options.hasSelectorCheckbox = primitives.common.Enabled.False;
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
        }, 300);
    }
}
