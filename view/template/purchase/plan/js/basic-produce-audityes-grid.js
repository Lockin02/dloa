// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$("#basicYesGrid").yxsubgrid("reload");
};
$(function() {
	$("#basicYesGrid").yxsubgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ
		// url :
		model : 'purchase_plan_basic',
		action : 'pageJsonAuditYes',
		title : '�������ɹ������б�',
		isToolBar : false,
		showcheckbox : false,
		param:{'purchType':"produce"},

		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '�ɹ�������',
					name : 'planNumb',
					sortable : true,
					width : 180
				},  {
					display : '����״̬',
					name : 'ExaStatus',
					sortable : true
				},{
					display : '������',
					name : 'createName',
					sortable : true
				}, {
					display : '����ʱ�� ',
					name : 'sendTime',
					sortable : true
				}, {
					display : 'ϣ�����ʱ�� ',
					name : 'dateHope',
					sortable : true
				}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=purchase_plan_equipment&action=pageJson',
			param : [{
						paramId : 'basicId',
						colId : 'id'
					}],
			colModel : [ {
						name : 'productNumb',
						display : '���ϱ��'
					}, {
						name : 'productName',
						width : 200,
						display : '��������'
					},{
						name : 'pattem',
						display : "����ͺ�"
					},{
						name : 'amountAll',
						display : "��������",
						width : 60
					},{
						name : 'dateHope',
						display : "ϣ���������"
					}]
		},
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					location="?model=purchase_plan_basic&action=read&id="
							+ row.id + "&purchType=" + row.purchType+"&skey="+row['skey_'];
				} else {
					alert("��ѡ��һ������");
				}
			}

		},{
			name : 'aduit',
			text : '�������',
			icon : 'view',
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
				},{
					display : '���ϱ��',
					name : 'productNumb'
				},{
					display : '��������',
					name : 'productName'
				},{
					display : '������',
					name : 'createName'
				}
		],
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