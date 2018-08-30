<?php
@session_start( );
include( $Htmledit_Path."/../includes/db.inc.php" );
include( $Htmledit_Path."/../includes/config.php" );
if ( $Htmledit_Tag != "Htmledit" )
{
    exit( );
}
?>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<title>HTML编辑器</title>
<link rel="STYLESHEET" type="text/css" href="../css/css.css">
<script language=javascript>
var sInitForeColor = '#000000';
var sInitBackColor = '#ffffff';

function callColorDlg(src){
    if('ForeColor' == src) sInitColor = sInitForeColor;
    if('BackColor' == src) sInitColor = sInitBackColor;
    if(sInitColor == null)
        var sColor = dlgHelper.ChooseColorDlg();
    else
        var sColor = dlgHelper.ChooseColorDlg(sInitColor.substring(1,sInitColor.length));
    sColor = sColor.toString(16);
    if (sColor.length < 6) {
         var sTempString = "000000".substring(0,6-sColor.length);
        sColor = sTempString.concat(sColor);
    }
    clor='#' + sColor;
    doFormat(src,clor);
    if('ForeColor' == src) sInitForeColor = clor;
    if('BackColor' == src) sInitBackColor = clor;
}
</script>

<script language="JScript">
document.onmouseover = doOver;
document.onmouseout  = doOut;
document.onmousedown = doDown;
document.onmouseup   = doUp;
var loaded = 0;

