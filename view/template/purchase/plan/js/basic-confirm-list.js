// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$("#confirmGrid").yxsubgrid("reload");
};
$(function() {
	$("#confirmGrid").yxsubgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ
		// url :
		model : 'purchase_plan_basic',
		action : 'myConfirmListPageJson',
		title : '������ȷ�ϵĲɹ�����',
		isToolBar : false,
		showcheckbox : false,
		bodyAlign:'center',
		param : {
			productSureStatusArr:'0,2'
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
					width : 120
				}, {
					display : '����״̬',
					name : 'ExaStatus',
					sortable : true
				}, {
					display : 'ȷ��״̬',
					name : 'productSureStatus',
					process : function(v, data) {
						if (v == 0) {
							return "δȷ��";
						} else if (v == 1) {
							return "��ȷ��";
						} else {
							return "����ȷ��";
						}
					}
				}, {
					display : '����Դ���ݺ�',
					name : 'sourceNumb',
					sortable : true,
					width : 120
				}, {
					display : '������',
					name : 'sendName',
					sortable : true
				}, {
					display : '����ʱ�� ',
					name : 'sendTime',
					sortable : true,
					width : 80
				}],
		comboEx : [{
					text : 'ȷ��״̬',
					key : 'productSureStatusArr',
					data : [{
								text : 'δȷ��',
								value : 0
							}, {
								text : '����ȷ��',
								value : 2
							}, {
								text : '��ȷ��',
								value : 1
							}]
				}],
		// ���ӱ������
		subGridOptions : {
			url : '?model=purchase_plan_equipment&action=pageJsonForConfirm',
			param : [{
						paramId : 'basicId',
						colId : 'id'
					},{
						paramId : 'purchTypeEqu',
						colId : 'purchType'
					}],
			colModel : [{
						name : 'productCategoryName',
						display : '�������',
						width : 50
					}, {
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
						name : 'dateHope',
						display : "ϣ���������"
					}, {
						name : 'isBack',
						display : "�Ƿ���",
						process : function(v, data) {
							return v == 1 ? "��" : "��";
						}
					}]
		},
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					if(row.purchType=="oa_asset_purchase_apply"){
							showThickboxWin("?model=asset_purchase_apply_apply&action=purchView&id="
							+ row.id+ "&perm=view&placeValuesBefore&TB_iframe=true&modal=false&height=900&width=1000");
					}else{
					location = "?model=purchase_plan_basic&action=read&id="
							+ row.id + "&purchType=" + row.purchType + "&skey="
							+ row['skey_'];
					}
				} else {
					alert("��ѡ��һ������");
				}
			}

		}, {
			text : '����ȷ��',
			icon : 'add',
			showMenuFn : function(row) {
				if (row.productSureStatus == 1) {
					return false;
				}
				return true;
			},
			action : function(row, rows, grid) {
				if (row) {
					if(row.purchType=="oa_asset_purchase_apply"){
						location = "?model=asset_purchase_apply_apply&action=toConfirmProduct&id="
								+ row.id
								+ "&purchType="
								+ row.purchType
								+ "&skey="
								+ row['skey_'];

					}else{
						location = "?model=purchase_plan_basic&action=toConfirmProduct&id="
								+ row.id
								+ "&purchType="
								+ row.purchType
								+ "&skey="
								+ row['skey_'];
					}
				} else {
					alert("��ѡ��һ������");
				}
			}

		}],
		// ��������
		searchitems : [{
					display : '�ɹ�������',
					name : 'planNumbUnion'
				}, {
					display : '���ϱ��',
					name : 'productNumbUnion'
				}, {
					display : '��������',
					name : 'productNameUnion'
				}, {
					display : '����Դ���ݺ�',
					name : 'sourceNumbUnion'
				}],
		// title : '�ͻ���Ϣ',
		// ҵ���������
		// boName : '��Ӧ����ϵ��',
		// Ĭ�������ֶ���
//		sortname : "updateTime",
		// Ĭ������˳��
//		sortorder : "DESC",
		// ��ʾ�鿴��ť
		isViewAction : false,
		// isAddAction : true,
		isEditAction : false,
		isDelAction : false
	});

});