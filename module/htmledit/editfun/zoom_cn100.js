strHTML=document.forms[0].newsContent.value;
document.write
(
'<iframe ID="editor" NAME="editor" style="width: 100%; height:200;display:none"></iframe>'
);
document.write
('<div id="switchDiv" style="padding: 0;margin: 0;width: 100%"><table border="0" cellspacing="0" cellpadding="0" width="100%" style="background-color:#E3E3E3">'
);
document.write
(
'<tr align="center"><td width="7%"><span id="status1" onClick="switchstatus(1)" style="font-size:12px; cursor:hand; background-color:#ffffff">普通</span></td><td width="7%"><span id="status2" onClick="switchstatus(2)" style="font-size:12px; cursor:hand">HTML</span></td><td width="7%"><span id="status3" onClick="switchstatus(3)" style="font-size:12px; cursor:hand">预览</td>');
document.write('<td><img src="../../module/htmledit/images/scrolll.gif" height=15 width=24></td><td width="75%" style="background-color:white;filter:Alpha(opacity=50);">&nbsp; </td><td><img src="../../module/htmledit/images/scrollr.gif" height=15 width=24></td></tr></table></div>'
);

function switchstatus(flag){
	document.frames.editor.frames.edit1.swapModes(flag);
	var i;
	for(i = 1; i < 4; i ++) {
		if(i == flag) document.all["status" + i].style.backgroundColor = "#ffffff";
		else document.all["status" + i].style.backgroundColor = "#E3E3E3";
	}
}

function winhidden(){
if (confirm("您确信要放弃所有改动退出吗？\n退出后将无法保存您所做的改动。")){
document.all.editor.src = "";
window.close();
}
}


function win_init(){
document.all.editor.src = "../../module/htmledit/edit.php?langtype=cn";
window.status = "程序载入中，请等待……";
}


function save(){
document.frames.editor.savefile();
}
window.onload = win_init

function UploadComplete(URL){
if ((URL != null) && (URL != ""))
if (URL.indexOf(":") == -1)
doFormat("InsertImage", "http://" + URL);
else			
doFormat("InsertImage", URL);
document.all.UploadImg.style.display = "none";
document.forms["upload"].reset();
}
