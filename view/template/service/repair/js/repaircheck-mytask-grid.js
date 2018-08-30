var show_page = function(page) {
	$("#repaircheckGrid").yxgrid("reload");
};
$(function() {
	$("#repaircheckGrid").yxgrid({
		model : 'service_repair_repaircheck',
		title : '���˼��ά������',
		isEditAction : false,
		isAddAction : false,
		isDelAction : false,
		showcheckbox : false,
		param : {
			'repairUserCode' :	$("#repairUserCode").val()//����ά����Ա����
		},
		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					name : 'docCode',
					display : '���ݱ��',
					sortable : true,
					hide : true
				}, {
					name : 'issuedUserCode',
					display : '�´���code',
					sortable : true,
					hide : true
				}, {
					name : 'issuedUserName',
					display : '�´���',
					sortable : true,
					hide : true
				}, {
					name : 'issuedTime',
					display : '�´�ʱ��',
					sortable : true,
					width : 120
				}, {
					name : 'serilnoName',
					display : '���к�',
					sortable : true,
					width : 200
				}, {
					name : 'prov',
					display : 'ʡ��',
					sortable : true,
					width : 60
				}, {
					name : 'customerName',
					display : '�ͻ�����',
					sortable : true,
					width : 200
				}, {
					name : 'contactUserName',
					display : '�ͻ���ϵ��',
					sortable : true,
					width : 80
				}, {
					name : 'telephone',
					display : '��ϵ�绰',
					sortable : true
				}, {
					name : 'isGurantee',
					display : '�Ƿ����',
					sortable : true,
					width : 80,
					process : function(v) {
						if (v == '0') {
							return "��";
						} else if (v == '1') {
							return "��";
						}
					}
				}, {
					name : 'applyDocCode',
					display : 'ά�����뵥���',
					sortable : true,
					hide : true
				}, {
					name : 'repairDeptCode',
					display : '���ά�޲���code',
					sortable : true,
					hide : true
				}, {
					name : 'repairDeptName',
					display : '���ά�޲���',
					sortable : true,
					hide : true
				}, {
					name : 'repairUserCode',
					display : '���ά����Աcode',
					sortable : true,
					hide : true
				}, {
					name : 'repairUserName',
					display : '���ά����Ա',
					sortable : true,
					hide : true
				}, {
					name : 'productType',
					display : '���Ϸ���',
					sortable : true,
					hide : true
				}, {
					name : 'productCode',
					display : '���ϱ��',
					sortable : true,
					hide : true,
					width : 200
				}, {
					name : 'productName',
					display : '��������',
					sortable : true,
					hide : true,
					width : 250
				}, {
					name : 'pattern',
					display : '����ͺ�',
					sortable : true,
					hide : true
				}, {
					name : 'unitName',
					display : '��λ',
					sortable : true,
					hide : true
				}, {
					name : 'fittings',
					display : '�����Ϣ',
					sortable : true,
					hide : true
				}, {
					name : 'troubleType',
					display : '��������',
					sortable : true,
					width : 100
				}, {
					name : 'troubleInfo',
					display : '��������',
					sortable : true,
					width : 200
				}, {
					name : 'isAgree',
					display : '�Ƿ�ͬ��ά��',
					sortable : true,
					width : 80,
					process : function(v) {
						if (v == '0') {
							return "��";
						} else if (v == '1') {
							return "��";
						} else if (v == '2') {
							return "δȷ��";
						} else {
							return "δȷ��";
						}
					}
				}, {
					name : 'docStatus',
					display : '����״̬',
					sortable : true,
					width : 60,
					process : function(v) {
						if (v == 'YJC') {
							return "�Ѽ��";
						} else if (v == 'YWX') {
							return "��ά��";
						} else if (v == 'WJC') {
							return "δ���";
						} else if (v == 'DHCJ') {
							return "����ؼ�";
						} else {
							return "δ���";
						}
					}
				}, {
					name : 'checkInfo',
					display : '��⴦����',
					sortable : true,
					width : 150

				}, {
					name : 'finishTime',
					display : 'ά�����ʱ��',
					sortable : true

				}, {
					name : 'remark',
					display : '��ע',
					sortable : true,
					width : 150
				}],
		toViewConfig : {
			action : 'toView',
			formWidth : 800,
			formHeight : 500
		},

		menusEx : [{
			text : '��ⷴ��',
			icon : 'business',
			showMenuFn : function(row) {
				if (row.docStatus == "WJC" || row.docStatus == 'DHCJ') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=service_repair_repaircheck&action=toFeedback&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=800");
				}
			}
		}, {
			text : 'ά�����',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.docStatus == "YJC" && row.isAgree == "0") {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("?model=service_repair_repaircheck&action=toConfirm&id="
							+ row.id
							+ "&skey="
							+ row['skey_']
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
				}
			}
		}, {
			text : '�޸Ĵ�����',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.isAgree != '0') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row){
					showThickboxWin("?model=service_repair_repaircheck&action=toEditCheckInfo&id="
						+ row.id
						+ '&skey=' + row['skey_']
						+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=400&width=700");
				}
			}
		}, {
			name : 'view',
			text : "������־",
			icon : 'view',
			action : function(row, rows, grid) {
				showThickboxWin("?model=syslog_operation_logoperation&action=businessView&pkValue="
                    + row.id
                    + "&tableName=oa_service_repair_check"
                    + "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=500&width=850");
			}
		}],
		comboEx : [{
					text : '����״̬',
					key : 'docStatus',
					value : 'WJC',
					data : [{
								text : 'δ���',
								value : 'WJC'
							}, {
								text : '�Ѽ��',
								value : 'YJC'
							}, {
								text : '��ά��',
								value : 'YWX'
							}]
		}],
		searchitems : [{
					display : '���ݱ��',
					name : 'docCode'
				}, {
					display : 'ʡ��',
					name : 'prov'
				}, {
					display : '�ͻ�����',
					name : 'customerName'
				}, {
					display : '��������',
					name : 'productName'
				}, {
					display : '���к�',
					name : 'serilnoNameSer'
				}, {
					display : '��������',
					name : 'troubleType'
				}]
	});
});