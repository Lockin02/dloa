/**
 * ��ʼʡ������
 */
function initOffice() {
	//��ȡʡ�ݶ�Ӧ�İ��´�
	$.ajax({
		type: "POST",
		url: "?model=engineering_officeinfo_range&action=getOfficeInfoForId",
		data: {
			provinceId: $("#provinceId").val(),
			businessBelong: $("#businessBelong").val(),
			productLine: $("#productLine").val()
		},
		async: false,
		success: function(data) {
			if (data != "") {
				var dataObj = eval("(" + data + ")");
				$("#officeName").val(dataObj.officeName);
				$("#officeId").val(dataObj.officeId);
				$("#deptId").val(dataObj.feeDeptId);
				$("#deptName").val(dataObj.feeDeptName);
				$("#productLine").val(dataObj.productLine);
			}
		}
	});
}

//����������رղ���֤
function timeCheck($t) {
	var startDate = $('#planBeginDate').val();
	var endDate = $('#planEndDate').val();
	if (startDate == "" || endDate == "") {
		return false;
	}
	var s = DateDiff(startDate, endDate) + 1;
	if (s < 0) {
		alert("Ԥ�ƿ�ʼ���ܱ�Ԥ�ƽ���ʱ����");
		$t.value = "";
		return false;
	}
}

// ��ȡ���߿���ռ��
function initWorkRate() {
	$("#workRate").attr('readonly', true).removeClass('txt').addClass('readOnlyText');
	$.ajax({
		type: "POST",
		url: "?model=engineering_project_esmproject&action=getWorkrateCanUse",
		data: {
			contractId: $("#contractId").val(),
			contractType: $("#contractType").val(),
			productLine: $("#newProLine").val()
		},
		async: false,
		success: function(data) {
			var workRateObj = $("#workRate");
			if (workRateObj.val() * 1 == 0 || data * 1 < workRateObj.val() * 1) {
				workRateObj.val(data);
			}
			$("#remainWorkRate").val(data);
			workRateObj.attr('readonly', false).removeClass('readOnlyText').addClass('txt');
		}
	});

	// PK�ɱ�ռ��Ҳһ�����
	var contractId = $("#contractId").val();
	var paramArr = {
		projectId : $("#id").val(),
		productLine: $("#newProLine").val(),
		contratId : contractId,
		contractType : 'GCXMYD-01',
		chkType : 'pk'
	};
	$.ajax({
		type: "POST",
		url: "?model=engineering_project_esmproject&action=getPkrateCanUse",
		data: paramArr,
		async: false,
		success: function(data) {
			var pkEstimatesRate = (isNaN(Number(data)))? 0 : Number(data);
			$("#pkEstimatesRate").val(pkEstimatesRate);
			$("#pkEstimatesRate").attr("title",pkEstimatesRate);
			$("#pkEstimatesRate").attr("data-orgval",pkEstimatesRate);;
		}
	});
}

// ��Ŀ��Ż�ȡ
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

//����֤
function checkform() {
	var workRateObj = $("#workRate");
	var remainWorkRateObj = $("#remainWorkRate");
	var pkEstimatesRate = $("#pkEstimatesRate").val();
	var pkEstimatesRateMax = $("#pkEstimatesRate").attr("data-orgval");

	if (workRateObj.val() * 1 == 0) {
		alert('����ռ�Ȳ���Ϊ0');
		return false;
	}

	if (workRateObj.val() * 1 > remainWorkRateObj.val() * 1) {
		alert('����ռ���ѳ�����Χ');

		workRateObj.val(remainWorkRateObj.val());
		return false;
	}

	if (workRateObj.val() * 1 == remainWorkRateObj.val() * 1 && Number(pkEstimatesRate) != Number(pkEstimatesRateMax)){
		alert('���η��乤��ռ����ȫ����������ɣ�PK�ɱ�ռ�ȱ���ȫ�����䡣');

		$("#pkEstimatesRate").val(pkEstimatesRateMax);
		$("#pkEstimatesRate").focus();
		return false;
	}

	//��Ŀ���ΨһУ��
	var unRepeat = true;
	$.ajax({
		type: "POST",
		url: "?model=engineering_project_esmproject&action=checkIsRepeat",
		data: {projectCode: $("#projectCode").val()},
		async: false,
		success: function(data) {
			if (data == "1") {
				alert('��Ŀ����Ѵ���');
				unRepeat = false;
			}
		}
	});

	return unRepeat;
}

