$(document).ready(function() {
	//��ȡֵ
	var excApplyId = $("#excApplyId").val();
	var excApplyCode = $("#excApplyCode").val();

	if(excApplyCode){
		//��ֵת��Ϊ����
		var excApplyIdArr = excApplyId.split(',');
		var excApplyCodeArr = excApplyCode.split(',');

		var str = '';
		var strArr = [];
		for(var i = 0;i < excApplyCodeArr.length ; i++){
			strArr[i] = "<a href='javascript:void(0)' " +
				"onclick='" +
				"showThickboxWin(\"?model=engineering_exceptionapply_exceptionapply&action=toView&id=" +
				excApplyIdArr[i] + '&skey=' + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=480&width=800\")'>"+
				excApplyCodeArr[i] +"</a>";
		}
		$("#showExcApplyView").html(strArr.toString());
	}
})