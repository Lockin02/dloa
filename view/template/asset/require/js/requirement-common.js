$(document).ready(function() {
	//�ʼ���Ⱦ
	$("#TO_NAME").yxselect_user({
		hiddenId : 'TO_ID',
		mode : 'check',
		formCode : 'requirement'
	});
	//��֤��Ϣ
	validate({
		"applyName" : {
			required : true
		},
		"applyDeptName" : {
			required : true
		},
		"userName" : {
			required : true
		},
		"userPhone" : {
			required : true
		},
		"userDeptName" : {
			required : true
		},
		"userCompanyName" : {
			required : true
		},
		"beginDate" : {
			required : true
		},
		"returnDate" : {
			required : true
		},
		"requireType" : {
			required : true
		}
	});
	//�ʼ������˼�id
	var sendName = $('#TO_NAME').val();
	var sendId = $('#TO_ID').val();
	//ʹ����
	$("#userName").yxselect_user({
		hiddenId : 'userId',
		isGetDept : [true, "userDeptId", "userDeptName"],
		event : {
			select : function(e, returnValue) {
				if (returnValue) {
					$('#userCompanyCode').val(returnValue.companyCode);
					$('#userCompanyName').val(returnValue.companyName);
					$('#TO_NAME').val(sendName+','+$('#userName').val());
					$('#TO_ID').val(sendId+','+$('#userId').val());
				}
			}
		}
	});
	//ʹ�ò���
	$("#userDeptName").yxselect_dept({
		hiddenId : 'userDeptId',
		event : {
			'selectReturn' : function(e, data) {
				$("#userName").yxselect_user("remove").val('');
				$("#userName").yxselect_user({
					hiddenId : 'userId',
					deptIds : data.val
				});
			},
			'clearReturn' : function(e){
				$("#userName").yxselect_user("remove");
				$("#userName").yxselect_user({
					hiddenId : 'userId'
				});
			}
		}
	});
	//���ݳ������ж��Ƿ���Ҫ��д�黹ʱ��
	$('#requireType').change(function(){
		if( $(this).val() == 1 ){//ʹ������Ϊ����,ȥ������
			$('#returnDate').val('');
			$('#returnDate').removeClass("validate[required]").parent('td').prev('td').find('span').css('color','black');
		}else{
			$('#returnDate').addClass("validate[required]").parent('td').prev('td').find('span').css('color','blue');
		}
	});
	$('#useCode').change(function(){
		$('#useName').val($('#useCode').get(0).options[$('#useCode').get(0).selectedIndex].innerText);
	});
})

//��Ŀ��ű����ж�
function removeProject() {
	$("#projectCode").yxcombogrid_rdprojectfordl("remove");
	$("#projectCode").yxcombogrid_esmproject("remove");
	$("#projectCode").val("");
	$("#projectId").val("");
	projectSelect();
}

// ��ʼ����Ŀѡ��
function projectSelect() {
	$val = $("#useCode").find("option:selected").val();
	if ($val == 'ZCYT-YFL') {
		// �з���Ŀ��Ⱦ��
		$("#projectCode").removeClass('readOnlyTxtNormal')
				.addClass('txt validate[required]');
		$("#projectCode").yxcombogrid_rdprojectfordl({
			hiddenId : 'projectId',
			nameCol : 'projectCode',
			isShowButton : false,
			height : 250,
			isFocusoutCheck : false,
			gridOptions : {
				param : {
					'is_delete' : 0
				},
				isTitle : true,
				showcheckbox : false,
				event : {
					'row_dblclick' : function(e, row, data) {
						$("#projectName").val(data.projectName);
					}
				}
			}
		});
		$(".myspan").show();
	} else if ($val == 'ZCYT-GCFW') {
		// ������Ŀ��Ⱦ
		$("#projectCode").removeClass('readOnlyTxtNormal')
				.addClass('txt validate[required]');
		$("#projectCode").yxcombogrid_esmproject({
			hiddenId : 'projectId',
			nameCol : 'projectCode',
			isShowButton : false,
			height : 250,
			gridOptions : {
				isTitle : true,
				showcheckbox : false,
				event : {
					'row_dblclick' : function(e, row, data) {
						$("#projectName").val(data.projectName);
						$("#projectType").val(data.category);
					}
				}
			}
		});
		$(".myspan").show();
	} else {
		$("#projectCode").removeClass('txt').addClass('readOnlyTxtNormal');
		$("#projectCode").removeClass('validate[required]');
		$(".myspan").hide();
	}
}

//��ʼʱ�������ʱ�����֤
function timeCheck($t){
	if($t.value!=''){
		var s = plusDateInfo('beginDate','returnDate');
		if(s < 0) {
			alert("��ʼʱ�䲻�ܱȹ黹ʱ����");
			$t.value = "";
			return false;
		}
	}
}

// ���ݴӱ�Ĳ�ֵ��̬����Ӧ���ܽ��
function countAmount() {
	// ��ȡ��ǰ����������Ƭ���ʲ���
	var curRowNum = $("#itemTable").yxeditgrid("getCurRowNum")
	var rowAmountVa = 0;
	var cmps = $("#itemTable").yxeditgrid("getCmpByCol", "expectAmount");
	cmps.each(function() {
		rowAmountVa = accAdd(rowAmountVa, $(this).val(), 2);
	});
	$("#expectAmount").val(rowAmountVa);
	$("#expectAmount_v").val(moneyFormat2(rowAmountVa));
	return true;
}