function doOver() {
    var toEl = getReal(window.event.toElement, "className", "coolButton");
    var fromEl = getReal(window.event.fromElement, "className", "coolButton");
    if (toEl == fromEl) return;
    var el = toEl;
    var cDisabled = el.cDisabled;
    cDisabled = (cDisabled != null); 
    if (el.className == "coolButton")
        el.onselectstart = new Function("return false");
    if ((el.className == "coolButton") && !cDisabled) {
        makeRaised(el);
        makeGray(el,false);
    }
}
function doOut() {
    var toEl = getReal(window.event.toElement, "className", "coolButton");
    var fromEl = getReal(window.event.fromElement, "className", "coolButton");
    if (toEl == fromEl) return;
    var el = fromEl;
    var cDisabled = el.cDisabled;
    cDisabled = (cDisabled != null);
    var cToggle = el.cToggle;
    toggle_disabled = (cToggle != null);
    if (cToggle && el.value) {
        makePressed(el);
        makeGray(el,true);
    }
    else if ((el.className == "coolButton") && !cDisabled) {
        makeFlat(el);
        makeGray(el,true);
    }
}
function doUp() {
    el = getReal(window.event.srcElement, "className", "coolButton");
    var cDisabled = el.cDisabled;
    cDisabled = (cDisabled != null);
    if ((el.className == "coolButton") && !cDisabled) {
        makeRaised(el);
    }
}
function getReal(el, type, value) {
    temp = el;
    while ((temp != null) && (temp.tagName != "BODY")) {
        if (eval("temp." + type) == value) {
            el = temp;
            return el;
        }
        temp = temp.parentElement;
    }
    return el;
}
function findChildren(el, type, value) {
    var children = el.children;
    var tmp = new Array();
    var j=0;    
    for (var i=0; i<children.length; i++) {
        if (eval("children[i]." + type + "==\"" + value + "\"")) {
            tmp[tmp.length] = children[i];
        }
        tmp = tmp.concat(findChildren(children[i], type, value));
    }    
    return tmp;
}
function disable(el) {
    if (document.readyState != "complete") {
        window.setTimeout("disable(" + el.id + ")", 100);
        return;
    }    
    var cDisabled = el.cDisabled;    
    cDisabled = (cDisabled != null);
    if (!cDisabled) {
        el.cDisabled = true;
        el.innerHTML = '<span style="background: buttonshadow; width: 100%; height: 100%; text-align: center;">' +
                        '<span style="filter:Mask(Color=buttonface) DropShadow(Color=buttonhighlight, OffX=1, OffY=1, Positive=0); height: 100%; width: 100%%; text-align: center;">' +
                        el.innerHTML + '</span>' + '</span>';
        if (el.onclick != null) {
            el.cDisabled_onclick = el.onclick;
            el.onclick = null;
        }
    }
}
function enable(el) {
    var cDisabled = el.cDisabled;    
    cDisabled = (cDisabled != null);    
    if (cDisabled) {
        el.cDisabled = null;
        el.innerHTML = el.children[0].children[0].innerHTML;
        if (el.cDisabled_onclick != null) {
            el.onclick = el.cDisabled_onclick;
            el.cDisabled_onclick = null;
        }
    }
}
function addToggle(el) {
    var cDisabled = el.cDisabled;    
    cDisabled = (cDisabled != null);    
    var cToggle = el.cToggle;    
    cToggle = (cToggle != null);
    if (!cToggle && !cDisabled) {
        el.cToggle = true;        
        if (el.value == null)
            el.value = 0;        
        if (el.onclick != null)
            el.cToggle_onclick = el.onclick;
        else 
            el.cToggle_onclick = "";
        el.onclick = new Function("toggle(" + el.id +"); " + el.id + ".cToggle_onclick();");
    }
}
function removeToggle(el) {
    var cDisabled = el.cDisabled;    
    cDisabled = (cDisabled != null);    
    var cToggle = el.cToggle;    
    cToggle = (cToggle != null);    
    if (cToggle && !cDisabled) {
        el.cToggle = null;
        if (el.value) {
            toggle(el);
        }
        makeFlat(el);        
        if (el.cToggle_onclick != null) {
            el.onclick = el.cToggle_onclick;
            el.cToggle_onclick = null;
        }
    }
}
function toggle(el) {
    el.value = !el.value;    
    if (el.value)
        el.style.background = "URL(/images/tileback.gif)";
    else
        el.style.backgroundImage = "";
}
function makeFlat(el) {
    with (el.style) {
        background = "";
        border = "1px solid buttonface";
        if ((el.id != "more") && (el.id != "fore"))
            padding      = "1px";
    }
}
function makeRaised(el) {
    with (el.style) {
        borderLeft   = "1px solid buttonhighlight";
        borderRight  = "1px solid buttonshadow";
        borderTop    = "1px solid buttonhighlight";
        borderBottom = "1px solid buttonshadow";
        if ((el.id != "more") && (el.id != "fore"))
            padding      = "1px";
    }
}
function makePressed(el) {
    with (el.style) {
        borderLeft   = "1px solid buttonshadow";
        borderRight  = "1px solid buttonhighlight";
        borderTop    = "1px solid buttonshadow";
        borderBottom = "1px solid buttonhighlight";
        if ((el.id != "more") && (el.id != "fore")){
            paddingTop    = "2px";
            paddingLeft   = "2px";
            paddingBottom = "0px";
            paddingRight  = "0px";
        }
    }
}
function makeGray(el,b) {
}
document.write("<style>");
document.write(".coolBar    {background: buttonface;border-top: 1px solid buttonhighlight;    border-left: 1px solid buttonhighlight;    border-bottom: 1px solid buttonshadow; border-right: 1px solid buttonshadow; padding: 2px; font: menu;}");
document.write(".coolButton {border: 1px solid buttonface; padding: 1px; text-align: center; cursor: default;}");
document.write("</style>");
var activeCSS            = "border: 1 solid buttonface; color: windowtext; cursor: text;";
var inactiveCSS            = "border: 1 solid window; cursor: hand; color: red;";
var validTextColor        = "windowtext";
var invalidTextColor    = "buttonshadow";

function doFormat(what) {
    var eb = document.all.editbar;
    eb._editor.execCommand(what,arguments[1]);
}
function Format1(what,opt,which) {
    var eb = document.all.editbar;
    eb._editor.addpic(what,opt,which);
}
function doMark(str,tiaojian) {
    document.all.editbar._editor.specialtype(str,tiaojian);
}
function MTable() {
    document.all.editbar._editor.ModifyTable();
}
function MCell() {
    document.all.editbar._editor.ModifyCell();
}
function insrowabove(){
    document.all.editbar._editor.insrowabove();
}
function insrowbelow(){
    document.all.editbar._editor.insrowbelow();
}
function deleterow(){
    document.all.editbar._editor.deleterow();
}
function about(){
    document.all.editbar._editor.about();
}
function deletecol(){
    document.all.editbar._editor.deletecol();
}
function inscolafter(){
    //document
}