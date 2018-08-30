$(document).ready(function () {
	$.ajax({
		type: "POST",
		async: false,
		url: "?model=hr_permanent_examine&action=findData",
		data: {
			userAccount: $("#userAccount").val()
		},
		success: function (result) {
			if (result == 1) {
				alert("���Ѿ��ύ��ת������,�����ظ��ύ");
				$('#masterbtn').hide();
				$("#savebtn").attr("disabled", "disabled");
			} else if (!($("#assessId").val() > 0)) {
				alert("����������Դ�����ÿ��˷���");
				$('#masterbtn').hide();
				$("#savebtn").attr("disabled", "disabled");
			}
		}
	});
	$.ajax({
		type: "POST",
		async: false,
		url: "?model=hr_permanent_examine&action=tutorExist",
		data: {
			userNo: $("#userNo").val()
		},
		success: function (result) {
			if (result == 1 && $('#planstatus').val() == 0) {
				$("#leader").css("visibility", "visible");
				$("#leader").yxselect_user({
					hiddenId: 'leaderId'
				});
				$("#tutor").yxselect_user({
					hiddenId: 'tutorId'
				});
				$("#leaderShow").attr("class", "blue");
				validate({
					"leader": {
						required: true
					}
				});
				$('#postbtn').hide();
			} else if ($('#planstatus').val() == 0) {
				$("#leader").css("visibility", "visible");
				$("#leader").yxselect_user({
					hiddenId: 'leaderId'
				});
				$("#leaderShow").attr("class", "blue");
				validate({
					"leader": {
						required: true
					}
				});
				$('#masterbtn').hide();
			} else if ($('#planstatus').val() == 1) {
				$("#leader").removeClass('txt')
				$("#leader").addClass("readOnlyText");
				$('#postbtn').hide();
				$('#masterbtn').hide();
			}
		}
	});
	GongArr = getData('HRGZJB');
	addDataToSelect(GongArr, 'schemeTypeCode');
	$("#summaryTable").yxeditgrid({
		objName: 'examine[summaryTable]',
		isAddOneRow: true,
		colModel: [{
			display: '����Ҫ�����[*]',
			name: 'workPoint',
			width: '40%',
			validation: {
				required: true
			}
		}, {
			display: '����ɹ�[*]',
			name: 'outPoint',
			width: '40%',
			validation: {
				required: true
			}
		}, {
			display: '���ʱ��ڵ�[*]',
			name: 'finishTime',
			width: '20%',
			readonly: 'readonly',
			event: {
				focus: function () {
					WdatePicker();
				}
			},
			validation: {
				required: true
			}
		}, {
			name: 'ownType',
			type: 'hidden',
			value: 1
		}]
	});

	$("#planTable").yxeditgrid({
		objName: 'examine[planTable]',
		isAddOneRow: true,
		colModel: [{
			display: '����Ҫ�����[*]',
			name: 'workPoint',
			width: '40%',
			validation: {
				required: true
			}
		}, {
			display: '����ɹ�������׼[*]',
			name: 'outPoint',
			width: '40%',
			validation: {
				required: true
			}
		}, {
			display: '���ʱ��ڵ�[*]',
			name: 'finishTime',
			readonly: 'readonly',
			width: '20%',
			event: {
				focus: function () {
					WdatePicker();
				}
			},
			validation: {
				required: true
			}
		}, {
			name: 'ownType',
			type: 'hidden',
			value: 2
		}]
	});
	if ($("#assessId").val() > 0) {
		$("#schemeTable").yxeditgrid({
			objName: 'examine[schemeTable]',
			url: '?model=hr_permanent_schemelist&action=listJson',
			param: {
				parentId: $("#assessId").val()
			},
			isAddOneRow: true,
			isAddAndDel: false,
			realDel: true,
			colModel: [{
				name: 'standardId',
				type: 'hidden'
			}, {
				display: '������Ŀ',
				name: 'standard',
				width: '15%',
				readonly: 'readonly',
				type: 'statictext'
			}, {
				display: '������Ŀ',
				name: 'standard',
				type: 'hidden'
			}, {
				display: '���˷���',
				name: 'standarScore',
				width: '7%',
				readonly: 'readonly',
				type: 'statictext'
			}, {
				display: '���˷���',
				name: 'standarScore',
				type: 'hidden'
			}, {
				display: '����Ȩ��',
				name: 'standardProportion',
				width: '7%',
				readonly: 'readonly',
				type: 'statictext'
			}, {
				display: '����Ȩ��',
				name: 'standardProportion',
				type: 'hidden'
			}, {
				display: '��������',
				name: 'standardContent',
				width: '30%',
				type: 'statictext',
				align: 'left'
			}, {
				display: '��������',
				name: 'standardContent',
				type: 'hidden'
			}, {
				display: '����Ҫ��',
				name: 'standardPoint',
				width: '30%',
				readonly: 'readonly',
				type: 'statictext',
				align: 'left'
			}, {
				display: '����Ҫ��',
				name: 'standardPoint',
				type: 'hidden'
			}, {
				display: '����[*]',
				name: 'selfScore',
				width: '7%',
				validation: {
					custom: ['onlyNumber']
				},
				event: {
					blur: function () {
						caculate();
					}
				}
			}, {
				name: 'status',
				type: 'hidden',
				value: 1
			}]
		});
	}
	validate({});
})

function submitinfo() {
	if (caculate()) {
		$("#status").val(3);
		$("#form1").submit();
	}

}

function submitmaster() {
	if (caculate()) {
		$("#status").val(2);
		$("#form1").submit();
	}

}

function caculate() {
	var rowAmountVa = 0;
	var cmps = $("#schemeTable").yxeditgrid("getCmpByCol", "selfScore");
	var portions = $("#schemeTable").yxeditgrid("getCmpByCol", "standarScore");
	var standardProportion = $("#schemeTable").yxeditgrid("getCmpByCol", "standardProportion");
	for (var i = 0; i < cmps.length; i++) {
		if (parseInt(cmps[i].value) > parseInt(portions[i].value)) {
			alert("���ֲ��ܸ��ڿ��˷������ֵ");
			cmps[i].value = '';
			$("#selfScore").val("");
			return false;
		} else {
			if (cmps[i].value.indexOf(".") != -1) {
				alert("����������")
				cmps[i].value = '';
				$("#selfScore").val("");
				return false;
			}
		}
	}

	for (var i = 0; i < cmps.length; i++) {
		var percent = accDiv(standardProportion[i].value, 10);
		var mark = accMul(cmps[i].value, percent); // ��ðٷֱȺ�ķ���
		rowAmountVa = accAdd(rowAmountVa, mark, 2); // �������
	}
	if (rowAmountVa > 100) {
		alert("�ܺͲ��ܳ���100��");
		return false;
	}
	$("#selfScore").val(rowAmountVa);
	return true;
}