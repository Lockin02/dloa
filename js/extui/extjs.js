/**
 * ��ģ������--div
 * @param {} btnId
 * @param {} tblId
 */
function showAndHideDiv(btnId,tblId){
	//���������
	var tblObj = $("div[id^='" + tblId + "']");
	//������ǰ������״̬������ʾ
	if(tblObj.is(":hidden")){
		tblObj.show();
		$("#" + btnId).attr("src", "images/icon/info_up.gif");
	}else{
		tblObj.hide();
		$("#" + btnId).attr("src", "images/icon/info_right.gif");
	}
}