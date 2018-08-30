/**
 * 表单模块收缩--div
 * @param {} btnId
 * @param {} tblId
 */
function showAndHideDiv(btnId,tblId){
	//缓存表格对象
	var tblObj = $("div[id^='" + tblId + "']");
	//如果表格当前是隐藏状态，则显示
	if(tblObj.is(":hidden")){
		tblObj.show();
		$("#" + btnId).attr("src", "images/icon/info_up.gif");
	}else{
		tblObj.hide();
		$("#" + btnId).attr("src", "images/icon/info_right.gif");
	}
}