$(document).ready(function() {

	$("#schemeAttrList").yxeditgrid({
		objName : 'handoverScheme[attrvals]',
		type : 'view',
		url : '?model=hr_leave_formwork&action=listJson',
		param : {
			parentCode : $("#id").val(),
			sort :'sort',
			dir : 'ASC'
		},
		tableClass : 'form_in_table',
		colModel : [{
					display : 'id',
					name : 'id',
					type : 'txt',
					type:'hidden'
				}, {
					display : '������Ŀ',
					name : 'items',
					type : 'txt',
					width : 130
				}, {
					display : '������',
					name : 'recipientName',
					type : 'txt',
					width : 130
				}, {
					display : '������ID',
					name : 'recipientId',
					type : 'txt',
					type:'hidden'
				},{
					display : '��ע',
					name : 'remark',
					type : 'txt',
					width : 130
				},{
					display : '�Ƿ������ǰȷ��',
					name : 'advanceAffirm',
					type : 'txt',
					width : 60,
					process : function(v, row) {
						if (v == "1") {
							return "<span>��</span>";
						} else {
							return "<span>��</span>";
						}
					}
				},{
					display : '����',
					name : 'sort',
					type : 'txt',
					width : 50
				},{
					display : '�Ƿ����ʼ�',
					name : 'mailAffirm',
					type : 'txt',
					process : function(v, row) {
						if (v == "1") {
							return "<span>��</span>";
						} else {
							return "<span>��</span>";
						}
					},
					width : 70
				},{
					display : '����ǰ��',
					name : 'sendPremise',
					type : 'txt',
					width : 80
				}]
	});

        })