// ��������/�޸ĺ�ص�ˢ�±��
var show_page = function(page) {
	$(".contractchange").yxgrid("reload");
};
$(function() {
	$(".contractchange").yxgrid({
		// �������url�����ô����url������ʹ��model��action�Զ���װ
		// url :
		model : 'purchase_change_contractchange',
		action : 'pageJsonMy',
		param:{notState:"9"},
		title : '�ɹ���������б�',
		isToolBar : false,
		showcheckbox : false,

		// ����Ϣ
		colModel : [{
					display : 'id',
					name : 'id',
					sortable : true,
					hide : true
				}, {
					display : '�ɹ��������',
					name : 'hwapplyNumb',
					sortable : true,
					width : 180
				},  {
					display : 'Ԥ�����ʱ��',
					name : 'dateHope',
					sortable : true
				}, {
					display : '��Ӧ������',
					name : 'suppName',
					sortable : true
				}, {
					display : '��������',
					name : 'paymetType',
					datacode : 'fkfs',
					sortable : true,
					width : 60
				}, {
					display : '��Ʊ����',
					name : 'billingType',
					datacode : 'FPLX',
					sortable : true,
					width : 80
				}, {
					display : '���״̬',
					name : 'ExaStatus',
					sortable : true,
					width : 80
				}, {
					display : '��ע',
					name : 'remark',
					sortable : true,
					width : 160
				}],
		// ��չ�Ҽ��˵�
		menusEx : [{
			text : '�鿴',
			icon : 'view',
			action : function(row, rows, grid) {
				if (row) {
					showOpenWin("?model=purchase_contract_purchasecontract&action=toTabView&id="
							+ row.id + "&applyNumb=" + row.applyNumb+"&skey="+row['skey_']);
				} else {
					alert("��ѡ��һ������");
				}
			}

		}, {
			text : '�༭',
			icon : 'edit',
			showMenuFn : function(row) {
				if (row.ExaStatus == '���ύ' || row.ExaStatus == '���') {
					return true;
				}
				return false;
			},
			action : function(row, rows, grid) {
				if (row) {
					// showThickboxWin("?model=purchase_change_contractchange&action=init"
					// + "&id="
					// + row.id
					// + "&placeValuesBefore&TB_iframe=true&modal=false&height="
					// + 400 + "&width=" + 700);
					parent.location = "?model=purchase_contract_purchasecontract&action=toEditChange&id="
							+ row.id;
				}
			}
		},
				// {
				// text : '�鿴��ʷ�汾',
				// icon : 'view',
				// action : function(row,rows,grid){
				// if(row){
				// location =
				// "?model=purchase_change_contractchange&action=toViewHistory&id="
				// + row.applyNumb;
				// }
				// }
				// },
				{
					text : '�ύ����',
					icon : 'add',
					showMenuFn : function(row) {
						if (row.ExaStatus == '���ύ' || row.ExaStatus == '���') {
							return true;
						}
						return false;
					},
					action : function(row, rows, grid) {
						if (row && row.ExaStatus != "��������"
								&& row.ExaStatus != "���") {
							parent.location = "controller/purchase/change/ewf_index.php?actTo=ewfSelect&billId="
									+ row.id
									+ "&examCode=oa_purch_apply_basic&formName=�ɹ���ͬ����";
						}
					}
				}],
		// ��������
		searchitems : [{
					display : '�������',
					name : 'seachApplyNumb'
				},
								{
									display : '��������',
									name : 'orderTime'
								},
								{
									display : '��Ӧ������',
									name : 'suppName'
								},
								{
									display : '���ϱ��',
									name : 'productNumb'
								},
								{
									display : '��������',
									name : 'productName'
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