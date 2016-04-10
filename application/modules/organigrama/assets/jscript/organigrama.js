var m_timer;

 $(window).load(function() {
     //---Use this as start template
     var items = [
         new primitives.orgdiagram.ItemConfig({
             id: 0,
             parent: null,
             title: "Nuevo Organigrama",
             description: "VP, Public Sector",
             image: globals.module_url+"assets/images/photos/n.png"
         }),

     ];

     // options.hasSelectorCheckbox = primitives.common.Enabled.True;

     /* Edit Mode */
     /**
     var editModes = jQuery("#editMode");
     editModes.append(jQuery('<br/><div id="radioEditMode"><input type="radio" id="radioEdit" name="editMode" value="1" checked="checked"><label for="radioEdit">Edit</label><input type="radio" id="radioView" name="editMode" value="0"><label for="radioView">View</label></div>'));

     jQuery("#radioEditMode").buttonset();
     jQuery("input:radio[name=editMode]").change(function() {
         Update();
     });
     */
     var orgEditorConfig = new primitives.orgeditor.Config();
     orgEditorConfig.editMode = true;
     orgEditorConfig.items =items;
     orgEditorConfig.cursorItem =0;
     orgEditorConfig.hasSelectorCheckbox=primitives.common.Enabled.False;
     /**
      * Load via ajax
      */

     orgEditorConfig.onSave = function() {
         // var config = jQuery("#centerpanel").bpOrgEditor("option");
         /*Read config option and store chart changes */
     };
     //LoadData();
     bpOrgEditor = jQuery("#orgdiagram").bpOrgEditor(orgEditorConfig);
     LoadData();
 });
  
 function LoadData(){
    /**
      * Load via ajax
      */
      $.ajax({
       'url':globals.module_url+'get/'+globals.idorg,
       'method':'post',
       'dataType':'json',
       'success':function(data,status){
        var orgEditorConfig = new primitives.orgeditor.Config();
        orgEditorConfig.editMode = true
         // options=;
         // orgEditorConfig.items=data.data.items;
         // options.cursorItem = options.items[0] != null ? options.items[0].id : null;
				     bpOrgEditor.bpOrgEditor(data.data);
				     bpOrgEditor.bpOrgEditor("update");
				     // bpOrgEditor._trigger("onSave");
         //color pickers
       }

      });
 }
 /**
  * Helper Functions
  */ 
 $(window).resize(function () {
				onWindowResize();
			});
function onWindowResize() {
			if (m_timer == null) {
				m_timer = window.setTimeout(function () {
					bpOrgEditor.bpOrgEditor("update", primitives.common.UpdateMode.Refresh)
					window.clearTimeout(m_timer);
					m_timer = null;
				}, 300);
			}
		}
		
 function Update(selector, updateMode) {
     if (bpOrgEditor != null) {
         bpOrgEditor.bpOrgEditor("option", GetOrgDiagramConfig());
         bpOrgEditor.bpOrgEditor("update");
     }
 }

 function GetOrgDiagramConfig() {
     var editMode = parseInt(jQuery("input:radio[name=editMode]:checked").val(), 10);

     return {
         editMode: (editMode == 1)
     };
 }