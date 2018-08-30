$(document).ready(function(){
	//��ʼ��ʡ�ݳ�����Ϣ
	initCity();

	//��ظ����˳�ʼ��
	//��������
	$("#areaManager").yxselect_user({
		hiddenId : 'areaManagerId',
		formCode : 'esmAreaManager'
	});
	//��������
	$("#techManager").yxselect_user({
		hiddenId : 'techManagerId',
		formCode : 'esmTechManager'
	});
	//���۸�����
	$("#salesman").yxselect_user({
		hiddenId : 'salesmanId',
		formCode : 'esmSalesman'
	});
	//�з�������
	$("#rdUser").yxselect_user({
		hiddenId : 'rdUserId',
		formCode : 'esmRdUser'
	});
	
//	//���ù�������
//	$("#deptName").yxselect_dept({
//		hiddenId : 'deptId'
//	});

	//����������Ⱦ
	$("#toolType").yxcombogrid_datadict({
		height : 250,
		valueCol : 'dataName',
		hiddenId : 'toolTypeHidden',
		gridOptions : {
			isTitle : true,
			param : {"parentCode":"GCGJLX"},
			showcheckbox : true,
			event : {
				'row_dblclick' : function(e, row, data){

				}
			},
			// ��������
			searchitems : [{
				display : '����',
				name : 'dataName'
			}]
		}
	});

	//��ѡ���´�
	$("#officeName").yxcombogrid_office({
		hiddenId : 'officeId',
		height : 300,
		gridOptions : {
			showcheckbox : false,
			isTitle : true,
			event : {
				'row_dblclick' : function(e, row, data){
					$("#deptId").val(data.feeDeptId);
					$("#deptName").val(data.feeDeptName);
					$("#productLine").val(data.productLine);
				}
			}
		}
	});

	//��ѡ��Ŀ����
	$("#managerName").yxselect_user({
		hiddenId : 'managerId',
		formCode : 'esmprojectAdd'
	});
	
	//��������
	$("#deptName").yxselect_dept({
		hiddenId : 'deptId'
	});

	/**
	 * ��֤��Ϣ
	 */
	validate({
		"officeName" : {
			required : true
		},
		"projectName" : {
			required : true
		},
		"managerName" : {
			required : true
		},
		"planBeginDate" : {
			required : true
		},
		"planEndDate" : {
			required : true
		},
		"expectedDuration" : {
			required : true
		},
		"deptName" : {
			required : true
		},
		"category" : {
			required : true
		}
	});

	/**
	 * ���Ψһ����֤
	 */
	var url = "?model=engineering_project_esmproject&action=checkRe1" +
        "3peat";
	$("#projectCode").ajaxCheck({
		url : url,
		alertText : "* �ñ���Ѵ���",
		alertTextOk : "* �ñ�ſ���"
	});
	
	// �¼���
	$("#productLine").bind('change', initProjectCode);
	$("#category").bind('change', initProjectCode);
	$("#workRate").bind('blur', initProjectCode);
});

//��Ŀ��Ż�ȡ
function initProjectCode() {
	var productLineObj = $("#productLine");
	var moduleCode = $("#moduleCode").val();
	var category = $("#category").find("option:selected").attr('e1');
	var workRate = $("#workRate").val();
	if (category && category != '' && workRate != "" && moduleCode) {
		var projectCode = $("#contractCode").val() + moduleCode +
			category;

		$.ajax({
			type: "POST",
			url: "?model=engineering_project_esmproject&action=getProjectNum",
			data: {
				contractId: $("#contractId").val(),
				contractType: $("#contractType").val(),
				productLine: productLineObj.val()
			},
			async: false,
			success: function(data) {
				if (parseInt(workRate) != 100 && parseInt(data) == 0) {
					projectCode += 1;
				} else {
					if (parseInt(data) == 0) {
						projectCode += parseInt(data);
					} else {
						projectCode += parseInt(data) + 1;
					}
				}
			}
		});

		$("#projectCode").val(projectCode);
	}
}