// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$("#myApplyGrid").yxsubgrid("reload");
};
$(function() {
	$("#myApplyGrid").yxsubgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ
		// url :
		model : 'purchase_plan_basic',
		action : 'rdConfirmJson',
		title : '�з��ɹ�ȷ����Ϣ',
		isToolBar : false,
		showcheckbox : false,
		param : {
			'state' : '0',
			"purchType" : "rdproject",
			'ExaStatus' : "���"
		},

		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '�ɹ�����',
					name : 'purchTypeCName',
					sortable : false
				}, {
					display : '�ɹ�������',
					name : 'planNumb',
					sortable : true,
					width : 150
				}, {
					display : '����״̬',
					name : 'ExaStatus',
					sortable : true
				}, {
					display : 'ȷ��״̬',
					name : 'sureStatus',
					sortable : true,
					process : function(v, row) {
						if (v == "0") {
							return "δȷ��";
						} else {
							return "��ȷ��";
						}
					}
				}, {
					display : '����Դ���ݺ�',
					name : 'sourceNumb',
					sortable : true,
					width : 180,
					hide : true
				}, {
					display : '������',
					name : 'createName',
					sortable : true
				}, {
					display : '����ʱ�� ',
					name : 'sendTime',
					sortable : true,
					width : 80
				}, {
					display : 'ϣ�����ʱ�� ',
					name : 'dateHope',
					sortable : true,
					width : 80,
					hide : true
				}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=purchase_plan_equipment&action=pageJson',
			param : [{
						paramId : 'basicId',
						colId : 'id'
					}],
			colModel : [{
						name : 'productNumb',
						display : '���ϱ��'
					}, {
						name : 'productName',
						width : 200,
						display : '��������'
					}, {
						name : 'pattem',
						display : "����ͺ�"
					}, {
						name : 'unitName',
						display : "��λ",
						width : 50
					}, {
						name : 'amountAll',
						display : "��������",
						width : 70
					}, {
						name : 'dateIssued',
						display : "��������"
					}, {
						name : 'dateHope',
						display : "ϣ���������"
					}]
		},

		// ��չ�Ҽ��˵�
		menusEx : [{
			text : 'ȷ��',
			icon : 'business',
			showMenuFn : function(row) {
				if (row.sureStatus == "0") {
					return true;
				} else {
					return false;
				}

			},
			action : function(row, rows, grid) {
				if (row) {
					showModalWin("index1.php?model=purchase_plan_basic&action=toConfirm&id="
							+ row.id);
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					location = "?model=purchase_plan_basic&action=read&id="
							+ row.id + "&purchType=" + row.purchType + "&skey="
							+ row['skey_'];
				} else {
					alert("��ѡ��һ������");
				}
			}
		}, {
			name : 'aduit',
			text : '�������',
			icon : 'view',
			showMenuFn : function(row) {
				if ((row.ExaStatus == "���" || row.ExaStatus == "���")
						&& (row.purchType == "assets"
								|| row.purchType == "rdproject" || row.purchType == "produce")) {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					showThickboxWin("controller/common/readview.php?itemtype=oa_purch_plan_basic&pid="
							+ row.id
							+ "&placeValuesBeforeTB_=savedValues&TB_iframe=true&modal=false&height=300&width=800");
				}
			}
		}],
		// ��������
		searchitems : [{
					display : '�ɹ�������',
					name : 'seachPlanNumb'
				}, {
					display : '���ϱ��',
					name : 'productNumb'
				}, {
					display : '��������',
					name : 'productName'
				}, {
					display : '����Դ���ݺ�',
					name : 'sourceNumb'
				}],
		// title : '�ͻ���Ϣ',
		// ҵ���������
		// boName : '��Ӧ����ϵ��',
		// Ĭ�������ֶ���
		sortname : "updateTime",
		// Ĭ������˳��
		sortorder : "DESC",
		// ��ʾ�鿴��ť
		isViewAction : false,
		// isAddAction : true,
		isEditAction : false,
		isDelAction : false
	});

});