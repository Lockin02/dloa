$(document).ready(function() {
	var memberObj = $("#memberName");
	if($("#isManager").val() == "0" || memberObj.val() == ""){
		//��Ŀ��Ա
		memberObj.yxselect_user({
			hiddenId : 'memberId',
			formCode : 'esmroleEdit',
			mode : 'check'
		});
	}else{
		//��Ŀ����
		memberObj.attr('class','readOnlyTxtMiddleLong');
		$("#roleName").attr('class','readOnlyTxtNormal');
		$("#roleName").attr('readonly',true);
		$("#appendInfo").show();
	}

	// ��̬�����������
//	$("#activityName").yxcombogrid_esmactivity({
//		hiddenId : 'activityId',
//		isShowButton : false,
//		height : 250,
//		gridOptions : {
//			isShowButton : true,
//			isTitle : true,
//			param  : {'isLeaf' : 1,'bigID' : '0' , 'projectId' : $("#projectId").val()},
//			event : {
//				'row_dblclick' : function(e, row, data) {
//					$("#coefficient").val(data.coefficient);
//					$("#price").val(data.price);
//
//					calPerson();
//				}
//			}
//		}
//	});
});

//����֤
function checkform(){
	if($("#roleName").val() == ""){
		alert("��ɫ���Ʋ���Ϊ��");
		return false;
	}

	var rs = true;
	var memberId = $("#memberId").val();
	if(memberId !=""){
		$.ajax({
			type: "POST",
		    url: "?model=engineering_member_esmmember&action=checkHasManager",
		    data: {"projectId" : $("#projectId").val(), 'memberId' : memberId},
		    async: false,
		    success: function(result){
		    	if(result == 1){
		    		alert('���ܰ���Ŀ��������������Ա��ɫ');
		    		rs = false;
		    	}
		    }
		});
//		$.ajax({
//		    type: "POST",
//		    url: "?model=engineering_member_esmmember&action=memberIsExsist",
//		    data: {"projectId" : $("#projectId").val() , 'memberId' : memberId},
//		    async: false,
//		    success: function(data){
//		   		if(data != "0"){
//					alert(data + '�Ѿ���������Ŀ�У����ܼ������');
//					rs = false;
//		   	    }
//			}
//		});
	}
	return rs;
}