$(document).ready(function() {
	// ��������
	initOffice();

	// ��ѡ���´�
	$("#officeName").yxcombogrid_office({
		hiddenId: 'officeId',
		height: 250,
		gridOptions: {
			showcheckbox: false,
			param: {productLineArr: $("#productLineUse").val()},
			isTitle: true,
			event: {
				row_dblclick: function(e, row, data) {
					$("#deptId").val(data.feeDeptId);
					$("#deptName").val(data.feeDeptName);
					$("#productLine").val(data.productLine);
					initWorkRate();
				}
			}
		}
	});

	// �¼���
	$("#productLine").bind('change', initProjectCode);
	$("#category").bind('change', initProjectCode).bind('change', function() {
        // c�࿪Ʊ���ȣ�AB��������������
        if ($(this).val() == 'XMLBC') {
            $("#incomeType").val('SRQRFS-02');
        } else {
            $("#incomeType").val('SRQRFS-01');
        }
    });
	$("#workRate").bind('blur', initProjectCode);

	// ��ʼ�����߿���ռ��
	initWorkRate();

	// ��ѡ��Ŀ����
	$("#managerName").yxselect_user({
		hiddenId: 'managerId',
		mode: 'check',
		formCode: 'esmcharter'
	});

	// ��������
	$("#deptName").yxselect_dept({
		hiddenId: 'deptId'
	});

	// ��ʼ��������Ϣ
	initCity();

	$("#country").change(function() {
		if ($(this).find("option:selected").text() != '�й�') {
			//	alert('����');
			var cityUrl = "?model=system_procity_city&action=pageJson"; // ��ȡ�е�URL
			$("#province").val('32');
			var provinceId = 32;
			$.ajax({
				type: 'POST',
				url: cityUrl,
				data: {
					provinceId: provinceId,
					pageSize: 999
				},
				async: false,
				success: function(data) {
					$('#city').children().remove("option[value!='']");
					getCitys(data);
					$('#city').find("option[text='����']").attr("selected", true);
				}
			});
		}
	});

	// ��֤��Ϣ
	validate({
		projectCode: {//��Ŀ���
			required: true,
			length: [16, 16]
		},
		projectName: {//��Ŀ����
			required: true,
			length: [0, 100]
		},
		officeName: {//���´�
			required: true,
			length: [0, 20]
		},
		managerName: {//��Ŀ����
			required: true,
			length: [0, 20]
		},
		planBeginDate: {//Ԥ����������
			custom: ['date']
		},
		planEndDate: {//Ԥ�ƽ�������
			custom: ['date']
		},
		workRate: {//����ռ��
			required: true,
			custom: ['percentage']
		},
        incomeType: {
            required: true
        },
		country: {
			required: true
		},
		province: {
			required: true
		},
		city: {
			required: true
		},
		deptName: {
			required: true
		},
		category: {
			required: true
		},
        description: {
            description: true
        }
	});

	// �رչ���
	$("#closeRules").yxeditgrid({
		objName: 'esmcharter[closedetail]',
		url: '?model=engineering_baseinfo_esmcloserule&action=listRuleJson',
		title: '�رչ���',
		tableClass: 'form_in_table',
		isAddOneRow: false,
		isAddAndDel: false,
		colModel: [{
			name : 'ruleId',
			display : "ѡ��",
			width : 50,
			process : function(v,row){
				if(row.isNeed == "1"){
					return "<input type='checkbox' name='esmcharter[closedetail][][ruleId]' value='" + row.ruleId +"' checked='checked'" +
						"style='display:none;'/><span class='red'>��ѡ</span>";
				}else{
					return "<input type='checkbox' name='esmcharter[closedetail][][ruleId]' value='" + row.ruleId +"'/>";
				}
			},
			type : 'statictext'
		}, {
			display: '��Ŀ',
			name: 'ruleName',
			tclass: 'readOnlyTxtNormal',
			readonly: true
		}, {
			display: '����',
			name: 'content',
			tclass: 'readOnlyTxtLong',
			readonly: true
		}]
	});
});