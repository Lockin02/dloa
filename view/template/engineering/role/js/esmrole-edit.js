$(document).ready(function() {
	var memberObj = $("#memberName");
	if($("#isManager").val() == "0" || memberObj.val() == ""){
		//��Ŀ��Ա
		memberObj.yxselect_user({
			hiddenId : 'memberId',
			formCode : 'esmroleEdit',
			mode : 'check',
			event : {
				select : function(e, returnValue) {
					if (returnValue) {
						$("#maxfixedRateInfo").parents("tr").show();
						getMaxFixedRate();
					}
				}
			}
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
	//��֤��Ŀ��ɫ�Ƿ�ɱ༭�̶�Ͷ�����
	$.ajax({
	    type: "POST",
	    url: "?model=engineering_role_esmrole&action=isEditFixedRate",
	    data: {'id' : $("#id").val()},
	    async: false,
	    success: function(data){
	    	//0Ϊ�ɱ༭
	   		if(data == 0){
	   			$("#fixedRate").attr('class','txt');
	   			$("#fixedRate").attr('readonly',false);
	   			$("#fixedRateInfo").hide();	
			}
		}
	});
	//��ʼ���������̶�Ͷ�����
	getMaxFixedRate();
})
//����֤
function checkform(){
	if($("#roleName").val() == ""){
		alert("��ɫ���Ʋ���Ϊ��");
		return false;
	}
	var rs = true;
	var memberId = $("#memberId").val();//��ǰ��Աid
	var orgMemberId = $("#orgMemberId").val();//ԭ��Աid
	var maxfixedRate = $("#maxfixedRate").val();//�������̶�Ͷ�����
	if(memberId != ""){	//��Ա���Ʋ�Ϊ��
		if(memberId != orgMemberId){
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
//			$.ajax({
//			    type: "POST",
//			    url: "?model=engineering_member_esmmember&action=memberCanSet",
//			    data: {"projectId" : $("#projectId").val() , "roleId" : $("#id").val() , "orgMemberId" : orgMemberId , 'memberId' : memberId},
//			    async: false,
//			    success: function(data){
//			   		if(data != "1"){
//			   			var rsObj = eval("(" + data + ")");
//						switch(rsObj.val){
//							case '2' :
//								alert(rsObj.member + '�Ѿ���������Ŀ�У����ܼ������'); rs = false; break;
//							case '3' :
//								alert(rsObj.member + '�Ѵ��������Ŀ���񣬲���ɾ��'); rs = false; break;
//							case '4' :
//								alert(rsObj.member + '�Ѿ�¼����Ŀ��־������ɾ��'); rs = false; break;
//							default : break;
//						}
//			   	    }
//				}
//			});
		}
		//��֤�������̶�Ͷ�����
		if($("#fixedRate").val()<0 || ($("#fixedRate").val()-maxfixedRate)>0){
			alert("������0~"+maxfixedRate+"֮�������");
			$("#fixedRate").val("").focus();
			rs = false;
		}	
	}

	return rs;
}
//��ȡ�������̶�Ͷ�����
function getMaxFixedRate(){
	$.ajax({
	    type: "POST",
	    url: "?model=engineering_role_esmrole&action=getMaxFixedRate",
	    data: {'projectId' : $("#projectId").val(),'memberId' : $("#memberId").val()},
	    async: false,
	    success: function(data){
	    	if(data){
		    	//��Ա���Ʋ�Ϊ��
		    	if($("#memberId").val()!=""){
		    		$("#maxfixedRateInfo").empty().append("����̶�Ͷ��������Ϊ��"+data+"%");
		    		$("#maxfixedRate").val(data);
		    	}else{
		    		$("#maxfixedRateInfo").parents("tr").hide();
		    	}
	    	}
		}
	});
}