// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$("#myApplyGrid").yxsubgrid("reload");
};
$(function() {
	$("#myApplyGrid").yxsubgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ
		// url :
		model : 'purchase_plan_basic',
		action : 'myListPageJson',
		title : '�ѹرղɹ�����',
		isToolBar : false,
		showcheckbox : false,
		param : {
			'state' : '3'
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
					sortable : false,
					width : 80
				}, {
					display : '�ɹ�������',
					name : 'planNumb',
					sortable : true,
					width : 120
				},  {
					display : '����Դ���ݺ�',
					name : 'sourceNumb',
					sortable : true,
					width : 120
				}, {
					display : '���κ�',
					name : 'batchNumb',
					sortable : true,
					width : 120
				}, {
					display : '����ʱ�� ',
					name : 'sendTime',
					sortable : true,
					width : 80
				}, {
					display : 'ϣ�����ʱ�� ',
					name : 'dateHope',
					sortable : true,
					width : 80
				}, {
					display : '����ԭ�� ',
					name : 'applyReason',
					sortable : true,
					width : 300
				}, {
					display : '�ر�ԭ�� ',
					name : 'closeRemark',
					sortable : true,
					width :300
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
						display : '��������',
						process : function(v, data) {
							if (v == "") {
								return data.inputProductName;
							}
							return v;
						}
					},{
						name : 'amountAll',
						display : "��������",
						width : 70
					}, {
						name : 'amountAllOld',
						display : "ԭ��������",
						width : 70
					}, {
						name : 'dateIssued',
						display : "��������",
						width : 80
					}, {
						name : 'dateHope',
						display : "ϣ���������",
						width : 80
					}]
		},
		// ��չ�Ҽ��˵�
		menusEx : [{
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

		},  {
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
				}, {
					display : '���κ�',
					name : 'batchNumb'
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