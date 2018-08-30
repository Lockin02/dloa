$(function() {
	// ѡ��������������
	$("#proposer").yxselect_user({
		hiddenId : 'proposerId',
		mode : 'single',
		event : {
			select : function(e, returnValue) {
				if (returnValue) {
					$('#applyCompanyCode').val(returnValue.companyCode)
					$('#applyCompanyName').val(returnValue.companyName)
				}
			}
		}
	});
	// ѡ�����ȷ�������
	$("#recipient").yxselect_user({
		hiddenId : 'recipientId',
		mode : 'single'
	});
	indeptAddSelect();
	outdeptAddSelect();

	$('#outAgencyType').removeClass("validate[required]");
	$('#inAgencyType').removeClass("validate[required]");
	$('.outAgencyType').hide();
	$('.inAgencyType').hide();

	$("#allocationTable").yxeditgrid({
		objName : 'allocation[allocationitem]',
		url : '?model=asset_assetcard_assetcard&action=listJson',
		isAddAndDel : false,
		param : {
			'id' : $('#assetId').val()
		},
		colModel : [{
			display : '�ʲ�Id',
			name : 'assetId',
			process : function($input,row){
				$input.val(row.id);
			},
			type : 'hidden'
		}, {
			display : '��Ƭ���',
			name : 'assetCode',
			tclass : 'readOnlyTxtNormal',
			validation : {
				required : true
			},
			readonly : true,
			width : 150
		}, {
			display : '�ʲ�����',
			name : 'assetName',
			validation : {
				required : true
			},
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 150
		}, {
			display : 'Ӣ������',
			name : 'englishName',
			type : 'hidden'
		}, {
			display : '��������',
			name : 'buyDate',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 80
		}, {
			display : 'ԭֵ',
			name : 'origina',
			type : 'money',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 80
		}, {
			display : '����ͺ�',
			name : 'spec',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 150
		}, {
			display : '������',
			name : 'sequence',
			process:function($input,row){
				$input.val(row.machineCode);
			},
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 150
		}, {
			display : '����',
			name : 'deploy',
			tclass : 'readOnlyTxtNormal',
			readonly : true,
			width : 150
		}, {
			display : '�����豸',
			name : 'equip',
			type : 'statictext',
			process : function(e, data) {
				if (data) {
					var $href = $("<a>��ϸ</a>");
					$href.attr("href", "#");
					$href.click(function() {
						window.open('?model=asset_assetcard_equip&action=toPage&assetId='
										+ data.id);
					})
					return $href;
				} else {
					return '<a href="#" >��ϸ</a>';
				}
			}

		}, {
			display : '��������',
			name : 'estimateDay',
			type : 'hidden'
		}, {
			display : '�Ѿ�ʹ���ڼ���',
			name : 'alreadyDay',
			type : 'hidden'
		}, {
			display : '���۾ɶ�',
			name : 'monthDepr',
			type : 'hidden',
			process:function($input,row){
				$input.val(row.monthlyDepreciation);
			}
		}, {
			display : '���۾ɽ��',
			name : 'depreciation',
			type : 'hidden'
		}, {
			display : '�����ֵ',
			name : 'salvage',
			type : 'hidden'
		}, {
			display : '����ǰ��;',
			name : 'beforeUse',
			type : 'hidden',
			process:function($input,row){
				$input.val(row.useType);
			}
		}, {
			display : '�������;',
			name : 'afterUse',
			tclass : 'txtshort'

		}, {
			display : '����ǰ��ŵص�',
			name : 'beforePlace',
			type : 'hidden',
			process:function($input,row){
				$input.val(row.place);
			}
		}, {
			display : '������ŵص�',
			name : 'afterPlace',
			tclass : 'txtshort'

		}, {
			display : '��ע',
			name : 'remark',
			tclass : 'txt'
		}]
	});
	/**
	 * ��֤��Ϣ
	 */
	validate({
		"moveDate" : {
			required : true
		},
		"outDeptName" : {
			required : true
		},
		"inDeptName" : {
			required : true
		},
		"proposer" : {
			required : true
		},
		"recipient" : {
			required : true
		}
	});
	//�л���������
	$('#alloType').change(function() {
		if ($('#alloType').val() == 'DTD') {
			//��ʾ����
			$('.inDeptType').show();
			$('.outDeptType').show();
			//���ò��ű���
			$('#outDeptName').addClass("validate[required]");
			$('#inDeptName').addClass("validate[required]");
			//�������ÿ�д
			$('#outDeptName').removeClass("readOnlyTxtNormal").addClass("txt");
			$('#inDeptName').removeClass("readOnlyTxtNormal").addClass("txt");
			//�Ƴ����������б�
			$("#outAgencyName").yxcombogrid_agency("remove");
			$("#inAgencyName").yxcombogrid_agency("remove");
			//ȡ���������
			$('#outAgencyName').removeClass("validate[required]");
			$('#inAgencyName').removeClass("validate[required]");
			//���������ÿ�
			$('.inAgencyType > input').val("");
			$('.inAgencyType').hide();
			$('.outAgencyType > input').val("");
			$('.outAgencyType').hide();
			//������֯�������
			indeptAddSelect();
			outdeptAddSelect();
		} else if ($('#alloType').val() == 'ATA') {
			//��ʾ����
			$('.inAgencyType').show();
			$('.outAgencyType').show();
			//ȡ�����ű���
			$('#outDeptName').removeClass("validate[required]");
			$('#inDeptName').removeClass("validate[required]");
			//�����������
			$('#outAgencyName').addClass("validate[required]");
			$('#inAgencyName').addClass("validate[required]");
			//������֯��������д
			$('#outDeptName').removeClass("txt").addClass("readOnlyTxtNormal");
			$('#inDeptName').removeClass("txt").addClass("readOnlyTxtNormal");
			//�Ƴ���֯�������
			$("#inDeptName").yxselect_dept("remove");
			$("#outDeptName").yxselect_dept("remove");
			//�������������б�
			inAgencyAddSelect();
			outAgencyAddSelect();
			//���ز��Ų��ÿ�
			$('.inDeptType > input').val("");
			$('.inDeptType').hide();
			$('.outDeptType > input').val("");
			$('.outDeptType').hide();
		}
	});
});
function indeptAddSelect() {
	// ѡ����벿�����
	$("#inDeptName").yxselect_dept({
		hiddenId : 'inDeptId',
		mode : 'single'
	});
}
function outdeptAddSelect() {
	// ѡ������������
	$("#outDeptName").yxselect_dept({
		hiddenId : 'outDeptId',
		mode : 'single'
	});
}

function inAgencyAddSelect() {
	// ѡ������������
	$("#inAgencyName").yxcombogrid_agency({
		hiddenId : 'inAgencyCode'
	});
}

function outAgencyAddSelect() {
	// ѡ������������
	$("#outAgencyName").yxcombogrid_agency({
		hiddenId : 'outAgencyCode',
		gridOptions : {
			showcheckbox : false
		}
	});
}
/*
 * ���ȷ��
 */
function confirmAudit() {
	if (confirm("��ȷ��Ҫ�ύ�����?")) {
		$("#form1").attr("action",
				"?model=asset_daily_allocation&action=add&actType=audit");
		$("#form1").submit();

	} else {
		return false;
	}
}
