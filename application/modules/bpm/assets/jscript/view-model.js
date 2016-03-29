//---POPUP style
//---available styles are:
//  all-azure, all-black, all-blue, all-green, all-grey, all-orange, all-violet, all-yellow,
//  azure,black,blue,green,grey,orange,violet,yellow
var popup_style='all-black';
var highlight_border='2px dotted grey';

//---set speed for painting routine in mili seconds
var HSPEED=300;

var SOUP_EXTRA={
    dropShadow:true,
    width: '300px',
    innerHtmlStyle: {
                    color:'#FFF',
                    'text-align':'left'
                }
            };

///////////////////////////////////////////////////////////////////////////////
////////////////////////////    URLS //////////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////
//---model URL
var MODEL_URL=base_url+'bpm/repository/load/model/'+idwf;
//---tokens to be marked
//var TOKENS_URL=base_url+'bpm/repository/get_tokens/model/'+idwf+'/'+idcase;

////////////////////////////////////////////////////////////////////////////////
///////////////////////////FUNCTIONS////////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
var MOUSE_OVER=function(event){
    resourceId=$(this).attr('resourceId');
    $(this).css("border",highlight_border);

    //console.log('mouseOn:'+resourceId);
    //---turn off all others

    url=base_url+'bpm/repository/get_comments/model/'+idwf+'/'+resourceId;
    var div=$(this);
//                    $.get(url,'',function(data){
//                        div.SetBubblePopupInnerHtml(data, false);
//                    });
}

var MOUSE_OUT=function(event){
    //$(this).RemoveBubblePopup()
    resourceId=$(this).attr('resourceId');
    $(this).css('border','');
//console.log('mouseOff:'+resourceId);

}
var LAYER_CLICK=function(event){
    event.preventDefault();
    event.stopImmediatePropagation();
    div=$(this);
    resourceId=div.attr('resourceId');
    type=$(this).attr('type');
    //console.log('clickOn:'+resourceId+':'+type);
    if(div.HasBubblePopup()){
        if(div.IsBubblePopupOpen()){
            div.HideBubblePopup();
        }else {
            div.ShowBubblePopup();
        }

    }


}

var POPUP_TEXT=function(token,shape){
                //popup_text=
            popup_text='';
            popup_text='Doc:'+shape.properties.documentation;
             
            return popup_text;
}
////////////////////////////////////////////////////////////////////////////////
///////////////////////DOCUMENT READY///////////////////////////////////////////
////////////////////////////////////////////////////////////////////////////////
$(document).ready(function(){
    
    $('#div-svg').load(globals.module_url+'repository/svg/' + globals.idwf,null,function(){
        panzoom_init();
        //fix links
        $('#svg-box a').each(
            function(e){
                link=globals.module_url+'repository/view/'+$(this).attr('xlink:href').replace('editor.xhtml#','');
                console.log(link);
                $(this).attr('xlink:href',link);
                // $(this).attr();
                
            }
            );
    });
    
    //---1.convert svg to an object 4 jquery
    // svg=$("#svg-box").svg();

    // //---2. load model data
    // model=loadSync(MODEL_URL);

    // //---3. load tokens
    // thiscase={};

    // //---4. make overlay 4 tooltips
    // overlay(model);

    // //---5. Add popups to divs
    // add_popup();

    //---6. Fires highlight routine
    //intervalID=window.setInterval(paintme,HSPEED);
});