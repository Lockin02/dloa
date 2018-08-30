$(document).ready(function(){
	$("#memberName").yxselect_user({
		formCode : 'esmmeberAdd',
		hiddenId : 'memberId',
		event : {
			'select' : function(e, returnValue) {
				//�ж���Ŀ���Ƿ��иó�Ա���������
				$.ajax({
				    type: "POST",
				    url: "?model=engineering_member_esmmember&action=checkMemberRepeat",
				    data: {"projectId" : $("#projectId").val() , 'memberId' : returnValue.val},
				    async: false,
				    success: function(data){
				   		if(data == '1'){
							alert('��Ŀ���Ѵ��ڸó�Ա����ѡ��������Ա');
							$("#memberName").val("");
							$("#memberId").val("");
				   	    }
					}
				});
			}
		}
	});

	/**
	 * ��֤��Ϣ
	 */
	validate({
//		"roleName" : {
//			required : true
//		},
		"memberName" : {
			required : true
		}
	});

	// ��̬����Ԥ��
	$("#personLevel").yxcombogrid_eperson({
		hiddenId : 'personLevelId',
		width : 380,
		height : 250,
		isShowButton : false,
		gridOptions : {
			isShowButton : true,
			showcheckbox : false,
			param  : {'status' : 0 ,'isLeaf' : 1},
			event : {
				'row_dblclick' : function(e, row, data) {
					$("#coefficient").val(data.coefficient);
					$("#price").val(data.price);

					calPerson();
				}
			}
		}
	});

	// ��̬��ӽ�ɫ����
	$("#roleName").yxcombogrid_esmrole({
		hiddenId : 'roleId',
		height : 250,
		gridOptions : {
			isShowButton : true,
			showcheckbox : false,
			param  : {'isLeaf' : 1,'bigID' : '0' , 'projectId' : $("#projectId").val()},
			event : {
				'row_dblclick' : function(e, row, data) {
//					$("#activityName").val(data.activityName);
//					$("#activityId").val(data.activityId);
				}
			}
		}
	});

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