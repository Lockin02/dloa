$(document).ready(function() {
	$("#chanceView").hide();
	$("#chanceViewLink").hide();
	var actType=$("#actType").val();
	if(actType!=''){
		$("#chanceViewLink").show();
	}else{
		$("#chanceView").show();
	}
	validate({
				"projectName" : {
					required : true
				},
				"appDate" : {
					required : true
				}
			});
$("#appUserName").yxselect_user({
		hiddenId : 'appUserId',
//		isGetDept : [true, "userDeptId", "userDeptName"],
		event : {
			select : function(e, returnValue) {
				if (returnValue) {
					$('#appUserName').val(returnValue.userName)
					$('#appDeptId').val(returnValue.userDeptId)
					$('#appDeptName').val(returnValue.userDeptName)
				}
			}
		}
	});
$("#productListInfo").yxeditgrid({
		objName : 'apply[list]',
		url : 'index1.php?model=stockup_apply_applyProducts&action=getJsonEdit',
        param : {
            appId : $("#id").val()
        },
		dir : 'ASC',
		realDel : true,
		type : 'view',
		colModel : [{
					display : '�������ƣ���Ʒ��',
					name : 'productName',
					type : 'txt',
					width : 120,
					validation : {
						required : true
					}
				},{
					display : '��ƷID',
					name : 'productId',
					type:'hidden'
				},{
					display : '��ƷCODE',
					name : 'productCode',
					type:'hidden'
				},{
					display : '����',
					name : 'productNum',
					type : 'txt',
					width : 50,
					validation : {
						required : true
					}
				},{
					display : '��Ʒ����',
					name : 'productConfig',
					type : 'txt',
					width : 250
//					validation : {
//						required : true
//					}
				},{
					display : '������������',
					name : 'exDeliveryDate',
					type : 'date',
					width : 80,
					validation : {
						required : true
					}
				},{
					display : '��ע',
					name : 'remark',
					type : 'txt',
					width : 140
				}]
	});













})
